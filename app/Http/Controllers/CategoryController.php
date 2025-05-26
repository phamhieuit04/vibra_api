<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Models\Category;
use App\Models\Song;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
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
			$songs = Song::where('category_id', $id)->get();
			FileHelper::getSongsUrl($songs);
			return ApiResponse::success($songs);
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
