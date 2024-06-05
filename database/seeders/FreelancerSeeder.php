<?php

namespace Database\Seeders;

use App\Models\Freelancer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FreelancerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 freelancers using the factory
        $freelancers = Freelancer::factory()->count(10)->create();

        // Loop through each freelancer to create a token
        $freelancers->each(function ($freelancer) {
            $skills = collect(range(1,20))->random(3);
            $favoriteCategories = collect(range(1,4))->random();
            $freelancer->skills()->attach($skills);
            $freelancer->favoriteCategories()->attach($favoriteCategories);
            // Ensure you're inside a database transaction
            DB::transaction(function () use ($freelancer) {
                $token = $freelancer->createToken('freelancer')->plainTextToken;
                if($freelancer->id == 1){
                    Log::channel('tokens')->info('new logs');
                }
                Log::channel('tokens')->info('freelancer id :'.$freelancer->id .' token : '.$token);
            });
        });
    }
}
