<?php

namespace App\Repositories\Movie;

use App\Repositories\IBaseRepository;
use App\Models\MovieModel;
use Illuminate\Pagination\LengthAwarePaginator;

interface IMovieRepository extends IBaseRepository
{
    public function getAllWithPagination(int $perPage = 12): LengthAwarePaginator;
    public function findBySlug(string $slug);
    public function create(array $data): MovieModel;
}