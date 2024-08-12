<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user for all tests
        $this->user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_create_a_task()
    {
        $response = $this->postJson('/api/task/create', [
            'title' => 'Complete Project Report',
            'description' => 'Finalize and submit the project report by the end of the day.',
            'is_completed' => false,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Complete Project Report',
            'description' => 'Finalize and submit the project report by the end of the day.',
            'is_completed' => false,
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_read_tasks()
    {
        // Create tasks
        $task1 = Task::factory()->create(['user_id' => $this->user->id]);
        $task2 = Task::factory()->create(['user_id' => $this->user->id]);

        // Fetch tasks
        $response = $this->getJson('/api/task/get');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => [
                    'data' => [
                        ['id' => $task1->id],
                        ['id' => $task2->id],
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_can_read_a_task_by_id()
    {
        // Create a task
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Fetch task by ID
        $response = $this->getJson('/api/task/get/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task retrieved successfully',
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'is_completed' => $task->is_completed,
                    'user_id' => $task->user_id,
                ],
            ]);
    }

    /** @test */
    public function user_can_update_a_task()
    {
        // Create a task
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Update task
        $response = $this->putJson('/api/task/update/' . $task->id, [
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'is_completed' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => [
                    'id' => $task->id,
                    'title' => 'Updated Task Title',
                    'description' => 'Updated Description',
                    'is_completed' => true,
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'is_completed' => true,
        ]);
    }

    /** @test */
    public function user_can_delete_a_task()
    {
        // Create a task
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Delete task
        $response = $this->deleteJson('/api/task/delete/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
