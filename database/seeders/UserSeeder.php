<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'director',
            'email' => 'director@gmail.com',
            'hp' => '08123623476',
            'password' => Hash::make('password'), // Hash password for security
            'position' => 'director',
            'access_level' => 'superadmin',
            'user_status' => 'active',
        ]);

        User::create([
            'username' => 'manager',
            'email' => 'manager@gmail.com',
            'hp' => '08123622344',
            'password' => Hash::make('password'), // Hash password for security
            'position' => 'director',
            'access_level' => 'view_only',
            'user_status' => 'active',
        ]);

        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'hp' => '081234567890',
            'password' => Hash::make('password'), // Hash password for security
            'position' => 'admin',
            'user_status' => 'active',
        ]);

        User::create([
            'username' => 'user1',
            'email' => 'user@gmail.com',
            'hp' => '081234567891',
            'password' => Hash::make('password'),
            'position' => 'Staff',
            'user_status' => 'active',
        ]);
    }
}
