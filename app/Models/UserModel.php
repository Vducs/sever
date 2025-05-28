<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DateTimeInterface;

class UserModel extends Authenticatable implements JWTSubject
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password'];

    // Format dates to dd/mm/yy h/m/s
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/y H:i:s');
    }

    // JWTSubject methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}