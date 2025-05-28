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
            // Tạo slug từ title
            $slugValue = Str::slug(strtolower($data['title']));

            // Kiểm tra nếu slug đã tồn tại thì báo lỗi và dừng tạo
            if (SlugModel::where('slug', $slugValue)->exists()) {
                throw new \Exception('Phim đã tồn tại. Vui lòng kiểm tra lại.');
            }

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


    public function updateMovie(int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $movie = $this->repository->find($id);

            if (!$movie) {
                throw new \Exception('Không tìm thấy phim');
            }

            $oldTitle = $movie->title;

            // Cập nhật dữ liệu phim (trừ title sẽ cập nhật riêng nếu cần)
            $movieData = [
                'title' => $data['title'],
                'description' => $data['description'] ?? $movie->description,
                'category_id' => $data['category_id'] ?? $movie->category_id,
                'image' => $data['image'] ?? $movie->image,
                'rating' => $data['rating'] ?? $movie->rating,
                'published_at' => $data['published_at'] ?? $movie->published_at,
            ];

            $this->repository->update($id, $movieData);

            $movie->refresh();

            // Nếu tiêu đề thay đổi, xử lý cập nhật slug
            if (strtolower($data['title']) !== strtolower($oldTitle)) {
                $slugValue = Str::slug(strtolower($data['title']));

                // Kiểm tra slug có tồn tại và không phải của phim hiện tại
                $slugExists = SlugModel::where('slug', $slugValue)
                    ->where('sluggable_id', '!=', $movie->id)
                    ->exists();

                if ($slugExists) {
                    throw new \Exception('Phim với tiêu đề này đã tồn tại.');
                }

                // Cập nhật hoặc tạo slug mới
                if ($movie->slug) {
                    $movie->slug->update(['slug' => $slugValue]);
                } else {
                    $movie->slug()->create([
                        'slug' => $slugValue,
                        'status' => 1,
                    ]);
                }
            }

            // Cập nhật thể loại phim nếu có
            if (!empty($data['genres'])) {
                $movie->genres()->sync($data['genres']);
            }

            return $this->formatData($movie->fresh(['category', 'genres', 'slug']));
        });
    }


    public function deleteMovie(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $movie = $this->repository->find($id);

            if (!$movie) {
                throw new \Exception('Không tìm thấy phim');
            }

            // Xóa slug liên quan (nếu có)
            $movie->slug()->delete();

            // Gỡ liên kết genres
            $movie->genres()->detach();

            // Xóa phim
            return $this->repository->delete($id);
        });
    }

    public function searchMovies(string $keyword)
    {
        return $this->repository->searchByKeyword($keyword);
    }
}
