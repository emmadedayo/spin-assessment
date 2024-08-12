<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Repositories\TaskRepository;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\TaskQueryParamRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    //
    private ApiResponse $apiResponse;
    private TaskRepository $taskRepository;

    public function __construct(ApiResponse $apiResponse, TaskRepository $taskRepository)
    {
        $this->apiResponse = $apiResponse;
        $this->taskRepository = $taskRepository;
    }

    //create task
    public function createTask(CreateTaskRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        //add auth user id to task
        $data['user_id'] = auth()->user()->id;
        $this->taskRepository->create($data);
        return $this->apiResponse->success('Task created successfully');
    }

    //get all tasks
    public function getTasks(TaskQueryParamRequest $request): \Illuminate\Http\JsonResponse
    {
        $filters = [];
        $perPage = $request->get('per_page', 15);
        $limit = $request->get('limit');
        if ($request->has('name')) {
            $filters['name'] = $request->name;
        }
        $tasks = $this->taskRepository->all($filters, null, ['user'], $perPage, $limit);
        return $this->apiResponse->success('Tasks retrieved successfully', $tasks);
    }

    //get task by id
    public function getTask($id): \Illuminate\Http\JsonResponse
    {
        $task = $this->taskRepository->find($id, null, ['user']);
        if ($task === null) {
            return $this->apiResponse->failed('Task not found');
        }
        return $this->apiResponse->success('Task retrieved successfully', $task);
    }

    //update task

    /**
     * @throws \Exception
     */
    public function updateTask(UpdateTaskRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        if ($request->has('is_completed') && $request->input('is_completed') === true) {
            $data['completed_at'] = now();
        }
        $task = $this->taskRepository->update($data, $id);
        return $this->apiResponse->success('Task updated successfully', $task);
    }

    //delete task

    /**
     * @throws \Exception
     */
    public function deleteTask($id): \Illuminate\Http\JsonResponse
    {
        //check if task exists
        $task = $this->taskRepository->find($id);
        if ($task === null) {
            return $this->apiResponse->failed('Task not found');
        }
        $this->taskRepository->delete($id);
        return $this->apiResponse->success('Task deleted successfully');
    }
}
