<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
	use HasFactory;

	protected $table = 'bills';

	protected $fillable = [
		'user_id',
		'playlist_id',
		'status',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
