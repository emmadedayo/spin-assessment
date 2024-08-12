<?php
namespace App\Http\Interfaces;

use App\Models\Task;

interface RepositoryInterface
{
    public function all(array $filters = [], array $columns = ['*'], array $with = [], $perPage = 15, $limit = null);

    public function find($id, array $columns = ['*'], array $with = []);

    public function findWhere($column, $value, array $columns = ['*'], array $with = []);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function check($column, $value);
}
