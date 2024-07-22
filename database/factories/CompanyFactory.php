<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'company_name' => $this->faker->company,
            'specialization_id' => mt_rand(1, 3),
            'description' => $this->faker->paragraph,
            'country_id' => mt_rand(1, 243),
            'rating' => $this->faker->optional()->randomFloat(1, 1, 5),
            'verified_at' => $this->faker->optional()->dateTime,
            'website' => $this->faker->url,
            'location' => $this->faker->address,
            'followers' => $this->faker->numberBetween(0, 10000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Company $company) {
            Wallet::create([
                'owner_id' => $company->id,
                'owner_type' => Company::class,
                'balance' => 500.00,
            ]);
        });
    }
}
