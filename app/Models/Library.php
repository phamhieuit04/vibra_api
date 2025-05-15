<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
	use HasFactory;

	protected $table = 'libraries';

	protected $fillable = [
		'user_id',
		'playlist_id',
		'artist_id',
		'song_id',
		'created_at',
		'updated_at'
	];

	protected $cast = [
		'created_at' => 'timestamp',
		'updated_at' => 'timestamp'
	];
}
