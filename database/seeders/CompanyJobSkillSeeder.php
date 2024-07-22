<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyJob;
use App\Models\Skill;

class CompanyJobSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyJobs = CompanyJob::all();
        $skills = Skill::all();

        // Loop through each company job to attach random skills
        $companyJobs->each(function ($companyJob) use ($skills) {
            // Attach 3 to 5 random skills to each company job
            $randomSkills = $skills->random(rand(3, 5))->pluck('id');
            $companyJob->skills()->attach($randomSkills);
        });
    }
}
