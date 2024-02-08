<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert', 'master']),
            'order' => $this->faker->numberBetween(0, 100),
            'user_id' => User::factory(),
        ];
    }
}
