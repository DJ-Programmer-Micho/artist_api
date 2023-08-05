<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CheckUserLink
{

    public function handle($request, Closure $next)
    {
        // Check if the URL has the "artist" parameter
        if ($request->route('artist')) {
            // Get the user's filtered languages based on the "artist" parameter
            $artistName = $request->route('artist');
            // Find the user based on the "artist" parameter
            $artist = User::where('name', $artistName)->first();
            $loggedInUser = User::find(auth()->id());
            // If the user does not exist or does not match the logged-in user, redirect to the home page
            if (!$artist || $artist->id !== $loggedInUser->id) {
                return new RedirectResponse('/login'); // Replace '/' with the URL of your home page
            }
            // Attach the retrieved user to the request so that it is accessible to other middleware and services
            $request->merge(['artist' => $artist]);
        } 
        view()->share('artist', $artist->name);
 
        return $next($request);
    }
}