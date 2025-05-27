<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;


class EpisodeModel extends BaseModel
{
    protected $table = 'episodes';

    protected $fillable = ['title', 'movie_id', 'episode_number', 'url', 'duration', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
        'duration' => 'integer',
    ];

    public function movie()
    {
        return $this->belongsTo(MovieModel::class, 'movie_id', 'id');
    }

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('episode_number', 'asc');
    }
}