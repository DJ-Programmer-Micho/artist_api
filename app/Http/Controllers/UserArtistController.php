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
        $search = $request->input('search');
        $users = User::where('role', 2)
        ->where('name', 'LIKE', '%' . $search . '%')
        ->paginate(10);

        return view('owner.pages.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('owner.pages.users.create');
    }

    public function add(Request $request)
    {

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
        $range = 'sh0!U3';
        $google_id = $request->input('g_id');
        $profit = $request->input('profit');
        $sheets->spreadsheet($google_id)->range($range)->update([[$profit]]);

        return response()->json(['message' => 'User created with profile successfully']);
    }

    public function editUser($id)
    {
        $user = User::with('profile')->find($id);
        if (!$user) {
            return redirect()->route('owner.artists')->with('error', 'Profile not found.');
        }

        return view('owner.pages.users.edit' ,compact('user'));
    }

    public function updateUser(Request $request, $id)
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
        $range = 'sh0!U3';
        $google_id = $request->input('g_id');
        $profit = $request->input('profit');
        $sheets->spreadsheet($google_id)->range($range)->update([[$profit]]);

        return redirect()->route('owner.artists')->with('success', 'Profile updated successfully.');
    }

    public function deleteUser($id){
         // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }
    // Delete the user
    $user->delete();

    return redirect()->route('owner.artists')->with('success', 'User deleted successfully.');
    }
}
