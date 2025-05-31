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
			$userName = FileHelper::getNameFromEmail($user);
			$path = 'uploads/' . $userName . '/avatars' . $user->avatar;

			$filePath = Storage::disk('public-api')->path($path);
			if (!file_exists($filePath)) {
				return ApiResponse::dataNotfound();
			}

			$userFolder = GoogleDriveService::findFolderByName($userName);
			if (!$userFolder) {
				$userFolderId = GoogleDriveService::createFolder($userName);
			} else {
				$userFolderId = is_object($userFolder) ? $userFolder->getId() : $userFolder['id'];
			}

			if (!$userFolderId) {
				return ApiResponse::internalServerError();
			}

			$avatarsFolder = GoogleDriveService::findFolderByName('avatars', $userFolderId);
			if (!$avatarsFolder) {
				$avatarsFolderId = GoogleDriveService::createFolder('avatars', $userFolderId);
			} else {
				$avatarsFolderId = is_object($avatarsFolder) ? $avatarsFolder->getId() : $avatarsFolder['id'];
			}

			if (!$avatarsFolderId) {
				return ApiResponse::internalServerError();
			}

			$fileName = substr($user->avatar, 1);
			if (filesize($filePath) >= 5 * 1024 * 1024) {
				GoogleDriveService::chunkFileUpload($filePath, $fileName, $avatarsFolderId);
			} else {
				GoogleDriveService::uploadSmallFile($filePath, $fileName, $avatarsFolderId);
			}

			return ApiResponse::success($userFolder);
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
