<?php

namespace App\Repositories\Movie;

use App\Repositories\IBaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

interface IMovieRepository extends IBaseRepository
{
    public function getAllWithPagination(int $perPage = 12): LengthAwarePaginator;
    public function findBySlug(string $slug);
}