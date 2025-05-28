<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// Import các exception JWT
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    // Các thuộc tính và phương thức khác...

    public function render($request, Throwable $exception)
    {
        // Bắt lỗi token hết hạn
        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token đã hết hạn. Vui lòng đăng nhập lại.'
            ], 401);
        }

        // Bắt lỗi token không hợp lệ (ví dụ chữ ký sai)
        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token không hợp lệ.'
            ], 401);
        }

        // Bắt lỗi chung liên quan JWT (ví dụ không có token)
        if ($exception instanceof JWTException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xác thực token: ' . $exception->getMessage()
            ], 401);
        }

        // Bắt lỗi UnauthorizedHttpException (thường do JWT)
        if ($exception instanceof UnauthorizedHttpException) {
            // Có thể lấy exception gốc để kiểm tra nếu cần
            $previous = $exception->getPrevious();
            if ($previous instanceof TokenInvalidException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token không hợp lệ (UnauthorizedHttpException).'
                ], 401);
            }
            if ($previous instanceof TokenExpiredException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token đã hết hạn (UnauthorizedHttpException).'
                ], 401);
            }

            // Nếu không phải lỗi JWT cụ thể
            return response()->json([
                'status' => 'error',
                'message' => 'Không được phép truy cập.'
            ], 401);
        }

        // Trả về lỗi mặc định nếu không phải các lỗi trên
        return parent::render($request, $exception);
    }
}
