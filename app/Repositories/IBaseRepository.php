<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IBaseRepository
{
    public function all();
    public function find($id);
    public function findBy(string $field, $value);
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Model;
    public function update($id, array $data): bool;
    public function delete($id): bool;
}