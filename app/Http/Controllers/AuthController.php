<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Http\Resources\AuthResource;
use App\Models\DeviceToken;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
			$user->avatar_path = FileHelper::getAvatar($user);
			return ApiResponse::success($user);
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
				'avatar' => '/default.jpg',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
			Storage::disk('public-api')->copy(
				'assets/avatars/default.jpg',
				'uploads/' . FileHelper::getNameFromEmail($user) . '/avatars/default.jpg'
			);
			return ApiResponse::success($user);
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	public function logout()
	{
		try {
			Auth::user()->tokens()->delete();
			DeviceToken::where('user_id', Auth::user()->id)->delete();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
