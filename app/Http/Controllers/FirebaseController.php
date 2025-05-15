<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class FirebaseController extends Controller
{
	public function authentication(Request $request)
	{
		$params = $request->all();
		try {
			$user = User::whereEmail($params['email'])->first();
			if (!is_null($user)) {
				$user->token = $user->createToken($user->email)->plainTextToken;
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
				return ApiResponse::success($user);
			}
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
