<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Sheets;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ArtistCardController extends Controller
{
    public function index(Request $request){

        $users = User::get();
        // Get the selected user ID from the request
        $userId = $request->input('user');

        // Retrieve the user with the selected ID along with their profile
        $userSelected = User::with('profile')->find($userId);
        $asd = $userSelected;
        $paginator = [];
        if($asd){
            $google_id =  $userSelected->profile->g_id;
            $Sheet_ids =  ["sh0"];
            $channel_id =$userSelected->profile->c_id;

            $cacheKey = 'google_sheets_data_payment';
            $cacheDuration = now()->addHour(); // Cache duration: 1 hour

            // $cacheDataPatyment = Cache::remember($cacheKey, $cacheDuration, function () use ($google_id, $Sheet_ids) {
                $sheetsData = [];
                $sheets = new Sheets();

                foreach ($Sheet_ids as $sheetName) {
                    $range = $sheetName.'!F2:K'; // Specify the range with sheet name
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
        
                // return $finalResult; // Return the fetched data
            // });

            $currentDate = Carbon::now();
            $songs = [];
            
            // Loop through the fetched data
            foreach ($finalResult as $data) {
                $title = $data['Title'];
                $updatedDate = Carbon::createFromFormat('Y-m-d', $data['Updated Date']);
                $expireDate = Carbon::createFromFormat('Y-m-d', $data['Expire Date']);
            
                // Calculate the difference between the "Updated Date" and the current date
                $updatedDaysDifference = $currentDate->diffInDays($updatedDate, false);
            
                // Calculate the difference between the "Expire Date" and the current date
                $expireDaysDifference = $currentDate->diffInDays($expireDate, false);
            
                // Check if the song is already in the $songs array based on its title
                // and get the index of the existing song if found
                $existingSongIndex = collect($songs)->search(function ($song) use ($title) {
                    return $song['title'] === $title;
                });
            
                if ($existingSongIndex !== false) {
                    // If the song exists, check if the current entry has a later "Updated Date"
                    $existingSong = $songs[$existingSongIndex];
                    $existingUpdatedDate = Carbon::createFromFormat('Y-m-d', $existingSong['updated_date']);
            
                    // If the current entry has a later "Updated Date", update the song in the $songs array
                    if ($updatedDate->greaterThan($existingUpdatedDate)) {
                        $songs[$existingSongIndex] = [
                            'image' => $data['Image'],
                            'title' => $title,
                            'status' => 'active',
                            'daysDifference' => $expireDaysDifference,
                            'updated_date' => $data['Updated Date'],
                            'cost' => $data['Cost'],
                        ];
                    }
                } else {
                    // If the song is not found in the $songs array, add it as a new entry
                    $songs[] = [
                        'image' => $data['Image'],
                        'title' => $title,
                        'status' => 'active',
                        'daysDifference' => $expireDaysDifference,
                        'updated_date' => $data['Updated Date'],
                        'cost' => $data['Cost'],
                    ];
                }
            }
            
            // Check if any song in the $songs array should be marked as "expired" or "expiring_soon"
            foreach ($songs as &$song) {
                if ($song['daysDifference'] > 0 && $song['daysDifference'] <= 7) {
                    $song['status'] = 'expiring_soon';
                } elseif ($song['daysDifference'] <= 0) {
                    $song['status'] = 'expired';
                    $song['daysDifference'] = $song['daysDifference'];
                }
            }
            
            // Sort the $songs array based on the status and days remaining
            usort($songs, function ($a, $b) {
                $statusOrder = ['expired', 'expiring_soon', 'active'];
                $statusComparison = array_search($a['status'], $statusOrder) - array_search($b['status'], $statusOrder);
            
                if ($statusComparison !== 0) {
                    return $statusComparison;
                }
            
                // If the status is the same, sort by days remaining
                return $a['daysDifference'] - $b['daysDifference'];
            });
       
            $searchQuery = $request->input('search');

            $filteredData = $searchQuery
            ? collect($songs)->filter(function ($song) use ($searchQuery) {
                return stripos($song['title'], $searchQuery) !== false;
            })
            : collect($songs);

            $perPage = 15; // Adjust the number of songs per page as needed.
            $currentPage = request()->query('page', 1);
            $paginatedData = $filteredData->forPage($currentPage, $perPage);
            $totalItems = $filteredData->count();

            $paginator = new LengthAwarePaginator(
                $paginatedData,
                $totalItems,
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );

        }
        return view('owner.pages.payment', [
            'users' => $users,
            'songs' => $paginator,
        ]); 
    }


}
