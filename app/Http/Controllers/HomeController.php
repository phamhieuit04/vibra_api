<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Library;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\select;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listAlbum()
    {
        // Get all artists' albums
        try {
            $albums = Playlist::where('type', 1)->get();
            return ApiResponse::success($albums);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function listSong()
    {
        // Get all songs
        try {
            $songs = Song::whereStatus(2)->orderBy('total_played', 'DESC')->get();
            return ApiResponse::success($songs);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function listArtist()
    {
        // Get all artists
        try {
            $artists = User::with('libraries')
                ->select('id', 'name', 'avatar', 'gender', 'birth')
                ->get();
            foreach ($artists as $artist) {
                $artist->followers = count($artist->libraries);
            }
            $artists = collect($artists)->sortByDesc('followers')->values()->toArray();
            return ApiResponse::success($artists);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        // Add album to library
        $params = $request->all();
        $now = Carbon::now();

        try {
            $library = new Library();
            $library->user_id = Auth::user()->id;
            $library->playlist_id = $id;
            $library->created_at = $now;
            $library->updated_at = $now;
            $library->save();
            return ApiResponse::success(null);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // Get selected album's infor
        $params = $request->all();
        try {
            $album = Playlist::find($id);
            return ApiResponse::success($album);
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
}
