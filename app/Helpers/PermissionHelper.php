<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class PermissionHelper
{
    /**
     * Kiểm tra user hiện tại có quyền không
     */
    public static function hasPermission($permission)
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->hasPermission($permission);
    }

    /**
     * Kiểm tra user hiện tại có vai trò không
     */
    public static function hasRole($role)
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->hasRole($role);
    }

    /**
     * Kiểm tra user hiện tại có phải admin không
     */
    public static function isAdmin()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Kiểm tra user hiện tại có phải super admin không
     */
    public static function isSuperAdmin()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    /**
     * Lấy tất cả quyền của user hiện tại
     */
    public static function getUserPermissions()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        $permissions = collect();
        foreach ($user->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        return $permissions->unique('id');
    }

    /**
     * Lấy tất cả vai trò của user hiện tại
     */
    public static function getUserRoles()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        return $user->roles;
    }

    /**
     * Kiểm tra user có thể truy cập menu không
     */
    public static function canAccessMenu($menuPermissions)
    {
        if (empty($menuPermissions)) {
            return true;
        }

        if (is_string($menuPermissions)) {
            $menuPermissions = [$menuPermissions];
        }

        foreach ($menuPermissions as $permission) {
            if (self::hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tạo dropdown các vai trò cho form
     */
    public static function getRoleOptions()
    {
        return Role::orderBy('name')->pluck('description', 'id');
    }

    /**
     * Tạo dropdown các quyền cho form
     */
    public static function getPermissionOptions()
    {
        return Permission::orderBy('name')->pluck('description', 'id');
    }

    /**
     * Nhóm quyền theo module
     */
    public static function getGroupedPermissions()
    {
        $permissions = Permission::orderBy('name')->get();
        
        return $permissions->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });
    }

    /**
     * Lấy danh sách các module có quyền
     */
    public static function getModules()
    {
        $permissions = Permission::select('name')->get();
        $modules = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            if (count($parts) >= 2) {
                $module = $parts[0];
                if (!in_array($module, $modules)) {
                    $modules[] = $module;
                }
            }
        }

        sort($modules);
        return $modules;
    }

    /**
     * Lấy tên module bằng tiếng Việt
     */
    public static function getModuleName($module)
    {
        $moduleNames = [
            'user' => 'Người dùng',
            'role' => 'Vai trò', 
            'permission' => 'Quyền',
            'category' => 'Danh mục',
            'product' => 'Sản phẩm',
            'order' => 'Đơn hàng',
            'review' => 'Đánh giá',
            'dashboard' => 'Dashboard',
            'report' => 'Báo cáo',
            'setting' => 'Cài đặt',
        ];

        return $moduleNames[$module] ?? ucfirst($module);
    }

    /**
     * Lấy tên action bằng tiếng Việt
     */
    public static function getActionName($action)
    {
        $actionNames = [
            'view' => 'Xem',
            'create' => 'Tạo mới',
            'edit' => 'Chỉnh sửa',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
            'manage' => 'Quản lý',
            'process' => 'Xử lý',
            'export' => 'Xuất',
            'import' => 'Nhập',
            'approve' => 'Phê duyệt',
            'reject' => 'Từ chối',
        ];

        return $actionNames[$action] ?? ucfirst($action);
    }

    /**
     * Format tên permission để hiển thị
     */
    public static function formatPermissionName($permissionName)
    {
        $parts = explode('.', $permissionName);
        if (count($parts) >= 2) {
            $module = self::getModuleName($parts[0]);
            $action = self::getActionName($parts[1]);
            return $action . ' ' . $module;
        }

        return $permissionName;
    }

    /**
     * Kiểm tra user có thể thực hiện action trên module không
     */
    public static function can($module, $action)
    {
        return self::hasPermission($module . '.' . $action);
    }

    /**
     * Lấy badge color cho role
     */
    public static function getRoleBadgeColor($roleName)
    {
        $colors = [
            'super_admin' => 'danger',
            'admin' => 'warning', 
            'manager' => 'info',
            'staff' => 'success',
            'customer' => 'secondary',
            'guest' => 'light',
        ];

        return $colors[$roleName] ?? 'primary';
    }

    /**
     * Lấy icon cho module
     */
    public static function getModuleIcon($module)
    {
        $icons = [
            'user' => 'fa fa-users',
            'role' => 'fa fa-user-circle',
            'permission' => 'fa fa-key',
            'category' => 'fa fa-list',
            'product' => 'fa fa-cube',
            'order' => 'fa fa-shopping-cart',
            'review' => 'fa fa-star',
            'dashboard' => 'fa fa-dashboard',
            'report' => 'fa fa-bar-chart',
            'setting' => 'fa fa-cog',
        ];

        return $icons[$module] ?? 'fa fa-folder';
    }
} 