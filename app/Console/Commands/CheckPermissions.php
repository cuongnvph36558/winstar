<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CheckPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:check {--cleanup : Clean up invalid permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check permission system consistency and optionally clean up';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== KIỂM TRA HỆ THỐNG PERMISSIONS ===');
        
        // 1. Kiểm tra permissions trong database
        $this->checkDatabasePermissions();
        
        // 2. Kiểm tra seeder permissions
        $this->checkSeederPermissions();
        
        // 3. Kiểm tra gates
        $this->checkGates();
        
        // 4. Kiểm tra users và roles
        $this->checkUsersAndRoles();
        
        // 5. Clean up nếu được yêu cầu
        if ($this->option('cleanup')) {
            $this->cleanupInvalidPermissions();
        }
        
        $this->info('=== HOÀN THÀNH KIỂM TRA ===');
    }
    
    private function checkDatabasePermissions()
    {
        $this->info("\n1. PERMISSIONS TRONG DATABASE:");
        $permissions = Permission::all();
        $this->line("Total permissions: " . $permissions->count());
        
        $grouped = $permissions->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });
        
        foreach ($grouped as $module => $modulePermissions) {
            $this->line("  {$module}: " . $modulePermissions->count() . " permissions");
        }
        
        // Kiểm tra permissions không chuẩn
        $invalidPermissions = $permissions->filter(function($permission) {
            return !str_contains($permission->name, '.');
        });
        
        if ($invalidPermissions->count() > 0) {
            $this->warn("Found invalid permissions (no module.action format):");
            foreach ($invalidPermissions as $permission) {
                $this->line("  - {$permission->name}");
            }
        }
    }
    
    private function checkSeederPermissions()
    {
        $this->info("\n2. PERMISSIONS TRONG SEEDER:");
        
        // Permissions theo seeder
        $seederPermissions = [
            'user.view', 'user.edit', 'user.delete', 'user.manage_roles',
            'role.view', 'role.create', 'role.edit', 'role.delete', 'role.manage_permissions',
            'permission.view', 'permission.create', 'permission.edit', 'permission.delete',
            'category.view', 'category.create', 'category.edit', 'category.delete',
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'order.view', 'order.create', 'order.edit', 'order.delete', 'order.process',
            'review.view', 'review.moderate', 'review.delete',
            'dashboard.view', 'report.view', 'report.export',
            'setting.view', 'setting.edit'
        ];
        
        $dbPermissions = Permission::pluck('name')->toArray();
        
        // Permissions trong DB nhưng không trong seeder
        $extraInDb = array_diff($dbPermissions, $seederPermissions);
        if (!empty($extraInDb)) {
            $this->warn("Permissions in DB but not in seeder:");
            foreach ($extraInDb as $permission) {
                $this->line("  - {$permission}");
            }
        }
        
        // Permissions trong seeder nhưng không trong DB
        $missingInDb = array_diff($seederPermissions, $dbPermissions);
        if (!empty($missingInDb)) {
            $this->warn("Permissions in seeder but missing in DB:");
            foreach ($missingInDb as $permission) {
                $this->line("  - {$permission}");
            }
        }
        
        if (empty($extraInDb) && empty($missingInDb)) {
            $this->info("✅ Permissions đồng bộ với seeder");
        }
    }
    
    private function checkGates()
    {
        $this->info("\n3. KIỂM TRA GATES:");
        
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'super_admin');
        })->first();
        
        if (!$user) {
            $this->warn("Không tìm thấy super_admin user để test gates");
            return;
        }
        
        $testGates = [
            'view-dashboard',
            'manage-users',
            'manage-roles', 
            'manage-permissions',
            'manage-categories',
            'manage-products',
            'manage-orders',
            'manage-reviews',
            'view-reports',
            'manage-settings',
            'admin-access'
        ];
        
        foreach ($testGates as $gate) {
            $result = Gate::forUser($user)->allows($gate);
            $status = $result ? '✅' : '❌';
            $this->line("  {$gate}: {$status}");
        }
        
        // Test dynamic gates
        $this->line("\nDynamic Gates (trực tiếp permissions):");
        $testPermissions = ['dashboard.view', 'user.edit', 'role.create', 'nonexistent.permission'];
        
        foreach ($testPermissions as $permission) {
            $result = Gate::forUser($user)->allows($permission);
            $status = $result ? '✅' : '❌';
            $this->line("  {$permission}: {$status}");
        }
    }
    
    private function checkUsersAndRoles()
    {
        $this->info("\n4. USERS VÀ ROLES:");
        
        $roles = Role::withCount(['permissions', 'users'])->get();
        
        foreach ($roles as $role) {
            $this->line("Role: {$role->name}");
            $this->line("  Permissions: {$role->permissions_count}");
            $this->line("  Users: {$role->users_count}");
        }
    }
    
    private function cleanupInvalidPermissions()
    {
        $this->info("\n5. CLEANUP INVALID PERMISSIONS:");
        
        // Xóa permissions không có format module.action
        $invalidPermissions = Permission::whereNotExists(function($query) {
            $query->selectRaw('1')->whereRaw("name LIKE '%.%'");
        })->get();
        
        if ($invalidPermissions->count() > 0) {
            foreach ($invalidPermissions as $permission) {
                $this->warn("Deleting invalid permission: {$permission->name}");
                $permission->delete();
            }
        }
        
        // Xóa permissions không được sử dụng bởi role nào
        $unusedPermissions = Permission::whereDoesntHave('roles')->get();
        
        if ($unusedPermissions->count() > 0) {
            $this->warn("Found unused permissions:");
            foreach ($unusedPermissions as $permission) {
                $this->line("  - {$permission->name}");
            }
            
            if ($this->confirm('Delete unused permissions?')) {
                foreach ($unusedPermissions as $permission) {
                    $permission->delete();
                    $this->info("Deleted: {$permission->name}");
                }
            }
        }
        
        $this->info("✅ Cleanup completed");
    }
}
