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

class UserArtistController extends Controller
{
    public function index(Request $request)
    {
        $user_noti = array(
            'message' => 'Please Sign In Again', 
            'alert-type' => 'info'
        );
        if (!Auth::check()) {
            return redirect('/login')->with($user_noti);
        } 


        $search = $request->input('search');
        $users = User::where('role', 2)
        ->where('name', 'LIKE', '%' . $search . '%')
        ->paginate(10);

        return view('owner.pages.users.index', compact('users', 'search'));
    }

    public function create()
    {
        $user_noti = array(
            'message' => 'Please Sign In Again', 
            'alert-type' => 'info'
        );
        if (!Auth::check()) {
            return redirect('/login')->with($user_noti);
        } 


        return view('owner.pages.users.create');
    }

    public function add(Request $request)
    {
        $user_noti = array(
            'message' => 'Please Sign In Again', 
            'alert-type' => 'info'
        );
        if (!Auth::check()) {
            return redirect('/login')->with($user_noti);
        } 


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'g_id' => 'nullable|string|max:255',
            's_id' => 'nullable|max:255',
            'c_id' => 'nullable|string|max:255',
            'passport' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'profit' => 'nullable|string|max:255',
        ]);

        $sh = [];
        for ($i = 1; $i <= (int)$request->input('s_id'); $i++){
            $sh[] = 'sh'.$i;
        }
        $sheetsString = json_encode($sh);


        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 2, // Default role of 2
            'status' => 1, // Default status of 1
        ]);

        Profile::create([
            'user_id' => $user->id,
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

        $success = array(
            'message' => 'Artist Has Benn Created Succefully', 
            'alert-type' => 'success'
        );
        return redirect()->route('owner.artists')->with($success);
    }

    public function editUser($id)
    {
        $user_noti = array(
            'message' => 'Please Sign In Again', 
            'alert-type' => 'info'
        );
        if (!Auth::check()) {
            return redirect('/login')->with($user_noti);
        } 

        $user = User::with('profile')->find($id);
        $no_user = array(
            'message' => 'No Artist Found!', 
            'alert-type' => 'error'
        );
        if (!$user) {
            return redirect()->route('owner.artists')->with($no_user);
        }

        return view('owner.pages.users.edit' ,compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user_noti = array(
            'message' => 'Please Sign In Again', 
            'alert-type' => 'info'
        );
        if (!Auth::check()) {
            return redirect('/login')->with($user_noti);
        } 

        $sh = [];
        for ($i = 1; $i <= (int)$request->input('s_id'); $i++){
            $sh[] = 'sh'.$i;
        }
        $sheetsString = json_encode($sh);

        $sheets = new Sheets();
        $range = 'sh0!X3';
        $google_id = $request->input('g_id');
        $profit = $request->input('profit');
        $sheets->spreadsheet($google_id)->range($range)->update([[$profit]]);

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

        $success = array(
            'message' => 'Artist Has Been Updated!', 
            'alert-type' => 'success'
        );
        return redirect()->route('owner.artists')->with($success);
    }

    public function deleteUser($id){
        
         // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    $no_user = array(
        'message' => 'No Artist Found!', 
        'alert-type' => 'error'
    );
    if (!$user) {
        return redirect()->back()->with($no_user);
    }
    // Delete the user
    $user->delete();

    $success = array(
        'message' => 'Artist Has Been Deleted!', 
        'alert-type' => 'success'
    );

    return redirect()->route('owner.artists')->with($success);
    }
}
