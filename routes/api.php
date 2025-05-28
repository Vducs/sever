<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MovieController;

Route::prefix('v1')->group(function () {
    Route::get('/movies', [MovieController::class, 'index']);         // Lấy danh sách phim
    Route::get('/movies/{slug}', [MovieController::class, 'show']);   // Lấy chi tiết phim theo slug
    Route::post('/movies', [MovieController::class, 'store']);        // Tạo phim mới
    Route::put('/movies/{id}', [MovieController::class, 'update']);   // Cập nhật phim (MỚI THÊM)
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // Xóa phim
});
