<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/sign-up', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User created successfully',
            ]);
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function test_can_login_user()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User logged in successfully',
                'data' => [
                    'token' => true, // Token value is dynamic, just check if it exists
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                ]
            ]);
        $response->assertJsonStructure([
            'data' => [
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ],
        ]);
    }

}
