<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Bill;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listAlbum()
    {
        $albums = Playlist::where('author_id', Auth::user()->id)
            ->where('type', 1)
            ->with('author')
            ->get();
        FileHelper::getPlaylistsUrl($albums);

        return ApiResponse::success($albums);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $profile = User::find(Auth::user()->id);
            $profile->avatar_path = FileHelper::getAvatar();

            return ApiResponse::success($profile);
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $params = $request->all();
        try {
            $user = Auth::user();
            $user->update([
                'name'        => isset($params['name']) ? $params['name'] : $user->name,
                'gender'      => isset($params['gender']) ? $params['gender'] : $user->gender,
                'birth'       => isset($params['birth']) ? $params['birth'] : $user->birth,
                'description' => isset($params['description']) ? $params['description'] : $user->description,
            ]);
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                if (FileHelper::store($file, 'avatars')) {
                    $user->avatar = '/' . $file->getClientOriginalName();
                }
            }
            $user->touch();

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createAlbum()
    {
        try {
            $user = User::where('id', Auth::user()->id)
                ->with('playlists')
                ->first();
            Playlist::insert([
                'name'       => 'Album của tôi #' . count($user->playlists) + 1,
                'author_id'  => $user->id,
                'thumbnail'  => $user->avatar,
                'type'       => 1,
                'total_song' => 0,
                'price'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    public function updateAlbum(Request $request, $id)
    {
        $params = $request->all();
        $playlist = Playlist::find($id);
        $playlist->update([
            'name'        => isset($params['name']) ? $params['name'] : $playlist->name,
            'description' => isset($params['description']) ? $params['description'] : $playlist->description,
            'price'       => isset($params['price']) ? $params['price'] : $playlist->price,
        ]);
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            if (FileHelper::store($file, 'thumbnails')) {
                $playlist->thumbnail = '/' . $file->getClientOriginalName();
            }
        }
        $playlist->touch();
        $playlist->thumbnail_path = FileHelper::getThumbnail('playlist', $playlist);

        return ApiResponse::success($playlist);
    }

    public function uploadSong(Request $request)
    {
        $params = $request->all();
        if ($request->hasFile('song') && $request->hasFile('lyric') && $request->hasFile('thumbnail')) {
            FileHelper::store($request->file('song'), 'songs');
            FileHelper::store($request->file('lyric'), 'lyrics');
            FileHelper::store($request->file('thumbnail'), 'thumbnails');
            $song = Song::create([
                'name'         => FileHelper::getFileName($request->file('song')),
                'author_id'    => Auth::user()->id,
                'playlist_id'  => isset($params['playlist-id']) ? $params['playlist-id'] : null,
                'category_id'  => $params['category-id'],
                'lyrics'       => '/' . $request->file('lyric')->getClientOriginalName(),
                'thumbnail'    => '/' . $request->file('thumbnail')->getClientOriginalName(),
                'description'  => $params['description'],
                'total_played' => 0,
                'status'       => 1,
                'price'        => $params['price'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now()
            ]);
            if (isset($params['playlist-id'])) {
                $playlist = Playlist::find($params['playlist-id']);
                $playlist->total_song = $playlist->total_song + 1;
                $playlist->touch();
            }

            return ApiResponse::success($song);
        } else {
            return ApiResponse::dataNotfound();
        }
    }

    public function listSong()
    {
        try {
            $songs = Song::where('author_id', Auth::user()->id)->get();
            FileHelper::getSongsUrl($songs);

            return ApiResponse::success($songs);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function getPaymentHistory()
    {
        $bills = Bill::where('user_id', Auth::user()->id)->get();
        try {
            $bills->each(function ($bill) {
                if (is_null($bill->playlist_id)) {
                    $song = Bill::where('bills.id', $bill->id)
                        ->join('bill_details', 'bills.id', 'bill_details.bill_id')
                        ->join('songs', 'songs.id', 'bill_details.song_id')
                        ->first();
                    $bill->song = ['name' => $song->name, 'quantity' => 1, 'price' => $song->price];
                } else {
                    Bill::where('bills.id', $bill->id)
                        ->join('playlists', 'bills.playlist_id', 'playlists.id')
                        ->get()->each(function ($_bill) use ($bill) {
                            $bill->playlist = ['name' => $_bill->name, 'quantity' => 1, 'price' => $_bill->price];
                        });
                }
            });

            return ApiResponse::success($bills);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }
}
