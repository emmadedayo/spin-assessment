<?php

namespace App\Http\Repositories;

use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class TaskRepository
{
    /**
     * @throws Exception
     */
    public function all(array $filters = [], array $columns = null, array $with = [], $perPage = 15, $limit = null)
    {
        $query = Task::query();

        // Apply filters based on $filters array
        foreach ($filters as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value); // Handle array filters (e.g., multiple statuses)
            } else {
                $query->where($column, $value);
            }
        }

        // If $columns is null or empty, select all columns
        if (empty($columns)) {
            $query->select(['*']);
        } else {
            $query->select($columns);
        }

        // Eager load related models if $with is provided
        if (!empty($with)) {
            $query->with($with);
        }

        try {
            if ($limit) {
                $query->limit($limit);

                return $query->get();
            } else {
                return $query->paginate($perPage);
            }
        } catch (ModelNotFoundException $e) {
            // Handle the case where no tasks are found based on filters
            throw new Exception('No tasks found', 0, $e);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0, $e);
        }
    }



    public function find($id, array $columns = null, array $with = [])
    {
        $query = Task::query();
        if (empty($columns)) {
            $query->select(['*']);
        } else {
            $query->select($columns);
        }

        if ($with) {
            $query->with($with); // Eager load related models
        }

        if (is_array($id)) {
            return $query->whereIn('id', $id)->get();
        } else {
            // Check if ID is numeric before using find
            if (($id)) {
                return $query->find($id);
            } else {
                // Throw a more informative exception with context
                throw new InvalidArgumentException('Task ID must be a positive numeric value. Received: ' . $id);
            }
        }
    }

    public function findWhere($column, $value, array $columns = ['*'], array $with = []): Model|Builder|null
    {
        $query = Task::query();
        $query->select($columns);

        if ($with) {
            $query->with($with); // Eager load related models
        }

        try {
            return $query->where($column, $value)->first();
        } catch (ModelNotFoundException $e) {
            // Handle the case where no user is found with the specified criteria
            throw new ModelNotFoundException('Task not found with');
        }
    }

    public function findWhereWithArray(array $conditions, array $columns = ['*'], array $with = []): Model|Builder|null
    {
        $query = Task::query();
        $query->select($columns);

        if ($with) {
            $query->with($with); // Eager load related models
        }

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        try {
            return $query->first();
        } catch (ModelNotFoundException $e) {
            // Handle the case where no article is found with the specified criteria
            throw new ModelNotFoundException('Task not found with the given criteria');
        }
    }


    /**
     * @throws Exception
     */
    public function create(array $data)
    {
        try {
            return Task::create($data);
        } catch (Exception $e) {
            // Handle potential validation or data saving errors (consider logging)
            throw new Exception('An error occurred while creating a new task.', 0, $e);
        }
    }

    /**
     * @throws Exception
     */
    public function update(array $data, string $id)
    {
        $user = Task::find($id);
        if (!$user) {
            // Handle the case where the user to update is not found
            throw new Exception('Task resource not found, please contact the administrator', 404);
        }
        $user->forceFill($data);
        $user->save();

        return $user;
    }

    /**
     * @throws Exception
     */
    public function delete($id): string
    {
        try {
            return Task::destroy($id);
        } catch (Exception $e) {
            // Handle potential deletion errors (consider logging)
            throw new Exception('An error occurred while deleting the task.', 0, $e);
        }
    }

    public function check($column, $value)
    {
        return Task::where($column, $value)->exists();
    }

    public function getMe()
    {
        return $this->find(auth()->id(), ['*']);
    }
}
