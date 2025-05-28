<?php

namespace App\Repositories\User;
use App\Repositories\BaseRepository;

use App\Models\UserModel;

class UserRepository extends BaseRepository implements IUserRepository
{
    protected $model;

    public function __construct(UserModel $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByRole(string $role)
    {
        return $this->model->where('role', $role)->get();
    }

    // Các hàm CRUD đã có sẵn từ BaseRepository
}
