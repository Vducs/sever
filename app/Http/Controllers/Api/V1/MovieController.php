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
}