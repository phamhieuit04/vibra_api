<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
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
		$libraries = Library::with('author', 'playlist', 'playlist.author', 'artist', 'song', 'song.author', 'song.playlist', 'song.category')
			->where('user_id', Auth::user()->id)
			->get();
		foreach ($libraries as $library) {
			$library->author->avatar_path = FileHelper::getAvatar($library->author);
			if (!is_null($library->playlist)) {
				$playlist = $library->playlist;
				$playlist->thumbnail_path = FileHelper::getThumbnail('playlist', $playlist);
				$playlist->author->avatar_path = FileHelper::getAvatar($playlist->author);
				$library->setRelation('playlist', $playlist);
			}
			FileHelper::getSongUrl($library->song);
			if (!is_null($library->artist)) {
				$library->artist->avatar_path = FileHelper::getAvatar($library->artist);
			}
		}
		try {
			return ApiResponse::success($libraries);
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
				'author_id' => $user->id,
				'thumbnail' => $user->avatar,
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
		$libraries = Library::where('user_id', Auth::user()->id)
			->with(
				[
					'artist' => function ($query) use ($params) {
						$query->where('name', 'like', $params['search-key'] . '%');
					},
					'playlist' => function ($query) use ($params) {
						$query->where('name', 'like', $params['search-key'] . '%')
							->with('author');
					},
					'song' => function ($query) use ($params) {
						$query->where('name', 'like', $params['search-key'] . '%');
					},
				]
			)
			->get();
		$result = [
			'artists' => [],
			'playlists' => [],
			'songs' => []
		];
		foreach ($libraries as $library) {
			if (!is_null($library->artist)) {
				$library->artist->avatar_path = FileHelper::getAvatar($library->artist);
				array_push($result['artists'], $library->artist);
			}
			if (!is_null($library->playlist)) {
				$library->playlist->thumbnail_path = FileHelper::getThumbnail('playlist', $library->playlist);
				$library->playlist->author->avatar_path = FileHelper::getAvatar($library->playlist->author);
				array_push($result['playlists'], $library->playlist);
			}
			if (!is_null($library->song)) {
				FileHelper::getSongUrl($library->song);
				array_push($result['songs'], $library->song);
			}
		}
		return ApiResponse::success($result);
	}
}
