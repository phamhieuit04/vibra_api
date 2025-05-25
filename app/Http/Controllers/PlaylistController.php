<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;

class PlaylistController extends Controller
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
	public function store($id)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Request $request, $id)
	{
		try {
			$songs = Song::where('playlist_id', $id)->get();
			FileHelper::getSongsUrl($songs);
			return ApiResponse::success($songs);
		} catch (\Throwable $th) {
			return ApiResponse::dataNotfound();
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request)
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
