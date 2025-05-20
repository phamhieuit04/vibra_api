<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class FileHelper
{
	public static function getName($file)
	{
		$lastDot = strrpos($file->getClientOriginalName(), '.');
		$name = substr($file->getClientOriginalName(), 0, $lastDot);
		return $name;
	}

	public static function storeSong($file)
	{
		if (!is_null($file)) {
			$cleanEmail = explode('@', Auth::user()->email)[0];
			$fullPath = 'uploads/' . $cleanEmail . '/songs';
			$file->storeAs($fullPath, $file->getClientOriginalName(), ['disk' => 'public-api']);
		}
	}

	public static function storeLyric($file)
	{
		if (!is_null($file)) {
			$cleanEmail = explode('@', Auth::user()->email)[0];
			$fullPath = 'uploads/' . $cleanEmail . '/lyrics';
			$file->storeAs($fullPath, $file->getClientOriginalName(), ['disk' => 'public-api']);
		}
	}

	public static function songPath($song)
	{
		if (!is_null($song)) {
			$cleanEmail = explode('@', Auth::user()->email)[0];
			$fullPath = env('APP_URL') . '/uploads/' . $cleanEmail . '/songs/' . $song->name . '.mp3';
			return $fullPath;
		}
	}

	public static function lyricPath($song)
	{
		if (!is_null($song)) {
			$cleanEmail = explode('@', Auth::user()->email)[0];
			$fullPath = env('APP_URL') . '/uploads/' . $cleanEmail . '/lyrics' . $song->lyrics;
			return $fullPath;
		}
	}
}