<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
	public function syncAvatar()
	{
		try {
			$user = User::where('id', Auth::user()->id)->first();
			// TODO: fix upload file with server path
			$avatarUrl = FileHelper::getAvatar($user);
			$fileName = $user->avatar;
			if (!file_exists($avatarUrl)) {
				return [
					'code' => 204,
					'status' => 'success',
					'message' => 'File not found'
				];
			}
			GoogleDriveService::uploadFile($avatarUrl, $fileName);
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
