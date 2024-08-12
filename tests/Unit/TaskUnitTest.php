<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        // Create a task
        $response = $this->postJson('/api/task/create', [
            'title' => 'Complete Project Report',
            'description' => 'Finalize and submit the project report by the end of the day.',
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
    public function it_can_read_tasks()
    {
        $task1 = Task::factory()->create(['user_id' => $this->user->id]);
        $task2 = Task::factory()->create(['user_id' => $this->user->id]);

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
    public function it_can_read_a_task_by_id()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

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
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

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
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

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
