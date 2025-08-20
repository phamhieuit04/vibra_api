<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Playlist;
use App\Models\Song;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleDriveController extends Controller
{
    public function syncFiles(Request $request)
    {
        $params = $request->all();
        $type = $params['type'];
        $songPath = '';
        try {
            $user = Auth::user();
            $userName = FileHelper::getNameFromEmail($user);
            switch ($type) {
                case 'avatars':
                    $songPath = 'uploads/' . $userName . '/avatars' . $user->avatar;
                    $fileName = substr($user->avatar, 1);
                    GoogleDriveService::syncFileToDrive($type, $songPath, $userName, $fileName);
                    break;
                case 'songs':
                    $id = $params['id'];
                    $song = Song::where('id', $id)->with('author')->first();
                    $songPath = 'uploads/' . FileHelper::getNameFromEmail($song->author) . '/songs/' . $song->name . '.mp3';
                    $thumbnailPath = 'uploads/' . FileHelper::getNameFromEmail($song->author) . '/thumbnails/' . substr($song->thumbnail, 1);
                    $lyricsPath = 'uploads/' . FileHelper::getNameFromEmail($song->author) . '/lyrics/' . substr($song->lyrics, 1);
                    GoogleDriveService::syncFileToDrive($type, $songPath, $userName, $song->name);
                    GoogleDriveService::syncFileToDrive('thumbnails', $thumbnailPath, $userName, substr($song->thumbnail, 1));
                    GoogleDriveService::syncFileToDrive('lyrics', $lyricsPath, $userName, substr($song->lyrics, 1));
                    break;
                case 'thumbnails':
                    $id = $params['id'];
                    $playlist = Playlist::where('id', $id)->with('author')->first();
                    $thumbnailPath = 'uploads/' . FileHelper::getNameFromEmail($playlist->author) . '/thumbnails' . $playlist->thumbnail;
                    $thumbnailName = substr($playlist->thumbnail, 1);
                    GoogleDriveService::syncFileToDrive($type, $thumbnailPath, $userName, $thumbnailName);
                    break;
                default:
                    break;
            }

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }
}
