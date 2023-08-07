<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Sheets;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class OwnerController extends Controller
{
    public function index(){
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }        

        $google_id = '1PlVbc_A1HtVp2ZIB3JqKLNDOoWQlIq9yuS7DmvkGiIE';
        $Sheet_ids = ['main'];
        $cacheKey = 'google_sheets_data';
        $cacheDuration = now()->addHour(); // Cache duration: 1 hour

        // $cacheData = Cache::remember($cacheKey, $cacheDuration, function () use ($google_id, $Sheet_ids) {
            $sheetsData = [];
            $sheets = new Sheets();

            foreach ($Sheet_ids as $sheetName) {
                $range = 'main!B1:K2'; // Specify the range with sheet name
                $rows = $sheets->spreadsheet($google_id)->range($range)->get();
                $sheetsData[$sheetName] = collect($rows);
            }
            
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
            
            $mergedData = $mergedData->filter(function ($row) use ($header) {
                return $row !== $header;
            });
            
            $cards = $mergedData->map(function ($row) use ($header) {
                return array_combine($header, $row);
            });


            foreach ($Sheet_ids as $sheetName_table) {
                $range_table = 'main!A3:K'; // Specify the range with sheet name
                $rows_table = $sheets->spreadsheet($google_id)->range($range_table)->get();
                $sheetsData_table[$sheetName_table] = collect($rows_table);
            }
            
            $mergedData_table = new Collection();
            $header_table = null;
            foreach ($sheetsData_table as $sheetData_table) {
                if (!$sheetData_table->isEmpty()) {
                    if ($header_table === null) {
                        $header_table = $sheetData_table->pull(0);
                    }
                    $mergedData_table = $mergedData_table->merge($sheetData_table);
                }
            }
            
            $mergedData_table = $mergedData_table->filter(function ($row_table) use ($header_table) {
                return $row_table !== $header_table;
            });
            
            $cards_table = $mergedData_table->map(function ($row_table) use ($header_table) {
                return array_combine($header_table, $row_table);
            });


        return view('owner.pages.dashboard.index', [
            'static' => $cards[0],
            'static_table' => $cards_table,
        ]);
    }

    // public function index(){
    //     if (!Auth::check()) {
    //         return redirect('/login');
    //     }        

    //     return view('owner.pages.dashboard.index');
    // }

    // public function index(Request $request)
    // {
    //     $search = $request->input('search');
    //     $users = User::where('name', 'LIKE', '%' . $search . '%')->paginate(10);
    //     return view('owner.pages.usres.index', compact('users', 'search'));
    // }

    public function userProfit(Request $request)
    {
        $search = $request->input('search');
        // $users = User::where('role', 2)
        $users = User::where('name', 'LIKE', '%' . $search . '%')
        ->with('profile')
        ->paginate(10);

        
        foreach ($users as $index => $user){
            $google_id =  $user->profile->g_id;
            $Sheet_ids =  json_decode($user->profile->s_id, true);
            $channel_id =$user->profile->c_id;
            $profit =  0.6;

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

            $totalEarningsArray[$index] = $totalEarnings;
        }
        



        return view('owner.pages.userProfit', [
            'users' => $users,
            'search' => $search,
            'totalEarningsArray' => $totalEarningsArray
        ]);
    }
}
