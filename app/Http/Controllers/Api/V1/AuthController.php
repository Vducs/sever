<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\AuthService;
use App\Http\Resources\UserResource;
use App\Http\Controllers\BaseController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Đăng ký người dùng mới
     */
    public function register(Request $request)
    {
        try {
            $result = $this->authService->register($request->all());

            return $this->success([
                'user'  => $result['user'],
                'token' => $result['token'],
            ], 'Đăng ký thành công');
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }

    /**
     * Đăng nhập và lấy token
     */
    public function login(Request $request)
    {
        try {
            $data = $this->authService->login($request->only(['email', 'password']));
            return $this->success($data, 'Đăng nhập thành công');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 401);
        }
    }

    /**
     * Lấy thông tin profile của user hiện tại
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->unauthorized('Chưa đăng nhập');
        }

        return $this->success($user, 'Thông tin người dùng');
    }

    /**
     * Đăng xuất (thu hồi token)
     */
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return $this->error('Token không tồn tại hoặc hết hạn. Vui lòng đăng nhập lại.', 401);
            }

            // Xác thực token trước khi hủy
            JWTAuth::parseToken()->authenticate();
            JWTAuth::invalidate($token);

            return $this->success(null, 'Đăng xuất thành công');
        } catch (TokenExpiredException $e) {
            return $this->error('Token đã hết hạn', 401);
        } catch (TokenInvalidException $e) {
            return $this->error('Token không hợp lệ', 401);
        } catch (JWTException $e) {
            return $this->error('Lỗi khi đăng xuất: ' . $e->getMessage(), 500);
        }
    }
}
