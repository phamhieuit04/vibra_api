<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\GoogleDriveController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

Route::group(['prefix' => 'firebase'], function () {
    Route::get('/auth', [FirebaseController::class, 'authentication']);
    Route::get('/notify-new-song', [FirebaseController::class, 'notifyNewSong'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'email'], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/send-greeting', [MailController::class, 'sendGreeting']);
        Route::get('/send-appreciation', [MailController::class, 'sendAppreciation']);
    });
    Route::get('/verify', [MailController::class, 'sendVerify']);
    Route::get('/verify/{id}/{hash}', [MailController::class, 'verifyHandler'])
        ->middleware('signed')
        ->name('verification.verify');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'library'], function () {
        Route::get('/list-artist', [LibraryController::class, 'listArtist']);
        Route::get('/list-playlist', [LibraryController::class, 'listPlaylist']);
        Route::get('/list-song', [LibraryController::class, 'listSong']);
        Route::post('/store-playlist', [LibraryController::class, 'storePlaylist']);
        Route::post('/update-playlist/{id}', [LibraryController::class, 'updatePlaylist']);
        Route::get('/list-playlist-song/{id}', [LibraryController::class, 'listPlaylistSong']);
        Route::get('/destroy-playlist/{id}', [LibraryController::class, 'destroyPlaylist']);
        Route::get('/destroy-favorite-song/{id}', [LibraryController::class, 'destroyFavoriteSong']);
        Route::get('/destroy-favorite-artist/{id}', [LibraryController::class, 'destroyFavoriteArtist']);
        Route::get('/search', [LibraryController::class, 'search']);
    });

    Route::group(['prefix' => 'home'], function () {
        Route::get('/list-album', [HomeController::class, 'listAlbum']);
        Route::get('/show/{id}', [HomeController::class, 'show']);
        Route::get('/store/{id}', [HomeController::class, 'store']);
        Route::get('/list-song', [HomeController::class, 'listSong']);
        Route::get('/list-artist', [HomeController::class, 'listArtist']);
        Route::get('/list-category', action: [HomeController::class, 'listCategory']);
        Route::get('/search', [HomeController::class, 'search']);
    });

    Route::group(['prefix' => 'song'], function () {
        Route::get('/show/{id}', [SongController::class, 'show']);
        Route::get('/store/{id}', [SongController::class, 'store']);
        Route::get('/add-song-to-playlist', [SongController::class, 'addSongToPlaylist']);
        Route::get('/update/{id}', [SongController::class, 'updateTotalPlayed']);
        Route::get('/destroy', [SongController::class, 'destroy']);
    });

    Route::group(['prefix' => 'playlist'], function () {
        Route::get('/show/{id}', [PlaylistController::class, 'show']);
    });

    Route::group(['prefix' => 'artist'], function () {
        Route::get('/get-artist-songs/{id}', [ArtistController::class, 'getArtistSongs']);
        Route::get('/get-artist-albums/{id}', [ArtistController::class, 'getArtistAlbums']);
        Route::get('/show/{id}', [ArtistController::class, 'show']);
        Route::get('/follow/{id}', [ArtistController::class, 'follow']);
        Route::get('/block/{id}', [ArtistController::class, 'block']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/index', [CategoryController::class, 'index']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/show', [ProfileController::class, 'show']);
        Route::post('/update', [ProfileController::class, 'update']);
        Route::get('/list-album', [ProfileController::class, 'listAlbum']);
        Route::get('/list-song', [ProfileController::class, 'listSong']);
        Route::get('/create-album', [ProfileController::class, 'createAlbum']);
        Route::post('/update-album/{id}', [ProfileController::class, 'updateAlbum']);
        Route::post('/upload-song', [ProfileController::class, 'uploadSong']);
        Route::get('/payment-history', [ProfileController::class, 'getPaymentHistory']);
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::get('/create-bill', [PaymentController::class, 'createBill']);
        Route::get('/update-status/{id}', [PaymentController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'google-drive'], function () {
        Route::get('/sync-files', [GoogleDriveController::class, 'syncFiles']);
    });

    Route::get('/logout', [AuthController::class, 'logout']);
});
