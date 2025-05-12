<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
	use HasFactory;

	protected $table = 'playlists';

	protected $fillable = [
		'name',
		'description',
		'author_id',
		'thumbnail',
		'type',
		'total_song',
		'price',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
