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
        $priceType = $this->faker->randomElement(['hourly', 'fixed']);

        $hourlyRates = [
            'hourly_rate_min' => $this->faker->randomFloat(2, 10, 50),
            'hourly_rate_max' => $this->faker->randomFloat(2, 51, 100),
        ];

        $fixedRate = [
            'fixed_rate' => $this->faker->randomFloat(2, 100, 1000),
        ];

        return [
            'owner_id' => Company::factory(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'vacancies' => $this->faker->numberBetween(1, 10),
            'scope' => $this->faker->randomElement(['small', 'medium', 'large']),
            'work_schedule' => $this->faker->randomElement(['Full-time', 'Part-time']),
            'price_type' => $priceType,
            'duration' => $this->faker->randomElement(['less than 1 month', '1 to 3 months', '3 to 6 months', 'more than 6 months']),
            'applicants_count' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ] + ($priceType === 'hourly' ? $hourlyRates : $fixedRate);
    }
}
