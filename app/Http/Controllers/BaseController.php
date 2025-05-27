<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    /**
     * Trả JSON thành công
     */
    public function success($data = [], string $message = 'Thành công', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Trả JSON thất bại
     */
    public function error(string $message = 'Có lỗi xảy ra', int $code = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $data
        ], $code);
    }

    /**
     * Trả về kết quả phân trang theo chuẩn JSON
     */
    public function paginated(LengthAwarePaginator $paginator, string $message = 'Thành công'): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage()
            ]
        ]);
    }

    /**
     * Ghi log lỗi (nội bộ) và trả thông báo chung
     */
    public function exception(\Throwable $e, string $customMessage = 'Đã xảy ra lỗi không mong muốn'): JsonResponse
    {
        Log::error($e);

        return $this->error($customMessage, 500);
    }
}
