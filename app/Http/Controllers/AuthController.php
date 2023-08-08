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
    
            $owner = array(
                'message' => 'Welcome Master', 
                'alert-type' => 'success'
            );

            $artist = array(
                'message' => 'Welcome Back '.$user->name, 
                'alert-type' => 'success'
            );

            $error = array(
                'message' => 'Something Went Wrong', 
                'alert-type' => 'error'
            );
            switch ($user_role) {
                case 1:
                    return redirect('/own')->with($owner);
                    break;
                case 2:
                    return redirect('/'.$user->name)->with($artist);
                    break;
                default:
                    Auth::logout();
                    return redirect('/login')->with($error);
            }
        } else {
            $error = array(
                'message' => 'Please Sign In', 
                'alert-type' => 'error'
            );
            return redirect('/login')->with($error);
        }
    } // END Function (Login Fucntion)

    public function logout(){
        auth()->logout();
        $notification = array(
            'message' => 'Successfully Logged Out', 
            'alert-type' => 'info'
        );
        return redirect('/login')->with($notification);
    } // END Function (Logout)
}
