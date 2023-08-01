<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\GoogleSheetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


/*
|--------------------------------------------------------------------------
| Auth Route
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class,'index'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('logging');
Route::get('/logout', [AuthController::class,'logout'])->name('logout');


Route::prefix('/{artist}')->middleware(['checkStatus', 'artist', 'checkUser'])->group(function () {
    Route::get('/', [ArtistController::class, 'test'])->name('test.artist');
});

Route::get('sheet',[GoogleSheetController::class, 'index']);