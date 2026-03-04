<?php

namespace Database\Factories;

use App\Models\PastQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PastQuestion>
 */
class PastQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'subject' => $this->faker->randomElement(['Mathematics', 'Physics', 'Chemistry', 'Biology', 'English']),
            'level' => $this->faker->randomElement(['O-Level', 'A-Level', 'University']),
            'year' => (string) $this->faker->numberBetween(2000, 2025),
            'category' => $this->faker->optional()->word(),
            // For seeded data we don't need real files; file_path can be placeholder, size 0
            'file_path' => 'past-questions/sample.pdf',
            'file_size' => 0,
            'is_published' => true,
        ];
    }
}
