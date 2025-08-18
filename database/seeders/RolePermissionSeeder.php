<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo permissions
        $permissions = [
            // User Management (không có create vì đã có đăng ký)
            ['name' => 'user.view', 'description' => 'Xem danh sách người dùng'],
            ['name' => 'user.edit', 'description' => 'Chỉnh sửa người dùng'],
            ['name' => 'user.delete', 'description' => 'Xóa người dùng'],
            ['name' => 'user.manage_roles', 'description' => 'Quản lý vai trò người dùng'],

            // Role Management
            ['name' => 'role.view', 'description' => 'Xem danh sách vai trò'],
            ['name' => 'role.create', 'description' => 'Tạo mới vai trò'],
            ['name' => 'role.edit', 'description' => 'Chỉnh sửa vai trò'],
            ['name' => 'role.delete', 'description' => 'Xóa vai trò'],
            ['name' => 'role.manage_permissions', 'description' => 'Quản lý quyền của vai trò'],

            // Permission Management
            ['name' => 'permission.view', 'description' => 'Xem danh sách quyền'],
            ['name' => 'permission.create', 'description' => 'Tạo mới quyền'],
            ['name' => 'permission.edit', 'description' => 'Chỉnh sửa quyền'],
            ['name' => 'permission.delete', 'description' => 'Xóa quyền'],

            // Category Management
            ['name' => 'category.view', 'description' => 'Xem danh sách danh mục'],
            ['name' => 'category.create', 'description' => 'Tạo mới danh mục'],
            ['name' => 'category.edit', 'description' => 'Chỉnh sửa danh mục'],
            ['name' => 'category.delete', 'description' => 'Xóa danh mục'],

            // Product Management
            ['name' => 'product.view', 'description' => 'Xem danh sách sản phẩm'],
            ['name' => 'product.create', 'description' => 'Tạo mới sản phẩm'],
            ['name' => 'product.edit', 'description' => 'Chỉnh sửa sản phẩm'],
            ['name' => 'product.delete', 'description' => 'Xóa sản phẩm'],

            // Order Management
            ['name' => 'order.view', 'description' => 'Xem danh sách đơn hàng'],
            ['name' => 'order.create', 'description' => 'Tạo mới đơn hàng'],
            ['name' => 'order.edit', 'description' => 'Chỉnh sửa đơn hàng'],
            ['name' => 'order.delete', 'description' => 'Xóa đơn hàng'],
            ['name' => 'order.process', 'description' => 'Xử lý đơn hàng'],

            // Review Management
            ['name' => 'review.view', 'description' => 'Xem danh sách đánh giá'],
            ['name' => 'review.moderate', 'description' => 'Kiểm duyệt đánh giá'],
            ['name' => 'review.delete', 'description' => 'Xóa đánh giá'],

            // Dashboard & Reports
            ['name' => 'dashboard.view', 'description' => 'Xem dashboard admin'],
            ['name' => 'report.view', 'description' => 'Xem báo cáo'],
            ['name' => 'report.export', 'description' => 'Xuất báo cáo'],

            // System Settings
            ['name' => 'setting.view', 'description' => 'Xem cài đặt hệ thống'],
            ['name' => 'setting.edit', 'description' => 'Chỉnh sửa cài đặt hệ thống'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // Tạo roles - chỉ có 3 quyền: user, admin, staff
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Quản trị viên - quản lý hệ thống',
                'permissions' => 'all' // Sẽ gán tất cả permissions
            ],
            [
                'name' => 'staff',
                'description' => 'Nhân viên - xử lý đơn hàng và quản lý sản phẩm',
                'permissions' => [
                    'category.view', 'category.create', 'category.edit',
                    'product.view', 'product.create', 'product.edit',
                    'order.view', 'order.edit', 'order.process',
                    'review.view', 'review.moderate',
                    'dashboard.view', 'report.view'
                ]
            ],
            [
                'name' => 'user',
                'description' => 'Người dùng - quyền cơ bản',
                'permissions' => [
                    'product.view',
                    'order.view'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                ['description' => $roleData['description']]
            );

            // Gán permissions cho role
            if ($roleData['permissions'] === 'all') {
                // Gán tất cả permissions cho admin
                $allPermissions = Permission::all();
                $role->permissions()->sync($allPermissions->pluck('id'));
            } elseif (is_array($roleData['permissions']) && !empty($roleData['permissions'])) {
                $permissions = Permission::whereIn('name', $roleData['permissions'])->get();
                $role->permissions()->sync($permissions->pluck('id'));
            }
        }

        // Gán role cho user admin
        $adminUser = User::where('email', 'admin@winstar.com')->first();
        if ($adminUser) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && !$adminUser->hasRole('admin')) {
                $adminUser->assignRole($adminRole);
            }
        }

        // Gán role staff cho user staff
        $staffUser = User::where('email', 'staff@winstar.com')->first();
        if ($staffUser) {
            $staffRole = Role::where('name', 'staff')->first();
            if ($staffRole && !$staffUser->hasRole('staff')) {
                $staffUser->assignRole($staffRole);
            }
        }

        // Gán role user cho các user khác
        $userEmails = ['user1@example.com', 'user2@example.com'];
        foreach ($userEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $userRole = Role::where('name', 'user')->first();
                if ($userRole && !$user->hasRole('user')) {
                    $user->assignRole($userRole);
                }
            }
        }

        echo "✅ Đã tạo " . Permission::count() . " permissions\n";
        echo "✅ Đã tạo " . Role::count() . " roles\n";
        echo "✅ Đã gán permissions cho các roles\n";
        echo "✅ Đã gán roles cho users\n";
    }
}
