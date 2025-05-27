<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\MovieModel;

class MovieController extends BaseController
{
    /**
     * Lấy danh sách phim (có phân trang)
     * GET /api/v1/movies
     */
    public function index()
    {
        try {
            $movies = MovieModel::with(['category', 'genres', 'slug'])
                ->published()
                ->active()
                ->latest()
                ->paginate(12);

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
            $movie = MovieModel::with(['category', 'genres', 'episodes', 'slug'])
                ->published()
                ->active()
                ->whereHas('slug', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                })
                ->first();

            if (!$movie) {
                return $this->error('Phim không tồn tại', 404);
            }

            return $this->success($movie);
        } catch (\Throwable $e) {
            return $this->exception($e);
        }
    }
}
