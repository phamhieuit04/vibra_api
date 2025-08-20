<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Blocked;
use App\Models\Category;
use App\Models\Library;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listAlbum()
    {
        // Get all artists' albums
        try {
            $albums = Playlist::where('type', 1)
                ->with('author')
                ->get();
            foreach ($albums as $album) {
                $album->author->followers = count($album->author->libraries);
            }
            FileHelper::getPlaylistsUrl($albums);

            return ApiResponse::success($albums);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function listSong()
    {
        // Get all $songs which is not blocked by user
        try {
            $blocked = Blocked::where('user_id', Auth::id())
                ->whereNotNull('song_id')
                ->pluck('song_id');
            $songs = Song::whereStatus(1)
                ->whereNotIn('id', $blocked)
                ->with([
                    'author',
                    'playlist',
                    'category',
                    'playlist.author'
                ])
                ->orderBy('total_played', 'DESC')
                ->get();
            foreach ($songs as $song) {
                $song->author->followers = count($song->author->libraries);
            }
            FileHelper::getSongsUrl($songs);

            return ApiResponse::success($songs);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function listArtist()
    {
        // Get all artists
        try {
            $blocked = Blocked::where('user_id', Auth::id())
                ->whereNotNull('artist_id')
                ->pluck('artist_id');
            $artists = User::whereNotIn('id', $blocked)->get();
            foreach ($artists as $artist) {
                $artist->avatar_path = FileHelper::getAvatar($artist);
                $artist->followers = count($artist->libraries);
                unset($artist->libraries);
            }
            $artists = collect($artists)->sortByDesc('followers')->values()->all();

            return ApiResponse::success($artists);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function listCategory()
    {
        try {
            $categories = Category::all();
            foreach ($categories as $category) {
                $category->thumbnail_path = FileHelper::getThumbnail('category', $category);
            }

            return ApiResponse::success($categories);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {
            Library::insert([
                'user_id'     => Auth::user()->id,
                'playlist_id' => $id,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ]);

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // Get selected album's infor
        try {
            $album = Playlist::where('id', $id)->with('author')->first();
            $album->author->followers = count($album->author->libraries);
            $album->thumbnail_path = FileHelper::getThumbnail('playlist', $album);
            $album->author->avatar_path = FileHelper::getAvatar();

            return ApiResponse::success($album);
        } catch (\Throwable $th) {
            return ApiResponse::dataNotfound();
        }
    }

    public function search(Request $request)
    {
        $params = $request->all();
        try {
            $artists = User::where('name', 'LIKE', '%' . $params['search-key'] . '%')->get();
            foreach ($artists as $artist) {
                $artist->followers = count($artist->libraries);
            }
            $songs = Song::where('name', 'LIKE', '%' . $params['search-key'] . '%')
                ->with(['author', 'playlist', 'category'])
                ->get();
            $albums = Playlist::where('name', 'LIKE', '%' . $params['search-key'] . '%')
                ->with('author')
                ->get();
            FileHelper::getArtistsUrl($artists);
            FileHelper::getSongsUrl($songs);
            FileHelper::getPlaylistsUrl($albums);
            $results = [
                'artists' => $artists,
                'songs'   => $songs,
                'albums'  => $albums
            ];

            return ApiResponse::success($results);
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
