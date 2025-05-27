<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;


class GenreModel extends BaseModel
{
    protected $table = 'genres';

    protected $fillable = ['name', 'description', 'image', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function movies()
    {
        return $this->belongsToMany(MovieModel::class, 'genre_movie', 'genre_id', 'movie_id');
    }

    public function scopeWithMostMovies(Builder $query)
    {
        return $query->withCount('movies')->orderBy('movies_count', 'desc');
    }
}