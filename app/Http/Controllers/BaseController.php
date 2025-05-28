<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseController extends Controller
{
    /**
     * Trả về phản hồi thành công
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, $message = 'Thành công', $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Trả về phản hồi phân trang
     *
     * @param LengthAwarePaginator $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paginated(LengthAwarePaginator $data, $message = 'Thành công', $code = 200)
    {
        return response()->json([
            'status'     => 'success',
            'message'    => $message,
            'data'       => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ],
        ], $code);
    }

    /**
     * Trả về phản hồi lỗi chung
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($message = 'Có lỗi xảy ra', $code = 400)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'data'    => null,
        ], $code);
    }

    /**
     * Trả về lỗi ngoại lệ hệ thống
     *
     * @param \Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function exception(\Throwable $e)
    {
        return response()->json([
            'status'  => 'error',
            'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            'data'    => null,
        ], 500);
    }

    /**
     * Trả về lỗi xác thực dữ liệu (validation)
     *
     * @param array $errors
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationError($errors = [], $message = 'Dữ liệu không hợp lệ', $code = 422)
    {
        return response()->json([
            'status'  => 'fail',
            'message' => $message,
            'errors'  => $errors,
            'data'    => null,
        ], $code);
    }

    /**
     * Trả về lỗi không xác thực (Unauthorized)
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorized($message = 'Không có quyền truy cập', $code = 401)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'data'    => null,
        ], $code);
    }

    /**
     * Trả về lỗi không tìm thấy tài nguyên
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFound($message = 'Không tìm thấy dữ liệu', $code = 404)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'data'    => null,
        ], $code);
    }
}
