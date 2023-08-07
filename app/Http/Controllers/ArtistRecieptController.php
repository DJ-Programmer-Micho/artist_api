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

class ArtistRecieptController extends Controller
{
    public function index(Request $request){
        $id = '';
        $finalResult = [];
        $google_id = '';

        $users = User::where('role', 2)->get();
        // Get the selected user ID from the request
        $userId = $request->input('user');

        // Retrieve the user with the selected ID along with their profile
        $userSelected = User::with('profile')->find($userId);

        if($userSelected){
            $id = $userSelected->id;
            $google_id =  $userSelected->profile->g_id;
            $Sheet_ids =  ["sh0"];
            $channel_id =$userSelected->profile->c_id;

            $cacheKey = 'google_sheets_data_payment';
            $cacheDuration = now()->addHour(); // Cache duration: 1 hour

            // $cacheDataPatyment = Cache::remember($cacheKey, $cacheDuration, function () use ($google_id, $Sheet_ids) {
                $sheetsData = [];
                $sheets = new Sheets();

                foreach ($Sheet_ids as $sheetName) {
                    $range = $sheetName.'!M2:N'; // Specify the range with sheet name
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

        }
        return view('owner.pages.reciepts.index', [
            'id' => $id,
            'users' => $users,
            'datas' => $finalResult,
            'g_id' => $google_id
        ]); 
    }

    public function create(){

        $users = User::where('role',2)->get();

        return view('owner.pages.reciepts.create', [
            'users' => $users,
        ]);
    }

    public function add(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'user' => 'required',
            'date' => 'required|date',
            'cost' => 'required|numeric|min:0',
        ]);
        

        $user = $request->input('user');
        
        $profile = User::with('profile')->where('id', $user)->first();
        
        if ($profile) {
            $google_id = $profile->profile->g_id;
       

        $date = $request->input('date');
        $cost = $request->input('cost');


        $sheets = new Sheets();
        $logRange = 'sh0!M2:N2';
        $sheets->spreadsheet($google_id)->range($logRange)->append([[$date,$cost]]);

        // return response()->json(['message' => 'User created with profile successfully']);
        return redirect(url('user101/reciept?user='.$user));
        } 
        return redirect()->route('owner.reciept');
    }
}
