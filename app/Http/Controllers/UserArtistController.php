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

class UserArtistController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('role', 2)
        ->where('name', 'LIKE', '%' . $search . '%')
        ->paginate(10);

        return view('owner.pages.user', compact('users', 'search'));
    }
}
