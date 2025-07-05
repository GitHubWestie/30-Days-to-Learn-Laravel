<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employer_id' => Employer::factory(),
            'title' => fake()->jobTitle(),
            'salary' => fake()->randomElement(['$50,000USD', '$90,000USD', '$150,000USD']),
            'location' => fake()->randomElement(['Remote', 'Hybrid', 'Office', 'Field']),
            'schedule' => fake()->randomElement(['Full-time', 'Part-time', 'Flexible']),
            'url' => fake()->url(),
            'featured' => fake()->boolean(),
        ];
    }
}
