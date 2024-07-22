<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyJob;

class CompanyJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 company jobs using the factory
        CompanyJob::factory()->count(50)->create();
    }
}
