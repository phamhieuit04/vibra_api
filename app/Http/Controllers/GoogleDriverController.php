<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\User;
use App\Services\GoogleDriverService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GoogleDriverController extends Controller
{
    public function syncAvatar()
    {
        try {
            $user = User::where('id', Auth::user()->id)->first();
            // TODO
            $avatarUrl = FileHelper::getAvatar($user);
            $fileName = $user->avatar;

            if (!file_exists($avatarUrl)) {
                return [
                    'code' => 204,
                    'status' => 'success',
                    'message' => 'File not found'
                ];
            }

            $googleService = new GoogleDriverService();
            $googleService->synchronize($avatarUrl, $fileName);
            dd($avatarUrl);
            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }
}
