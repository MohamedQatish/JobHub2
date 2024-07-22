<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyJob>
 */
class CompanyJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => Company::factory(), // Assuming you have companies in the database
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'vacancies' => $this->faker->numberBetween(1, 10),
            'scope' => $this->faker->randomElement(['small', 'medium', 'large']),
            'work_schedule' => $this->faker->randomElement(['Full-time', 'Part-time']),
            'price_type' => $this->faker->randomElement(['hourly', 'fixed']),
            'duration' => $this->faker->randomElement(['less than 1 month', '1 to 3 months', '3 to 6 months', 'more than 6 months']),
            'hourly_rate_min' => $this->faker->optional()->randomFloat(2, 10, 50),
            'hourly_rate_max' => $this->faker->optional()->randomFloat(2, 51, 100),
            'fixed_rate' => $this->faker->optional()->randomFloat(2, 100, 1000),
            'applicants_count' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
