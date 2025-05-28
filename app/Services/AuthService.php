<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService extends BaseService
{
    protected $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(array $data)
    {
        $this->validate($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepo->create($data);

        // Tự động đăng nhập sau khi đăng ký và tạo token
        $token = JWTAuth::fromUser($user);

        return [
            'user'  => $user->makeHidden(['password']), // ẩn password nếu có
            'token' => $token,
        ];
    }


    public function login(array $credentials)
    {
        $this->validate($credentials, [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Email hoặc mật khẩu không đúng');
        }

        $user = JWTAuth::user();

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
