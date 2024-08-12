<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'is_completed' => false,
            'completed_at' => $this->faker->optional()->dateTime,
            'user_id' => $user ? $user->id : User::factory(),
        ];
    }
}
