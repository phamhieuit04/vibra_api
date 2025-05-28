<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Library;
use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SongController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request, $id)
	{
		try {
			Library::insert([
				'user_id' => Auth::user()->id,
				'song_id' => $id,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
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
		$song = Song::where('id', $id)
			->with('author', 'playlist', 'category')
			->first();
		$song->author->followers = count($song->author->libraries);
		FileHelper::getSongUrl($song);
		return ApiResponse::success($song);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function addSongToPlaylist(Request $request)
	{
		$params = $request->all();
		if (isset($params['song_id']) && isset($params['playlist_id'])) {
			Library::create([
				'user_id' => Auth::user()->id,
				'playlist_id' => $params['playlist_id'],
				'song_id' => $params['song_id'],
			]);
			return ApiResponse::success();
		} else {
			return ApiResponse::internalServerError();
		}
	}

	public function updateTotalPlayed(Request $request, $id)
	{
		try {
			$song = Song::find($id);
			$song->total_played = $song->total_played + 1;
			$song->save();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Request $request)
	{
		$params = $request->all();
		try {
			DB::beginTransaction();
			DB::table('libraries')
				->where('user_id', Auth::user()->id)
				->where('playlist_id', $params['playlist_id'])
				->where('song_id', $params['song_id'])
				->delete();
			DB::commit();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			return ApiResponse::internalServerError();
		}
	}
}
