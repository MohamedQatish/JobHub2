<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'IT'
            ],
            [
                'name' => 'Design & Creative'
            ],
            [
                'name' => 'Sales & Marketing'
            ],
            [
                'name' => 'Writing & Translation'
            ]
        ];
        DB::table('specializations')->insert($specializations);
    }
}
