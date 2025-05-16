<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\HomeController;
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
	Route::group(['prefix' => 'profile'], function () {
		Route::get('/show', [UserController::class, 'show']);
	});

	Route::group(['prefix' => 'home'], function () {
		Route::get('/list-album', [HomeController::class, 'listAlbum']);
		Route::get('/show/{id}', [HomeController::class, 'show']);
		Route::get('/store/{id}', [HomeController::class, 'store']);
		Route::get('/list-song', [HomeController::class, 'listSong']);
		Route::get('/list-artist', [HomeController::class, 'listArtist']);
		Route::get('/search', [HomeController::class, 'search']);
	});
	Route::get('/logout', [AuthController::class, 'logout']);
});