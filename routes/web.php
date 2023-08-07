<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ArtistPaymentController;
use App\Http\Controllers\ArtistRecieptController;
use App\Http\Controllers\UserArtistController;
use App\Http\Controllers\UserProfitController;
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

Route::prefix('/user101')->middleware(['checkStatus', 'superadmin'])->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('owner.dashboard');
    // USER ARTIST
    Route::get('/artists', [UserArtistController::class, 'index'])->name('owner.artists');
    Route::get('/artists/create', [UserArtistController::class, 'create'])->name('owner.artists.create');
    Route::post('/artists/create', [UserArtistController::class, 'add'])->name('owner.artists.add');
    Route::get('/artists/edit/{id}', [UserArtistController::class, 'editUser'])->name('owner.artists.edit');
    Route::post('/artists/edit/{id}', [UserArtistController::class, 'updateUser'])->name('owner.artists.update');
    Route::get('/artists/{id}', [UserArtistController::class, 'deleteUser'])->name('owner.artists.delete');
    // USER PROFIT
    Route::get('/profits', [UserProfitController::class, 'index'])->name('owner.profits');
    Route::get('/profits/{id}', [UserProfitController::class, 'view'])->name('owner.profits.view');
    Route::get('/profits/edit/{id}', [UserProfitController::class, 'editUser'])->name('owner.profits.edit');
    Route::post('/profits/edit/{id}', [UserProfitController::class, 'update'])->name('owner.profits.update');
    // USER PAYMENT
    Route::get('/expire', [ArtistPaymentController::class, 'index'])->name('owner.expire');
    Route::get('/expire/create', [ArtistPaymentController::class, 'create'])->name('owner.expire.create');
    Route::post('/expire/create', [ArtistPaymentController::class, 'add'])->name('owner.expire.add');
    Route::get('/expire/edit/{id}/{title}/{g_id}/{img}/{days}/{status}', [ArtistPaymentController::class, 'editPayment'])->name('owner.expire.edit');
    Route::post('/expire/edit/', [ArtistPaymentController::class, 'updatePayment'])->name('owner.expire.update');
    //USER RECIEPT
    Route::get('/reciept', [ArtistRecieptController::class, 'index'])->name('owner.reciept');
    Route::get('/reciept/create', [ArtistRecieptController::class, 'create'])->name('owner.reciept.create');
    Route::post('/reciept/create', [ArtistRecieptController::class, 'add'])->name('owner.reciept.add');
});


Route::prefix('/{artist}')->middleware(['checkStatus', 'artist', 'checkUser'])->group(function () {
    Route::get('/', [ArtistController::class, 'test'])->name('artist.dashboard');
    Route::get('/content', [ArtistController::class, 'content'])->name('artist.content');
    Route::get('/payment', [ArtistController::class, 'payment'])->name('artist.payment');
    Route::get('/reciept', [ArtistController::class, 'reciept'])->name('artist.reciept');
});


// Route::group(['middleware' => 'auth'], function () {
//     // Routes that require authentication
//     Route::prefix('/{artist}')->middleware(['checkStatus', 'artist', 'checkUser'])->group(function () {
//         Route::get('/', [ArtistController::class, 'test'])->name('artist.dashboard');
//         Route::get('/content', [ArtistController::class, 'content'])->name('artist.content');
//     });
// });

Route::get('sheet',[GoogleSheetController::class, 'index']);