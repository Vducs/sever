<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends BaseController
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Lấy danh sách phim (có phân trang)
     * GET /api/v1/movies
     */
    public function index()
    {
        try {
            $movies = $this->movieService->getAllMovies(12);
            return $this->paginated($movies);
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }

    /**
     * Lấy chi tiết một phim theo slug
     * GET /api/v1/movies/{slug}
     */
    public function show($slug)
    {
        try {
            $movie = $this->movieService->getMovieBySlug($slug);
            return $this->success($movie);
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }

    /**
     * Tạo phim mới
     * POST /api/v1/movies
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|string',
                'rating' => 'nullable|numeric|min:0|max:10',
                'published_at' => 'nullable|date',
                'genres' => 'nullable|array',
                'genres.*' => 'exists:genres,id',
            ]);

            $movie = $this->movieService->createMovie($validated);
            return $this->success($movie, 'Phim được tạo thành công', 201);
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }

    /**
     * Cập nhật phim
     * PUT /api/v1/movies/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|string',
                'rating' => 'nullable|numeric|min:0|max:10',
                'published_at' => 'nullable|date',
                'genres' => 'nullable|array',
                'genres.*' => 'exists:genres,id',
            ]);

            $movie = $this->movieService->updateMovie($id, $validated);
            return $this->success($movie, 'Phim được cập nhật thành công');
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }
    /**
     * Xóa phim
     * DELETE /api/v1/movies/{id}
     */
    public function destroy($id)
    {
        try {
            $this->movieService->deleteMovie($id);
            return $this->success(null, 'Phim đã được xóa thành công');
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }
}
