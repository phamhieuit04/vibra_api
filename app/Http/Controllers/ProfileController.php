<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
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
	public function index()
	{
		$albums = Playlist::where('author_id', Auth::user()->id)->get();
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
				'name' => isset($params['name']) ? $params['name'] : $user->name,
				'gender' => isset($params['gender']) ? $params['gender'] : $user->gender,
				'birth' => isset($params['birth']) ? $params['birth'] : $user->birth,
				'avatar' => isset($params['avatar']) ? $params['avatar'] : $user->avatar,
				'updated_at' => Carbon::now()
			]);
			$user->save();
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
				'name' => 'Playlist của tôi #' . count($user->playlists) + 1,
				'author_id' => $user->id,
				'thumbnail' => $user->avatar,
				'type' => 1,
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

	public function updateAlbum(Request $request, $id)
	{
		$params = $request->all();
		$playlist = Playlist::find($id);
		$playlist->update([
			'name' => isset($params['name']) ? $params['name'] : $playlist->name,
			'description' => isset($params['description']) ? $params['description'] : $playlist->description,
			'thumbnail' => isset($params['thumbnail']) ? $params['thumbnail'] : $playlist->thumbnail,
			'price' => isset($params['price']) ? $params['price'] : $playlist->price,
			'updated_at' => Carbon::now()
		]);
		$playlist->save();
		return ApiResponse::success($playlist);
	}

	public function uploadSong(Request $request)
	{
		$params = $request->all();
		if ($request->hasFile('file') && $request->hasFile('lyric')) {
			$file = $request->file('file');
			$lyric = $request->file('lyric');
			FileHelper::storeSong($file);
			FileHelper::storeLyric($lyric);
			$song = Song::create([
				'name' => FileHelper::getName($file),
				'author_id' => Auth::user()->id,
				'category_id' => $params['category-id'],
				'lyrics' => '/' . $lyric->getClientOriginalName(),
				'thumbnail' => '/default.png',
				'total_played' => 0,
				'status' => 1,
				'price' => 0,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
			$song->songPath = FileHelper::songPath($song);
			$song->lyricPath = FileHelper::lyricPath($song);
			return ApiResponse::success($song);
		} else {
			return ApiResponse::dataNotfound();
		}
	}
}
