<?php

namespace App\Services;

use App\Models\SlugModel;
use App\Repositories\Movie\IMovieRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function createMovie(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Chuẩn bị dữ liệu cho phim
            $movieData = [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'image' => $data['image'] ?? null,
                'views' => 0,
                'rating' => $data['rating'] ?? 0.0,
                'published_at' => $data['published_at'] ?? now(),
                'created_by' => Auth::id() ?? null,
            ];

            // Tạo phim
            $movie = $this->repository->create($movieData);

            // Tạo slug từ title
            $slugValue = Str::slug(strtolower($data['title']));
            $originalSlug = $slugValue;
            $count = 1;

            // Xử lý trùng lặp slug
            while (SlugModel::where('slug', $slugValue)->exists()) {
                $slugValue = $originalSlug . '-' . $count++;
            }

            // Tạo bản ghi slug
            $movie->slug()->create([
                'slug' => $slugValue,
                'status' => 1,
            ]);

            // Gắn genres nếu có
            if (!empty($data['genres'])) {
                $movie->genres()->sync($data['genres']);
            }

            return $this->formatData($movie);
        });
    }
}