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
	public function listPlaylist()
	{
		try {
			$playlists = Playlist::where('author_id', Auth::user()->id)->get();
			return ApiResponse::success($playlists);
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
			DB::table('libraries')->where('playlist_id', $id)
				->update(['playlist_id' => null]);
			DB::table('songs')->where('playlist_id', $id)
				->update(['playlist_id' => null]);
			DB::table('playlists')->where('id', $id)->delete();
			DB::commit();
			return ApiResponse::success();
		} catch (\Throwable $th) {
			DB::rollBack();
			return ApiResponse::internalServerError();
		}
	}
}
