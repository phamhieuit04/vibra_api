<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\DeviceToken;
use App\Models\Song;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
                        $user->touch();
                    }
                    $user->token = $user->createToken($user->email)->plainTextToken;
                    $user->device_token = $params['device_token'];
                    $user->avatar_path = FileHelper::getAvatar($user);
                    $device_token = DeviceToken::where('user_id', $user->id)
                        ->where('token', $params['device_token'])->first();
                    if (is_null($device_token)) {
                        DeviceToken::create([
                            'user_id' => $user->id,
                            'token'   => $params['device_token']
                        ]);
                    }

                    return ApiResponse::success($user);
                } else {
                    $user = User::create([
                        'name'              => explode('@', $params['email'])[0],
                        'email'             => $params['email'],
                        'email_verified_at' => Carbon::now(),
                        'password'          => Hash::make('12345678'),
                        'avatar'            => '/default.jpg'
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
                            'token'   => $params['device_token']
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
        $artist = User::find($params['artist_id']);
        $song = Song::find($params['song_id']);

        $title = $artist->name . ' đã phát hành 1 bài hát mới!';
        $body = 'Nội dung: ' . $song->description;
        $imageUrl = FileHelper::getUrl('thumbnails', $song);

        try {
            $users = User::withWhereHas('deviceTokens')
                ->withWhereHas('userLibraries', function ($query) use ($artist) {
                    $query->where('artist_id', $artist->id);
                })
                ->get();
            $device_tokens = collect([]);
            $users->each(function ($user) use ($device_tokens) {
                foreach ($user->deviceTokens as $device_token) {
                    $device_tokens->push($device_token->token);
                }
            });
            foreach ($device_tokens as $token) {
                FCMService::sendNotifyNewSong($token, $title, $body, $imageUrl);
            }

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }
}
