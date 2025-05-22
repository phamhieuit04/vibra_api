<?php

namespace App\Helpers;

use App\Models\Song;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FileHelper
{
	/**
	 * Remove '@' and everything behind it, return clean email name
	 * @param mixed $user
	 * @return string
	 */
	private static function getNameFromEmail($user = null)
	{
		if (!is_null($user)) {
			return explode('@', $user->email)[0];
		} else {
			return explode('@', Auth::user()->email)[0];
		}
	}

	/**
	 * Get clean file name without it's extension
	 * @param mixed $file
	 * @return string
	 */
	public static function getFileName($file)
	{
		$lastDot = strrpos($file->getClientOriginalName(), '.');
		$name = substr($file->getClientOriginalName(), 0, $lastDot);
		return $name;
	}

	/**
	 * Store file in public folder
	 * @param mixed $file
	 * @param string $location
	 * @return bool
	 */
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

	// TODO: refactor all get url functions, fix missing thumbnail url for playlist and category
	/**
	 * Get assets url, currently can get url from songs, lyrics, song's thumbnail
	 * @param string $location
	 * @param mixed $data
	 * @return string|null
	 */
	public static function getUrl(string $location, $data)
	{
		if (!is_null($data) && ($location === 'songs' || $location === 'lyrics' || $location === 'thumbnails')) {
			$path = asset("uploads/" . self::getNameFromEmail() . "/$location/");
			switch ($location) {
				case 'songs':
					return $path . '/' . $data->name . '.mp3';
				case 'lyrics':
					return $path . $data->lyrics;
				case 'thumbnails':
					return $path . $data->thumbnail;
			}
		} else {
			return null;
		}
	}

	/**
	 * Get user avatar url
	 * @param \App\Models\User|null $user
	 * @return string
	 */
	public static function getAvatar(User $user = null)
	{
		if (!is_null($user)) {
			return asset('uploads/' . self::getNameFromEmail($user) . '/avatars/' . $user->avatar);
		} else {
			return asset('uploads/' . self::getNameFromEmail() . '/avatars/' . Auth::user()->avatar);
		}
	}
}