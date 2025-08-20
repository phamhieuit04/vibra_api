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

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function playlist()
    {
        return $this->hasOne(Playlist::class, 'id', 'playlist_id');
    }

    public function artist()
    {
        return $this->hasOne(User::class, 'id', 'artist_id');
    }

    public function song()
    {
        return $this->hasOne(Song::class, 'id', 'song_id');
    }
}
