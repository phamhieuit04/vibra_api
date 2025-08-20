<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Blocked;
use App\Models\Library;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getArtistSongs(Request $request, $id)
    {
        try {
            $songs = Song::where('author_id', $id)->get();
            FileHelper::getSongsUrl($songs);

            return ApiResponse::success($songs);
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    public function getArtistAlbums(Request $request, $id)
    {
        try {
            $albums = Playlist::where('author_id', $id)
                ->where('type', 1)
                ->get();
            FileHelper::getPlaylistsUrl($albums);

            return ApiResponse::success($albums);
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
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
    public function show(Request $request, $id)
    {
        try {
            $artist = User::find($id);
            $artist->avatar_path = FileHelper::getAvatar($artist);

            return ApiResponse::success($artist);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function follow(Request $request, $id)
    {
        $library = Library::where('user_id', Auth::user()->id)
            ->where('artist_id', $id)
            ->first();
        if (is_null($library)) {
            Library::insert([
                'user_id'    => Auth::user()->id,
                'artist_id'  => $id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return ApiResponse::success();
        } else {
            return ApiResponse::internalServerError();
        }
    }

    public function block(Request $request, $id)
    {
        try {
            Blocked::insert([
                'user_id'    => Auth::user()->id,
                'artist_id'  => $id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }
}
