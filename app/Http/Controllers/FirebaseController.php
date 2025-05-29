<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Http\Resources\AuthResource;
use App\Models\DeviceToken;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FirebaseController extends Controller
{
	public function authentication(Request $request)
	{
		$params = $request->all();
		$validate = Validator::make($params, [
			'email' => 'required|email'
		]);
		if (!$validate->fails()) {
			try {
				$user = User::whereEmail($params['email'])->first();
				if (!is_null($user)) {
					if (is_null($user->email_verified_at)) {
						$user->email_verified_at = Carbon::now();
						$user->save();
					}
					$user->token = $user->createToken($user->email)->plainTextToken;
					$user->device_token = $params['device_token'];
					$user->avatar_path = FileHelper::getAvatar($user);
					$device_token = DeviceToken::where('user_id', $user->id)
						->where('token', $params['device_token'])->first();
					if (is_null($device_token)) {
						DeviceToken::create([
							'user_id' => $user->id,
							'token' => $params['device_token']
						]);
					}
					return ApiResponse::success($user);
				} else {
					$user = User::create([
						'name' => explode('@', $params['email'])[0],
						'email' => $params['email'],
						'email_verified_at' => Carbon::now(),
						'password' => Hash::make('12345678'),
						'avatar' => '/default.jpg'
					]);
					$user->token = $user->createToken($user->email)->plainTextToken;
					$user->device_token = $params['device_token'];
					$user->avatar_path = FileHelper::getAvatar($user);
					Storage::disk('public-api')->copy(
						'assets/avatars/default.jpg',
						'uploads/' . FileHelper::getNameFromEmail($user) . '/avatars/default.jpg'
					);
					$device_token = DeviceToken::where('user_id', $user->id)
						->where('token', $params['device_token'])->first();
					if (is_null($device_token)) {
						DeviceToken::create([
							'user_id' => $user->id,
							'token' => $params['device_token']
						]);
					}
					return ApiResponse::success($user);
				}
			} catch (\Throwable $th) {
				return ApiResponse::internalServerError();
			}
		} else {
			return ApiResponse::internalServerError();
		}
	}

	public function notifyNewSong(Request $request)
	{
		$params = $request->all();
		$user = Auth::user();
		$user->device_token = DeviceToken::where('user_id', $user->id)->first()->token;
		try {
			if (!is_null($user) && !is_null($user->device_token)) {
				$title = $params['artist_name'] . ' đã phát hành 1 bài hát mới!';
				$body = 'Nội dung: ' . $params['song_description'];
				$imageUrl = $params['thumbnail_path'];
				FCMService::sendNotifyNewSong($user, $title, $body, $imageUrl);
				return ApiResponse::success();
			} else {
				return ApiResponse::dataNotfound();
			}
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
