<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            [
                'name' => 'Laravel'
            ],[
                'name' => 'JavaScript'
            ],[
                'name' => 'REST API'
            ],[
                'name' => 'Python'
            ],[
                'name' => 'CSS'
            ],[
                'name' => 'HTML'
            ],[
                'name' => 'PHP'
            ],
            [
                'name' => 'SQL'
            ],[
                'name' => 'Java'
            ],[
                'name' => 'Problem Solving'
            ],[
                'name' => 'Flutter'
            ],[
                'name' => 'jQuery'
            ],[
                'name' => 'Next.JS'
            ],[
                'name' => 'React.JS'
            ],[
                'name' => 'Nest.JS'
            ],[
                'name' => 'MongoDB'
            ],[
                'name' => 'NoSQL'
            ],[
                'name' => 'C++'
            ],[
                'name' => 'C#'
            ],[
                'name' => 'Spring'
            ],  
        ];
        DB::table('skills')->insert($skills);
    }
}
