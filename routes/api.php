<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
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

// Firebase
Route::group(['prefix' => 'firebase'], function () {
	Route::get('/auth', [FirebaseController::class, 'authentication']);
});

// Send verify email
Route::group(['prefix' => 'email'], function () {
	Route::get('/verify', [AuthController::class, 'sendVerify'])->middleware('auth:sanctum');
	Route::get('/verify/{id}/{hash}', [AuthController::class, 'verifyHandler'])
		->middleware('signed')
		->name('verification.verify');
});

Route::middleware('auth:sanctum')->group(function () {
	Route::group(['prefix' => 'library'], function () {
		Route::get('/list-artist', [LibraryController::class, 'listArtist']);
		Route::get('/list-album', [LibraryController::class, 'listAlbum']);
		Route::get('/list-song', [LibraryController::class, 'listSong']);
		Route::post('/store-playlist', [LibraryController::class, 'storePlaylist']);
		Route::post('/update-playlist/{id}', [LibraryController::class, 'updatePlaylist']);
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
	});

	Route::group(['prefix' => 'artist'], function () {
		Route::get('/show/{id}', [ArtistController::class, 'show']);
		Route::get('/follow/{id}', [ArtistController::class, 'follow']);
		Route::get('/block/{id}', [ArtistController::class, 'block']);
	});

	Route::group(['prefix' => 'profile'], function () {
		Route::get('/show', [ProfileController::class, 'show']);
		Route::post('/update', [ProfileController::class, 'update']);
		Route::get('/index', [ProfileController::class, 'index']);
		Route::get('/create-album', [ProfileController::class, 'createAlbum']);
		Route::post('/update-album/{id}', [ProfileController::class, 'updateAlbum']);
		Route::post('upload-song', [ProfileController::class, 'uploadSong']);
	});

	Route::get('/logout', [AuthController::class, 'logout']);
});