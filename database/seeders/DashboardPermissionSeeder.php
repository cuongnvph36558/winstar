<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo permission cho dashboard
        $dashboardPermission = Permission::firstOrCreate([
            'name' => 'dashboard.view',
            'guard_name' => 'web'
        ], [
            'display_name' => 'Xem bảng điều khiển',
            'description' => 'Quyền xem trang bảng điều khiển admin'
        ]);

        // Gán permission cho role admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($dashboardPermission);
        }

        // Gán permission cho role super admin
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($dashboardPermission);
        }

        $this->command->info('Dashboard permission created and assigned to admin roles successfully!');
    }
}
