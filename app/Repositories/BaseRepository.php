<?php

namespace App\Repositories;

use App\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements IBaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findBy(string $field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        $record = $this->find($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    public function search(string $keyword, array $fields, int $perPage = 15)
    {
        if (empty($fields)) {
            throw new \InvalidArgumentException('Phải cung cấp ít nhất một trường để tìm kiếm');
        }

        $query = $this->model->newQuery();

        $query->where(function ($q) use ($fields, $keyword) {
            foreach ($fields as $index => $field) {
                if ($index === 0) {
                    $q->where($field, 'LIKE', "%{$keyword}%");
                } else {
                    $q->orWhere($field, 'LIKE', "%{$keyword}%");
                }
            }
        });

        return $query->paginate($perPage);
    }
}
