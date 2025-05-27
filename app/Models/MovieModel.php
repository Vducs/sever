<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class MovieModel extends BaseModel
{
    protected $table = 'movies'; // Chỉ định rõ tên bảng

    protected $fillable = ['title', 'description', 'category_id', 'image', 'views', 'rating', 'published_at', 'created_by'];

    protected $casts = [
        'published_at' => 'datetime',
        'rating' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }

    public function genres()
    {
        return $this->belongsToMany(GenreModel::class, 'genre_movie', 'movie_id', 'genre_id');
    }

    public function episodes()
    {
        return $this->hasMany(EpisodeModel::class, 'movie_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(UserModel::class, 'created_by', 'id');
    }

    public function slug()
    {
        return $this->morphOne(SlugModel::class, 'sluggable');
    }

    public function scopePopular(Builder $query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeTopRated(Builder $query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $slugValue = Str::slug(strtolower($model->title));
            $existingSlug = $model->slug;

            if ($existingSlug) {
                $existingSlug->update(['slug' => $slugValue]);
            } else {
                $model->slug()->create(['slug' => $slugValue]);
            }
        });
    }
}