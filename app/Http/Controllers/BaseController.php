<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseController extends Controller
{
    /**
     * Trả về phản hồi thành công
     */
    protected function success($data, $message = 'Thành công', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Trả về phản hồi phân trang
     */
    protected function paginated(LengthAwarePaginator $data, $message = 'Thành công', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]
        ], $code);
    }

    /**
     * Trả về phản hồi lỗi
     */
    protected function error($message = 'Có lỗi xảy ra', $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null
        ], $code);
    }

    /**
     * Xử lý ngoại lệ
     */
    protected function exception(\Throwable $e)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            'data' => null
        ], 500);
    }
}