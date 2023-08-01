<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->status == 0) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is inactive. Please contact the administrator.');
            }
    
            switch ($user->role) {
                case 1:
                    return redirect('/own');
                    break;
                case 2:
                    return redirect('/'.$user->name);
                    break;
                default:
                    Auth::logout();
                    return redirect('/login')->with('error', 'Oops! Something went wrong');
            }
        }
        return view('auth.login');
    } // END Function (Login View)

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status == 1 && Auth::attempt($credentials)) {
            $user_role = Auth::user()->role;
    
            switch ($user_role) {
                case 1:
                    return redirect('/own');
                    break;
                case 2:
                    return redirect('/'.$user->name);
                    break;
                default:
                    Auth::logout();
                    return redirect('/login')->with('error', 'Oops! Something went wrong');
            }
        } else {
            return redirect('/login')->with('error', 'Invalid credentials or user is inactive.');
        }
    } // END Function (Login Fucntion)

    public function logout(){
        auth()->logout();
        return back();
    } // END Function (Logout)
}
