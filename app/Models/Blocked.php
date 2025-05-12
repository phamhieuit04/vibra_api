<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blocked extends Model
{
	use HasFactory;

	protected $table = 'blocked';

	protected $fillable = [
		'user_id',
		'song_id',
		'artist_id',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
