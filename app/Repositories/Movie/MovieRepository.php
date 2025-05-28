<?php

namespace App\Repositories\Movie;

use App\Models\MovieModel;
use App\Repositories\BaseRepository;
use App\Repositories\Movie\IMovieRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieRepository extends BaseRepository implements IMovieRepository
{
    public function __construct(MovieModel $model)
    {
        parent::__construct($model);
    }

    public function getAllWithPagination(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'genres', 'slug'])
            ->published()
            ->active()
            ->latest()
            ->paginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        return $this->model
            ->with(['category', 'genres', 'episodes', 'slug'])
            ->published()
            ->active()
            ->whereHas('slug', function (Builder $query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->first();
    }

    public function create(array $data): MovieModel
    {
        return $this->model->create($data);
    }
}