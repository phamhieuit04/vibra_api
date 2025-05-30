<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $playlist_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUserId($value)
 */
	class Bill extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $bill_id
 * @property int $song_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillDetail whereUpdatedAt($value)
 */
	class BillDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $song_id
 * @property int|null $artist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Song|null $song
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked whereUserId($value)
 */
	class Blocked extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $thumbnail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceToken whereUserId($value)
 */
	class DeviceToken extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $playlist_id
 * @property int|null $artist_id
 * @property int|null $song_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $artist
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Playlist|null $playlist
 * @property-read \App\Models\Song|null $song
 * @method static \Illuminate\Database\Eloquent\Builder|Library newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library query()
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUserId($value)
 */
	class Library extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $author_id
 * @property string $thumbnail
 * @property int $type
 * @property int $total_song
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereTotalSong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUpdatedAt($value)
 */
	class Playlist extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $author_id
 * @property int|null $playlist_id
 * @property int $category_id
 * @property string $lyrics
 * @property string $thumbnail
 * @property int $total_played
 * @property int $status
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read Song|null $blocked
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Playlist|null $playlist
 * @method static \Illuminate\Database\Eloquent\Builder|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereLyrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereTotalPlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereUpdatedAt($value)
 */
	class Song extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $email
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $birth
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string $avatar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Blocked> $blocked
 * @property-read int|null $blocked_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeviceToken> $deviceTokens
 * @property-read int|null $device_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Library> $libraries
 * @property-read int|null $libraries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Library> $userLibraries
 * @property-read int|null $user_libraries_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

