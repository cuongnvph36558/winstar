<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class DashboardPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo permission cho dashboard (nếu chưa có)
        $dashboardPermission = Permission::firstOrCreate([
            'name' => 'dashboard.view'
        ], [
            'description' => 'Xem bảng điều khiển'
        ]);

        // Gán permission cho role admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->attach($dashboardPermission->id);
        }

        // Gán permission cho role super admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdminRole->permissions()->attach($dashboardPermission->id);
        }

        $this->command->info('Dashboard permission created and assigned to admin roles successfully!');
    }
}
