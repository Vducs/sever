<?php

if (!function_exists('formatDateVN')) {
    function formatDateVN($date)
    {
        return \Carbon\Carbon::parse($date)->format('d/m/Y');
    }
}

if (!function_exists('formatDateTimeVN')) {
    function formatDateTimeVN($date)
    {
        return \Carbon\Carbon::parse($date)->format('d/m/Y H:i:s');
    }
}

//Xử lý chuỗi

if (!function_exists('str_limit')) {
    function str_limit($string, $limit = 100, $end = '...')
    {
        return \Illuminate\Support\Str::limit($string, $limit, $end);
    }
}

if (!function_exists('slugify')) {
    function slugify($string)
    {
        return \Illuminate\Support\Str::slug($string);
    }
}

//Hàm xử lý số, tiền tệ

if (!function_exists('formatCurrency')) {
    function formatCurrency($number, $symbol = '₫')
    {
        return number_format($number, 0, ',', '.') . ' ' . $symbol;
    }
}

// Hàm hỗ trợ URL và asset
if (!function_exists('activeMenu')) {
    function activeMenu($routeName)
    {
        return request()->routeIs($routeName) ? 'active' : '';
    }
}

//Hàm hỗ trợ JSON / API response chuẩn
if (!function_exists('apiResponse')) {
    function apiResponse($data = null, $message = '', $status = true, $code = 200)
    {
        return response()->json([
            'status' => $status ? 'success' : 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}

//Random
if (!function_exists('random')) {
    /**
     * Tạo chuỗi ngẫu nhiên gồm chữ và số, độ dài $length
     *
     * @param int $length
     * @return string
     */
    function random(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
}
