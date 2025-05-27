<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryModel extends BaseModel
{
    protected $table = 'categories';

    protected $fillable = ['name', 'description', 'image', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function movies()
    {
        return $this->hasMany(MovieModel::class, 'category_id', 'id');
    }

    public function scopeWithMostMovies(Builder $query)
    {
        return $query->withCount('movies')->orderBy('movies_count', 'desc');
    }
}