<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Request;

class JwtMiddleware
{
    /**
     * Xử lý middleware để kiểm tra token JWT hợp lệ.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Cố gắng lấy user từ token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại.',
                    'data' => null,
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            // Token hết hạn
            return response()->json([
                'status' => 'error',
                'message' => 'Token đã hết hạn, vui lòng đăng nhập lại.',
                'data' => null,
            ], 401);
        } catch (TokenInvalidException $e) {
            // Token không hợp lệ
            return response()->json([
                'status' => 'error',
                'message' => 'Token không hợp lệ.',
                'data' => null,
            ], 401);
        } catch (JWTException $e) {
            // Lỗi token khác (ví dụ token không tồn tại)
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn chưa đăng nhập hoặc token không tồn tại.',
                'data' => null,
            ], 401);
        }

        // Nếu token hợp lệ, cho phép tiếp tục
        return $next($request);
    }
}
