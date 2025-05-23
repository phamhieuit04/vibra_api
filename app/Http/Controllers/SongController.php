<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Library;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
		FileHelper::getSongUrl($song);
		return ApiResponse::success($song);
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
