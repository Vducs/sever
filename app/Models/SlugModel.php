<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlugModel extends Model
{
    protected $table = 'slugs';

    protected $fillable = ['slug', 'status', 'sluggable_id', 'sluggable_type'];

    public function sluggable()
    {
        return $this->morphTo();
    }
}