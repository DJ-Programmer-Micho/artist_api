<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CheckUserLink
{

    public function handle($request, Closure $next)
    {
        // Check if the URL has the "business_name" parameter
        if ($request->route('artist')) {
            
            // Get the user's filtered languages based on the "business_name"
            $artistName = $request->route('artist');
    
            // Find the user based on the "business_name"
            $artist  = User::where('name', $artistName)->first();
            $loggedInUser = User::find(auth()->id());
    
            // If the user does not exist, redirect to the home page
            if (!$artist || $artist->id !== $loggedInUser->id) {
                return new RedirectResponse('/login'); // Replace '/' with the URL of your home page
            }
        }
        view()->share('artist', $artist->name);

        return $next($request);
    }
}