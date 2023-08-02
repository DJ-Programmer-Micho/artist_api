<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Sheets;
use Illuminate\Pagination\LengthAwarePaginator;

class ArtistController extends Controller
{
    public $profit = 0.6;

    public function test(){
        $userData = auth()->user();
        $google_id =  $userData->profile->g_id;
        $Sheet_ids =  json_decode($userData->profile->s_id, true);
        // $Sheet_ids =  ["sh1","sh2"];
        $channel_id =  $userData->profile->c_id;
        $apiKey = 'AIzaSyDwsfekAlGRXhzkYOEzSwZiFXsVAxw3ZTc';
        
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

        // dd($finalResult);
        // $sheet = $sheets->spreadsheet($google_id)->sheet($Sheet_id)->range('A1:M')->get();
        // $header = $mergedData->pull(0); // Assuming the first row contains the header

        $data = $finalResult->map(function ($row) use ($header) {
            return array_combine($header, $row);
        });

 
// dd($paginator);
        // MONEY CONTROLL
        $totalEarnings = $data->sum(function ($row) {
            $earnings = floatval(str_replace('$', '', $row['Earnings (USD)']));
            return $earnings * 0.6;
        });
        
        $storeEarning = $data->groupBy('Store')
        ->map(function ($storeData) {
            return $storeData->sum(function ($row) {
                return floatval(str_replace('$', '', $row['Earnings (USD)'])) * $this->profit;
            });
        });

        $songEarning = $data->groupBy('Title')
        ->map(function ($storeData) {
            return $storeData->sum(function ($row) {
                return floatval(str_replace('$', '', $row['Earnings (USD)'])) * $this->profit;
            });
        });

        // QTY/VIEW CONTROLL
        $totalQuantity = $data->sum(function ($row) {
            $qty = floatval($row['Quantity']);
            return $qty;
        });

        $storeQuantities = $data->groupBy('Store')
        ->map(function ($storeData) {
            return $storeData->sum(function ($row) {
                return (int) $row['Quantity'];
            });
        });

        $songQuantities = $data->groupBy('Title')
        ->map(function ($storeData) {
            return $storeData->sum(function ($row) {
                return (int) $row['Quantity'];
            });
        });

        $monthlyEarnings = $data->groupBy(function ($row) {
            return \Carbon\Carbon::parse($row['Sale Month'])->format('Y-m');
        })->map(function ($yearData) {
            return $yearData->sum(function ($row) {
                return floatval(str_replace('$', '', $row['Earnings (USD)'])) * $this->profit;
            });
        });
    
        // Get the years available in the data
        $years = $monthlyEarnings->keys()->map(function ($yearMonth) {
            return \Carbon\Carbon::parse($yearMonth)->format('Y');
        })->unique()->sort();
    
        // Create an array to hold the sorted data for each year
        $sortedData = [];
    
        // Loop through each year and populate the sortedData array
        foreach ($years as $year) {
            // Create an array to hold the earnings for each month in the year
            $yearData = [];
    
            // Loop through each month of the year and get the earnings
            for ($month = 1; $month <= 12; $month++) {
                $yearMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                $yearData[] = $monthlyEarnings->get($yearMonth, 0);
            }
    
            // Add the year data to the sortedData array
            $sortedData[$year] = $yearData;
        }
  

        $apiUrl = 'https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails,statistics,brandingSettings&id=' . $channel_id . '&key=' . $apiKey;

        $response = file_get_contents($apiUrl);
        if ($response === false) {
            // Error handling for failed API request
            die('Failed to fetch data from YouTube API.');
        }
        // Parse the JSON response
        $data = json_decode($response, true);
        if (!isset($data['items']) || empty($data['items'])) {
            // Error handling for missing 'items' key in API response
            die('Invalid API response. Unable to fetch channel data.');
        }
        $channelName = $data['items'][0]['snippet']['title'];
        $thumbnails = $data['items'][0]['snippet']['thumbnails']['medium']['url'];
        $bannerImageUrl = $data['items'][0]['brandingSettings']['image']['bannerExternalUrl'];
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
            'bannerImageUrl' => $bannerImageUrl,
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
        // use WithPagination;
 
 
        
        $userData = auth()->user();
        $google_id =  $userData->profile->g_id;
        $Sheet_ids =  json_decode($userData->profile->s_id, true);
        // $Sheet_ids =  ["sh1","sh2"];
        $channel_id =  $userData->profile->c_id;
        $apiKey = 'AIzaSyDwsfekAlGRXhzkYOEzSwZiFXsVAxw3ZTc';
        
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

        // dd($finalResult);
        // $sheet = $sheets->spreadsheet($google_id)->sheet($Sheet_id)->range('A1:M')->get();
        // $header = $mergedData->pull(0); // Assuming the first row contains the header

        $data = $finalResult->map(function ($row) use ($header) {
            return array_combine($header, $row);
        });

        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $data = $data->filter(function ($row) use ($searchQuery) {
                return stripos($row['Store'], $searchQuery) !== false || 
                       stripos($row['Title'], $searchQuery) !== false;
            });
        }

        // Paginate the filtered data
        $perPage = 60;
        $currentPage = $request->query('page', 1);
        $paginatedData = $data->forPage($currentPage, $perPage);
        $totalItems = $data->count();

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

        return view('dashboard.pages.content',
        [
            'paginator' => $paginator
        ]);
    }

}





// $channelName = $data['items'][0]['snippet']['title'];

// $publishedAt = $data['items'][0]['snippet']['publishedAt'];
// $thumbnails = $data['items'][0]['snippet']['thumbnails'];

// $uploadsPlaylist = $data['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

// $viewCount = $data['items'][0]['statistics']['viewCount'];
// $subscribersCount = $data['items'][0]['statistics']['subscriberCount'];
// $hiddenSubscriberCount = $data['items'][0]['statistics']['hiddenSubscriberCount'];
// $videoCount = $data['items'][0]['statistics']['videoCount'];
