<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 companies using the factory
        $companies = Company::factory()->count(50)->create();

        // Loop through each company to create a token
        $companies->each(function ($company) {
            // Ensure you're inside a database transaction
            DB::transaction(function () use ($company) {
                // Assuming you have a method to create a token
                $token = $company->createToken('company')->plainTextToken;

                // Log the token creation
                Log::channel('tokens')->info('company id :' . $company->id . ' token : ' . $token);
            });
        });
    }
}
