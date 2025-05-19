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
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
	public function index()
	{
		try {
			$library = Library::with('playlists', 'artists', 'songs')
				->where('user_id', Auth::user()->id)
				->get();
			return ApiResponse::success($library);
		} catch (\Throwable $th) {
			return ApiResponse::dataNotfound();
		}
	}

	public function storePlaylist()
	{
		try {
			$user = User::where('id', Auth::user()->id)
				->with('playlists')
				->first();
			Playlist::insert([
				'name' => 'Playlist của tôi #' . count($user->playlists) + 1,
				'author_id' => $user()->id,
				'thumbnail' => $user()->avatar,
				'type' => 2,
				'total_song' => 0,
				'price' => 0,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	public function destroyPlaylist(Request $request, $id)
	{
		if (is_null(Playlist::find($id))) {
			return ApiResponse::dataNotfound();
		}
		try {
			DB::beginTransaction();
			DB::table('libraries')->where('playlist_id', $id)->delete();
			DB::table('playlists')->where('id', $id)->delete();
			DB::commit();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			DB::rollBack();
			return ApiResponse::internalServerError();
		}
	}

	public function updatePlaylist(Request $request, $id)
	{
		$params = $request->all();
		try {
			$playlist = Playlist::where('id', $id)->first();
			$playlist->name = isset($params['name']) ? $params['name'] : $playlist->name;
			$playlist->description = isset($params['description']) ? $params['description'] : $playlist->description;
			$playlist->thumbnail = isset($params['thumbnail']) ? $params['thumbnail'] : $playlist->thumbnail;
			$playlist->updated_at = Carbon::now();
			$playlist->save();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::dataNotfound();
		}
	}

	public function destroyFavoriteSong(Request $request, $id)
	{
		try {
			DB::beginTransaction();
			DB::table('libraries')->where('song_id', $id)->delete();
			DB::commit();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			DB::rollBack();
			return ApiResponse::internalServerError();
		}
	}

	public function destroyFavoriteArtist(Request $request, $id)
	{
		try {
			DB::beginTransaction();
			DB::table('libraries')->where('artist_id', $id)->delete();
			DB::commit();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			DB::rollBack();
			return ApiResponse::internalServerError();
		}
	}

	public function search(Request $request)
	{
		$params = $request->all();
		$libraries = Library::where('user_id', Auth::user()->id)->get();
		$_library = new Library();
		foreach ($libraries as $library) {
			$_library->playlists[] = $library->playlists()
				->where('name', 'like', $params['search-key'] . '%')->first();
			$_library->artists[] = $library->artists()
				->where('name', 'like', $params['search-key'] . '%')->first();
			$_library->songs[] = $library->songs()
				->where('name', 'like', $params['search-key'] . '%')->first();
		}
		$result = [
			'playlists' => collect($_library->playlists)->filter()->values()->toArray(),
			'artists' => collect($_library->artists)->filter()->values()->toArray(),
			'songs' => collect($_library->songs)->filter()->values()->toArray()
		];
		return ApiResponse::success($result);
	}
}
