<?php

namespace Database\Seeders;

use App\Models\PostPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postPackages = [
            ['quantity' => 1, 'price' => 10],
            ['quantity' => 10, 'price' => 90],
            ['quantity' => 20, 'price' => 170],
            ['quantity' => 50, 'price' => 400],
        ];
        foreach ($postPackages as $x) {
            PostPackage::create($x);
        }
    }
}
