<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{
	public function syncAvatar()
	{
		try {
			$user = Auth::user();
			$path = 'uploads/' . FileHelper::getNameFromEmail($user) . '/avatars' . $user->avatar;
			$filePath = Storage::disk('public-api')->path($path);
			if (!file_exists($filePath)) {
				return ApiResponse::dataNotfound();
			}
			if (filesize($filePath) >= 5 * 1024 * 1024) {
				GoogleDriveService::chunkFileUpload($filePath, substr($user->avatar, 1));
			} else {
				GoogleDriveService::uploadSmallFile($filePath, substr($user->avatar, 1));
			}
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
