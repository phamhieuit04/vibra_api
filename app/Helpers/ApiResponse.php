<?php

namespace App\Helpers;

class ApiResponse
{
	public static function success($data = null)
	{
		return response()->json([
			'code' => 200,
			'data' => $data,
			'message' => 'Success.'
		]);
	}

	public static function dataNotfound()
	{
		return response()->json([
			'code' => 204,
			'data' => null,
			'message' => 'Data not found.'
		]);
	}

	public static function internalServerError()
	{
		return response()->json([
			'code' => 500,
			'data' => null,
			'message' => 'Internal server error.'
		]);
	}

	public static function unauthorized()
	{
		return response()->json([
			'code' => 401,
			'data' => null,
			'message' => 'Unauthorized.'
		]);
	}
}