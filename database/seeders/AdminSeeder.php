<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
    
        User::create([
            'name' => 'Admin User',
            'phone_number' => '0712345678',
            'email' => 'admin@gmail.com',
            'role' => '1',
            'password' => bcrypt('password'), 
        ]);

    }
}
