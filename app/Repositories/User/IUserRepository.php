<?php

namespace App\Repositories\User;

use App\Repositories\IBaseRepository;

interface IUserRepository extends IBaseRepository
{
    public function findByEmail(string $email);
    public function findByRole(string $role);
    // Các phương thức đặc thù khác nếu có
}
