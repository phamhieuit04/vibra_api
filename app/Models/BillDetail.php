<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
	use HasFactory;

	protected $table = 'bill_details';

	protected $fillable = [
		'bill_id',
		'song_id',
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
