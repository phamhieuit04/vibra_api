<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login()
	{
		return ApiResponse::success();
	}

	public function signup(Request $request)
	{
		$params = $request->all();
		try {
			User::insert([
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
}
