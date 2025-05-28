<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MovieController;
use App\Http\Controllers\Api\V1\GenreController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->namespace('App\Http\Controllers\Api\V1')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('jwt.auth')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
    
    
    // Nhóm Movie
    Route::prefix('movies')->group(function () {
        Route::get('/', [MovieController::class, 'index']);                   // Lấy danh sách phim
        Route::get('/search', [MovieController::class, 'search']);            // Tìm kiếm phim
        Route::get('/hot', [MovieController::class, 'hot']);                  // Phim hot (nếu có)
        Route::get('/top-rated', [MovieController::class, 'topRated']);       // Phim đánh giá cao (nếu có)
        Route::get('/{slug}', [MovieController::class, 'show']);              // Chi tiết phim theo slug
        Route::post('/', [MovieController::class, 'store']);                  // Tạo phim mới
        Route::put('/{id}', [MovieController::class, 'update']);              // Cập nhật phim
        Route::delete('/{id}', [MovieController::class, 'destroy']);          // Xóa phim
        Route::post('/{id}/view', [MovieController::class, 'incrementView']); // Tăng lượt xem
        Route::post('/{id}/rate', [MovieController::class, 'rate']);          // Đánh giá phim
        Route::post('/{id}/upload-image', [MovieController::class, 'uploadImage']); // Upload ảnh phim
    });

    // Nhóm Genres
    Route::prefix('genres')->group(function () {
        Route::get('/', [GenreController::class, 'index']);                    // Lấy danh sách genres
        Route::post('/', [GenreController::class, 'store']);                   // Tạo mới genre
        Route::put('/{id}', [GenreController::class, 'update']);               // Cập nhật genre
        Route::delete('/{id}', [GenreController::class, 'destroy']);           // Xóa genre
    });

    // Nhóm Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);                 // Lấy danh sách categories
        Route::post('/', [CategoryController::class, 'store']);                // Tạo mới category
        Route::put('/{id}', [CategoryController::class, 'update']);            // Cập nhật category
        Route::delete('/{id}', [CategoryController::class, 'destroy']);        // Xóa category
    });
});
