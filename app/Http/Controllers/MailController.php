<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Mail\SendGreeting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
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

	public function sendGreeting()
	{
		try {
			Mail::to(Auth::user())->send(new SendGreeting());
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
