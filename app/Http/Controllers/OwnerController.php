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
        if (!Auth::check()) {
            return redirect('/login');
        }        

        return view('owner.pages.dashboard');
    }

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
