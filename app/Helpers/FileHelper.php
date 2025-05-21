<?php

namespace App\Helpers;

use App\Models\Song;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FileHelper
{
	private static function getNameFromEmail($user = null)
	{
		if (!is_null($user)) {
			return explode('@', $user->email)[0];
		} else {
			return explode('@', Auth::user()->email)[0];
		}
	}

	public static function getFileName($file)
	{
		$lastDot = strrpos($file->getClientOriginalName(), '.');
		$name = substr($file->getClientOriginalName(), 0, $lastDot);
		return $name;
	}

	public static function store($file, string $location)
	{
		if (!is_null($file)) {
			if ($location === 'songs' || $location === 'lyrics' || $location === 'thumbnails' || $location === 'avatars') {
				$fullPath = 'uploads/' . self::getNameFromEmail() . '/' . $location;
				$file->storeAs($fullPath, $file->getClientOriginalName(), ['disk' => 'public-api']);
				return true;
			}
		}
		return false;
	}

	public static function getUrl(string $location, Song $song)
	{
		if (!is_null($song) && ($location === 'songs' || $location === 'lyrics' || $location === 'thumbnails')) {
			$path = env('APP_URL') . '/uploads/' . self::getNameFromEmail() . '/' . $location . '/';
			switch ($location) {
				case 'songs':
					return $path . $song->name . '.mp3';
				case 'lyrics':
					return $path . $song->lyrics;
				case 'thumbnails':
					return $path . $song->thumbnail;
			}
		} else {
			return null;
		}
	}

	public static function getAvatar(User $user = null)
	{
		if (!is_null($user)) {
			return env('APP_URL') . '/uploads/' . self::getNameFromEmail($user) . '/avatars/' . $user->avatar;
		} else {
			return env('APP_URL') . '/uploads/' . self::getNameFromEmail() . '/avatars/' . Auth::user()->avatar;
		}
	}

	// TODO: refactor all get url functions, fix missing thumbnail url for playlist and category
}