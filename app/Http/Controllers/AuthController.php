<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$params = $request->all();
		try {
			Auth::attempt([
				'email' => $params['email'],
				'password' => $params['password']
			]);
			$user = Auth::user();
			$user->token = $user->createToken($user->email)->plainTextToken;
			return ApiResponse::success(new AuthResource($user));
		} catch (\Throwable $th) {
			return ApiResponse::dataNotfound();
		}
	}

	public function signup(Request $request)
	{
		$params = $request->all();
		try {
			$user = User::create([
				'name' => explode('@', $params['email'])[0],
				'email' => $params['email'],
				'password' => Hash::make($params['password']),
				'avatar' => '/default.jpg'
			]);
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	public function logout()
	{
		try {
			Auth::user()->tokens()->delete();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	public function sendVerify()
	{
		try {
			event(new Registered(Auth::user()));
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	public function verifyHandler($id, $hash)
	{
		$user = User::findOrFail($id);
		if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
			if (!$user->hasVerifiedEmail()) {
				$user->markEmailAsVerified();
				return redirect()->away('http://localhost:5173/verify');
			}
		}
		return ApiResponse::internalServerError();
	}
}
