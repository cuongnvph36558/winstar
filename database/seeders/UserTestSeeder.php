<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo roles nếu chưa có
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ], [
            'description' => 'Quản trị viên'
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user'
        ], [
            'description' => 'Thành viên'
        ]);

        $staffRole = Role::firstOrCreate([
            'name' => 'staff'
        ], [
            'description' => 'Nhân viên'
        ]);

        // Tạo user admin
        $admin = User::where('email', 'admin@winstar.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Winstar',
                'email' => 'admin@winstar.com',
                'phone' => '0911111111',
                'address' => 'Hà Nội, Việt Nam',
                'password' => Hash::make('password123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]);
        }

        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Tạo user thường
        $user = User::where('email', 'user@winstar.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Nguyễn Văn A',
                'email' => 'user@winstar.com',
                'phone' => '0922222222',
                'address' => 'Hồ Chí Minh, Việt Nam',
                'password' => Hash::make('password123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]);
        }

        if (!$user->hasRole('user')) {
            $user->assignRole($userRole);
        }

        // Tạo nhân viên
        $staff = User::where('email', 'staff@winstar.com')->first();
        if (!$staff) {
            $staff = User::create([
                'name' => 'Trần Thị B',
                'email' => 'staff@winstar.com',
                'phone' => '0933333333',
                'address' => 'Đà Nẵng, Việt Nam',
                'password' => Hash::make('password123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]);
        }

        if (!$staff->hasRole('staff')) {
            $staff->assignRole($staffRole);
        }

        // Tạo thêm vài user để test phân trang
        for ($i = 1; $i <= 10; $i++) {
            $testUser = User::where('email', "test{$i}@winstar.com")->first();
            if (!$testUser) {
                $testUser = User::create([
                    'name' => "Test User {$i}",
                    'email' => "test{$i}@winstar.com",
                    'phone' => "094444444{$i}",
                    'address' => "Địa chỉ test {$i}",
                    'password' => Hash::make('password123'),
                    'status' => $i % 2, // Xen kẽ active/inactive
                    'email_verified_at' => $i % 3 == 0 ? null : now(), // Một số chưa verify email
                ]);
            }

            // Random assign role
            $roles = [$userRole, $staffRole];
            $randomRole = $roles[array_rand($roles)];
            
            if (!$testUser->hasRole($randomRole->name)) {
                $testUser->assignRole($randomRole);
            }
        }

        $this->command->info('Created test users successfully!');
        $this->command->line('Admin: admin@winstar.com / password123');
        $this->command->line('User: user@winstar.com / password123');
        $this->command->line('Staff: staff@winstar.com / password123');
        $this->command->line('Test users: test1-test10@winstar.com / password123');
    }
}
