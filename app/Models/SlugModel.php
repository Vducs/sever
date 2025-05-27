<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class SlugModel extends BaseModel
{
    protected $table = 'slugs';

    protected $fillable = ['slug', 'sluggable_id', 'sluggable_type'];

    public function sluggable(): MorphTo
    {
        return $this->morphTo();
    }
}