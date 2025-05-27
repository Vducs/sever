<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    use SoftDeletes;

    // Khóa chính
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Tùy chỉnh tên cột timestamps
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Tùy chỉnh cột soft delete
    protected $dates = ['deleted_at'];

    // Thuộc tính mặc định
    protected $guarded = [];

    // Khởi tạo model
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['status'] = $this->attributes['status'] ?? 1;
    }

    // Scope để lấy bản ghi active
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }

    // Scope để lấy bản ghi inactive
    public function scopeInactive(Builder $query)
    {
        return $query->where('status', 0);
    }

    // Scope để lấy bản ghi đã phát hành
    public function scopePublished(Builder $query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // Scope để tìm kiếm
    public function scopeSearch(Builder $query, $keyword)
    {
        if (!$keyword) {
            return $query;
        }
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }

    // Kiểm tra bản ghi có active hay không
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    // Kiểm tra bản ghi đã phát hành chưa
    public function isPublished(): bool
    {
        return !is_null($this->published_at) && $this->published_at <= now();
    }

    // Lấy tên bảng
    public static function getTableName(): string
    {
        return (new static)->getTable();
    }

    // Accessor cho created_at
    public function getCreatedAtAttribute($value)
    {
        return $value ? date('d/m/Y H:i:s', strtotime($value)) : null;
    }

    // Accessor cho image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // Mutator cho title
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtoupper($value);
    }

    // Kiểm tra bản ghi tồn tại và chưa bị xóa
    public function existsAndNotDeleted(): bool
    {
        return $this->exists && !$this->trashed();
    }
}