<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Mail\SendAppreciation;
use App\Mail\SendGreeting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
	public function sendVerify(Request $request)
	{
		$params = $request->all();
		try {
			event(new Registered(User::find($params['id'])));
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
				return ApiResponse::success(true);
			}
			return ApiResponse::unprocessableEntity();
		}
		return ApiResponse::success(false);
	}

	public function sendGreeting()
	{
		try {
			Mail::to(Auth::user())->send(new SendGreeting());
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	public function sendAppreciation()
	{
		try {
			Mail::to(Auth::user())->send(new SendAppreciation());
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
