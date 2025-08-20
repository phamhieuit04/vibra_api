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
        'description',
        'author_id',
        'playlist_id',
        'category_id',
        'total_played',
        'thumbnail',
        'lyrics',
        'status',
        'price',
        'created_at',
        'updated_at'
    ];

    protected $cast = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class, 'playlist_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function blocked()
    {
        return $this->hasOne(Song::class, 'song_id', 'id');
    }
}
