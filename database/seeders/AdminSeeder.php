<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'Admin@gmail.com',
            'password' => Hash::make(123456)
        ]);
        $token = $admin->createToken('admin')->plainTextToken;
        Log::channel('tokens')->info('admin id :'.$admin->id .' token : '.$token);
    }
}
