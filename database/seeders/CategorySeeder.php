<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialization_id = 1;
        $categories =[
            [
                'specialization_id' => $specialization_id,
                'name' => 'backend'
            ],[
                'specialization_id' => $specialization_id,
                'name' => 'frontend'
            ],[
                'specialization_id' => $specialization_id,
                'name' => 'AI'
            ],[
                'specialization_id' => $specialization_id,
                'name' => 'Java Dev'
            ],
        ];
        DB::table('categories')->insert($categories);
    }
}
