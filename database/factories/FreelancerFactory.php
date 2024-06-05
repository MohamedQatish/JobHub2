<?php

namespace Database\Factories;

use App\Models\Freelancer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Freelancer>
 */
class FreelancerFactory extends Factory
{
    protected $model = Freelancer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // you might want to use Hash::make() in real applications
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'hourly_wage' => $this->faker->randomFloat(2, 10, 40),
            //'residence' => $this->faker->city,
            // 'country_id' => mt_rand(1,243),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'total_jobs' => $this->faker->numberBetween(0, 50),
            'total_hours' => $this->faker->numberBetween(0, 3000),
            'total_earnings' => $this->faker->randomFloat(2, 100, 50000),
            'verified_at' => $this->faker->optional()->dateTime,
            'rating' => $this->faker->optional()->randomFloat(1, 1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
