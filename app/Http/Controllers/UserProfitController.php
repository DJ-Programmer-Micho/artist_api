<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Sheets;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class UserProfitController extends Controller
{
    public function index(Request $request)
    {

            $usersToSelect = User::where('role', '2')->get();
            
            $search = $request->input('search');
            // $users = User::where('role', 2)
            $users = User::where('role', '2')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->with('profile')
            ->paginate(10);


            if(!empty($users)){

                foreach ($users as $index => $user){
                $google_id =  $user->profile->g_id;
                $Sheet_ids =  json_decode($user->profile->s_id, true);
                $profit =  $user->profile->profit;;

                $sheetsData = [];
                $sheets = new Sheets();
                
                foreach ($Sheet_ids as $sheetName) {
                    $range = $sheetName.'!A1:M'; // Specify the range with sheet name
                    $rows = $sheets->spreadsheet($google_id)->range($range)->get();
                    $sheetsData[$sheetName] = collect($rows);
                }

                // Merge data from all sheets into one collection
                $mergedData = new Collection();
                $header = null;
                foreach ($sheetsData as $sheetData) {
                    if (!$sheetData->isEmpty()) {
                        if ($header === null) {
                            $header = $sheetData->pull(0);
                        }
                        $mergedData = $mergedData->merge($sheetData);
                    }
                }
                
                // Remove rows with the same values as the header
                $mergedData = $mergedData->filter(function ($row) use ($header) {
                    return $row !== $header;
                });
                
                // Map the merged data to associate each row with the header names
                $finalResult = $mergedData->map(function ($row) use ($header) {
                    return array_combine($header, $row);
                });
        
                $totalEarnings = $finalResult->sum(function ($row) use ($profit) {
                    $earnings = floatval(str_replace('$', '', $row['Earnings (USD)']));
                    return $earnings * $profit;
                });

                $sheetTax = new Sheets();
                $range = 'sh0!Q3'; // Specify the range with sheet name
                $dataTax = $sheetTax->spreadsheet($google_id)->range($range)->get();
                
                $taxValue = isset($dataTax[0][0]) ? str_replace(',', '', $dataTax[0][0]) : '0';
                $wallet[$index] = $totalEarnings - (float) $taxValue;

                $totalEarningsArray[$index] = $totalEarnings;
            }
            
        }
            if (!isset($totalEarningsArray)) {
                $totalEarningsArray = 0; // or any default value as per your requirement
            }

            if (!isset($wallet)) {
                $wallet = 0; // or any default value as per your requirement
            }
    
            return view('owner.pages.artistProfiles.index', [
                'usersToSelect' => $usersToSelect,
                'users' => $users,
                'search' => $search,
                'totalEarningsArray' => $totalEarningsArray,
                'wallet' => $wallet
            ]);
    }

    public function view($id){

        $artistData = User::with('profile')->where('id', $id)->first();

        $google_id =  $artistData->profile->g_id;
        $Sheet_ids =  json_decode($artistData->profile->s_id, true);
        $channel_id =$artistData->profile->c_id;
        $tax =  'sh0';
        $apiKey = env('YOUTUBE_API_V4');
        $profit =  $artistData->profile->profit;

        $cacheKey = 'google_sheets_data';
        $cacheDuration = now()->addHour(); // Cache duration: 1 hour



        $cacheData = Cache::remember($cacheKey, $cacheDuration, function () use ($google_id, $Sheet_ids) {

            $sheetsData = [];
            $sheets = new Sheets();

            foreach ($Sheet_ids as $sheetName) {
                $range = $sheetName.'!A1:M'; // Specify the range with sheet name
                $rows = $sheets->spreadsheet($google_id)->range($range)->get();
                $sheetsData[$sheetName] = collect($rows);
            }

            // Merge data from all sheets into one collection
            $mergedData = new Collection();
            $header = null;
            foreach ($sheetsData as $sheetData) {
                if (!$sheetData->isEmpty()) {
                    if ($header === null) {
                        $header = $sheetData->pull(0);
                    }
                    $mergedData = $mergedData->merge($sheetData);
                }
            }
            
            // Remove rows with the same values as the header
            $mergedData = $mergedData->filter(function ($row) use ($header) {
                return $row !== $header;
            });
            
            // Map the merged data to associate each row with the header names
            $finalResult = $mergedData->map(function ($row) use ($header) {
                return array_combine($header, $row);
            });
    
            return $finalResult; // Return the fetched data
        });

        // MONEY CONTROLL
        $totalEarnings = $cacheData->sum(function ($row) use ($profit) {
            $earnings = floatval(str_replace('$', '', $row['Earnings (USD)']));
            return $earnings * $profit;
        });
        
        $storeEarning = $cacheData->groupBy('Store')
        ->map(function ($storeData) use ($profit) {
            return $storeData->sum(function ($row) use ($profit) {
                return floatval(str_replace('$', '', $row['Earnings (USD)'])) * $profit;
            });
        });

        $songEarning = $cacheData->groupBy('Title')
        ->map(function ($storeData) use ($profit) {
            return $storeData->sum(function ($row) use ($profit) {
                return floatval(str_replace('$', '', $row['Earnings (USD)'])) * $profit;
            });
        });

        // QTY/VIEW CONTROLL
        $totalQuantity = $cacheData->sum(function ($row) {
            $qty = floatval($row['Quantity']);
            return $qty;
        });

        $storeQuantities = $cacheData->groupBy('Store')
        ->map(function ($storeData) {
            return $storeData->sum(function ($row) {
                return (int) $row['Quantity'];
            });
        });

        $songQuantities = $cacheData->groupBy('Title')
        ->map(function ($storeData) {
            return $storeData->sum(function ($row) {
                return (int) $row['Quantity'];
            });
        });

        $monthlyEarnings = $cacheData->groupBy(function ($row) {
            return \Carbon\Carbon::parse($row['Sale Month'])->format('Y-m');
        })->map(function ($yearData) use ($profit) {
            return $yearData->sum(function ($row) use ($profit){
                return floatval(str_replace('$', '', $row['Earnings (USD)'])) * $profit;
            });
        });
    
        $years = $monthlyEarnings->keys()->map(function ($yearMonth) {
            return \Carbon\Carbon::parse($yearMonth)->format('Y');
        })->unique()->sort();
    
        $sortedData = [];
    
        foreach ($years as $year) {
            $yearData = [];
            for ($month = 1; $month <= 12; $month++) {
                $yearMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                $yearData[] = $monthlyEarnings->get($yearMonth, 0);
            }
            $sortedData[$year] = $yearData;
        }
  

        $apiUrl = 'https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails,statistics,brandingSettings&id=' . $channel_id . '&key=' . $apiKey;
        $response = file_get_contents($apiUrl);
        if ($response === false) {
            die('Failed to fetch data from YouTube API.');
        }
        // dd($response);
        $data = json_decode($response, true);
        if (!isset($data['items']) || empty($data['items'])) {
            die('Invalid API response. Unable to fetch channel data.');
        }
        $channelName = $data['items'][0]['snippet']['title'];
        $thumbnails = $data['items'][0]['snippet']['thumbnails']['medium']['url'];
        $bannerImageUrl = $data['items'][0]['brandingSettings']['image']['bannerExternalUrl'];
        // dd($bannerImageUrl);
        $subscribersCount = $data['items'][0]['statistics']['subscriberCount'];
        $viewCount = $data['items'][0]['statistics']['viewCount'];
        $uploadsCount = $data['items'][0]['statistics']['videoCount'];
        $hiddenSubscriberCount = $data['items'][0]['statistics']['hiddenSubscriberCount'];
        $description = $data['items'][0]['snippet']['description'];
        $customUrl = $data['items'][0]['snippet']['customUrl'];
        $country = $data['items'][0]['snippet']['country'];

        $sheetTax = new Sheets();
        $range = $tax.'!Q3'; // Specify the range with sheet name
        $dataTax = $sheetTax->spreadsheet($google_id)->range($range)->get();
        
        $taxValue = isset($dataTax[0][0]) ? str_replace(',', '', $dataTax[0][0]) : '0';
        $wallet = $totalEarnings - (float) $taxValue;

        return view('owner.pages.artistProfiles.view',
        [
            'channelName' =>  $channelName, 
            'thumbnails'=> $thumbnails,
            'bannerImageUrl' => $bannerImageUrl.'=w2120-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj',
            'customUrl' => $customUrl,
            'country' => $country,
            'description' => $description,
            'subscribersCount' => $subscribersCount, 
            'viewCount' => $viewCount, 
            'uploadsCount'=>$uploadsCount, 
            'hiddenSubscriberCount'=>$hiddenSubscriberCount, 
            'earningsSum' => $totalEarnings,
            'dataTax' => $dataTax[0],
            'wallet' => $wallet,
            'storeQuantities' => $storeQuantities,
            'totalQuantities' => $totalQuantity,
            'storeEarning' => $storeEarning,
            'songEarning' => $songEarning,
            'songQuantities' => $songQuantities,
            'sortedData' => $sortedData,
            'years' => $years
        ]);
    }

    public function editUser($id)
    {
        $user = User::with('profile')->find($id);
        if (!$user) {
            return redirect()->route('owner.artists')->with('error', 'Profile not found.');
        }

        return view('owner.pages.artistProfiles.edit' ,compact('user'));
    }

    public function update(Request $request, $id)
    {

        $sh = [];
        for ($i = 1; $i <= (int)$request->input('s_id'); $i++){
            $sh[] = 'sh'.$i;
        }
        $sheetsString = json_encode($sh);

        User::where('id',$id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'status' => $request->input('status'),
        ]);

        Profile::where('user_id',$id)->update([
            'g_id' => $request->input('g_id'),
            's_id' => $sheetsString,
            'c_id' => $request->input('c_id'),
            'passport' => $request->input('passport'),
            'phone' => $request->input('phone'),
            'profit' => $request->input('profit'),
        ]);

        $sheets = new Sheets();
        $range = 'sh0!X3';
        $google_id = $request->input('g_id');
        $profit = $request->input('profit');
        $sheets->spreadsheet($google_id)->range($range)->update([[$profit]]);

        return redirect()->route('owner.profits')->with('success', 'Profile updated successfully.');
    }
}
