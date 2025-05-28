<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Xác định route redirect khi không đăng nhập.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login'); // Hoặc url bạn muốn redirect
        }
    }
}
