<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'role'];

    public function movies()
    {
        return $this->hasMany(MovieModel::class, 'created_by', 'id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}