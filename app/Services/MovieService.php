<?php

namespace App\Services;

use App\Repositories\Movie\IMovieRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieService extends BaseService
{
    protected $repository;

    public function __construct(IMovieRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllMovies(int $perPage = 12): LengthAwarePaginator
    {
        $movies = $this->repository->getAllWithPagination($perPage);
        return $this->formatData($movies);
    }

    public function getMovieBySlug(string $slug)
    {
        $movie = $this->repository->findBySlug($slug);

        if (!$movie) {
            throw new \Exception('Phim không tồn tại');
        }

        return $this->formatData($movie);
    }
}