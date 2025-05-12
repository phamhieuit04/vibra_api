<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
	use HasFactory;

	protected $table = 'songs';

	protected $fillable = [
		'name',
		'author_id',
		'playlist_id',
		'total_played',
		'thumbnail',
		'lyrics',
		'status',
		'price',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
