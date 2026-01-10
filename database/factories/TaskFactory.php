<?php

namespace Database\Factories;

use App\Enum\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        $description = random_int(1, 3) == 1 ? null : fake()->sentence(5);

        return [
            'status' => fake()->randomElement(TaskStatus::cases()),
            'title' => fake()->sentence(3),
            'description' => $description,
        ];
    }
}
