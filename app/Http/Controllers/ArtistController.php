<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Sheets;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class ArtistController extends Controller
{

    public function test(){
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }        

        $userData = Auth::user();
        $google_id =  $userData->profile->g_id;
        $Sheet_ids =  json_decode($userData->profile->s_id, true);
        $channel_id =$userData->profile->c_id;
        $apiKey = env('YOUTUBE_API_V4');
        $profit =  0.6;

       
        
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

        return view('dashboard.pages.dashboard',
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
            'storeQuantities' => $storeQuantities,
            'totalQuantities' => $totalQuantity,
            'storeEarning' => $storeEarning,
            'songEarning' => $songEarning,
            'songQuantities' => $songQuantities,
            'sortedData' => $sortedData,
            'years' => $years
        ]);
    }


    public function content(Request $request){

        if (!Auth::check()) {
            return redirect('/login');
        }        

        $userData = Auth::user();
        $google_id =  $userData->profile->g_id;
        $Sheet_ids =  json_decode($userData->profile->s_id, true);
        $channel_id =$userData->profile->c_id;
        $apiKey = env('YOUTUBE_API_V4');
        $profit =  0.6;

            // Check if the data is already cached
        $cacheKey = 'google_sheets_data';
        $cacheDuration = now()->addHour(); // Cache duration: 1 hour
        $dataCache = Cache::remember($cacheKey, $cacheDuration, function () use ($google_id, $Sheet_ids) {

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
        

        $searchQuery = $request->input('search');


        $filteredData = $searchQuery
            ? $dataCache->filter(function ($row) use ($searchQuery) {
                return stripos($row['Store'], $searchQuery) !== false || 
                       stripos($row['Title'], $searchQuery) !== false;
            })
            : $dataCache;
     

        // Paginate the filtered data
        $perPage = 60;
        $currentPage = $request->query('page', 1);
        $paginatedData = $filteredData->forPage($currentPage, $perPage);
        $totalItems = $filteredData->count();

        $paginator = new LengthAwarePaginator(
            $paginatedData,
            $totalItems,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );


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
        $description = $data['items'][0]['snippet']['description'];
        $customUrl = $data['items'][0]['snippet']['customUrl'];
        $country = $data['items'][0]['snippet']['country'];

        return view('dashboard.pages.content',
        [
            'paginator' => $paginator,
            'channelName' =>  $channelName, 
            'thumbnails'=> $thumbnails,
            'bannerImageUrl' => $bannerImageUrl.'=w2120-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj',
            'customUrl' => $customUrl,
            'country' => $country,
            'description' => $description,
        ]);
    }
}
