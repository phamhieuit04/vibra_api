<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'gender',
		'birth',
		'gender',
		'email_verified_at',
		'avatar',
		'password',
		'description'
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'birth' => 'date',
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	public function libraries()
	{
		return $this->hasMany(Library::class, 'artist_id', 'id');
	}

	public function playlists()
	{
		return $this->hasMany(Playlist::class, 'author_id', 'id');
	}

	public function blocked()
	{
		return $this->hasMany(Blocked::class, 'artist_id', 'id');
	}
}
