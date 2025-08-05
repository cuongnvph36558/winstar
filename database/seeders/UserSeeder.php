<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@winstar.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'status' => 1,
        ]);

        // Create some sample users
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => '123 Nguyen Trai, Ha Noi',
            'city' => 'Ha Noi',
            'district' => 'Dong Da',
            'ward' => 'Lang Ha',
            'status' => 1,
        ]);

        User::create([
            'name' => 'Tran Thi B',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654322',
            'address' => '456 Le Loi, Ho Chi Minh',
            'city' => 'Ho Chi Minh',
            'district' => 'District 1',
            'ward' => 'Ben Nghe',
            'status' => 1,
        ]);

        // Create additional users using factory
        User::factory(5)->create();
    }
} 