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
            'name' => 'Quản Trị Viên',
            'email' => 'admin@winstar.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'status' => 1,
        ]);

        // Create staff user
        User::create([
            'name' => 'Nhân Viên',
            'email' => 'staff@winstar.com',
            'password' => Hash::make('password'),
            'phone' => '0123456790',
            'status' => 1,
        ]);

        // Create some sample users
        User::create([
            'name' => 'Nguyễn Văn An',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => '123 Nguyễn Trãi, Hà Nội',
            'city' => 'Hà Nội',
            'district' => 'Đống Đa',
            'ward' => 'Láng Hạ',
            'status' => 1,
        ]);

        User::create([
            'name' => 'Trần Thị Bình',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654322',
            'address' => '456 Lê Lợi, TP. Hồ Chí Minh',
            'city' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Bến Nghé',
            'status' => 1,
        ]);

        // Create additional users with Vietnamese names
        User::create([
            'name' => 'Lê Văn Cường',
            'email' => 'user3@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654323',
            'address' => '789 Trần Hưng Đạo, Đà Nẵng',
            'city' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'ward' => 'Phước Ninh',
            'status' => 1,
        ]);

        User::create([
            'name' => 'Phạm Thị Dung',
            'email' => 'user4@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654324',
            'address' => '321 Lý Thường Kiệt, Cần Thơ',
            'city' => 'Cần Thơ',
            'district' => 'Ninh Kiều',
            'ward' => 'An Hội',
            'status' => 1,
        ]);

        User::create([
            'name' => 'Hoàng Văn Em',
            'email' => 'user5@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654325',
            'address' => '654 Nguyễn Huệ, Huế',
            'city' => 'Thừa Thiên Huế',
            'district' => 'Thành phố Huế',
            'ward' => 'Phú Hội',
            'status' => 1,
        ]);
    }
} 