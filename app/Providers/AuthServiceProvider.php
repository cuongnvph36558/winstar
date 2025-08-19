<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Định nghĩa Gate động dựa trên permissions
        Gate::before(function (User $user, $ability) {
            // Admin có tất cả quyền
            if ($user->isAdmin()) {
                return true; 
            }
            
            // Kiểm tra permission trực tiếp (format: module.action)
            if ($user->hasPermission($ability)) {
                return true;
            }
            
            return null; // Tiếp tục kiểm tra các gate khác
        });

        // Các Gate nhóm theo chức năng (để sử dụng trong view/controller)
        Gate::define('view-dashboard', function (User $user) {
            return $user->hasPermission('dashboard.view');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasPermission('user.view') || 
                   $user->hasPermission('user.edit') || 
                   $user->hasPermission('user.delete') ||
                   $user->hasPermission('user.manage_roles');
        });

        Gate::define('manage-roles', function (User $user) {
            return $user->hasPermission('role.view') || 
                   $user->hasPermission('role.create') || 
                   $user->hasPermission('role.edit') || 
                   $user->hasPermission('role.delete') ||
                   $user->hasPermission('role.manage_permissions');
        });

        Gate::define('manage-permissions', function (User $user) {
            return $user->hasPermission('permission.view') || 
                   $user->hasPermission('permission.create') || 
                   $user->hasPermission('permission.edit') || 
                   $user->hasPermission('permission.delete');
        });

        Gate::define('manage-categories', function (User $user) {
            return $user->hasPermission('category.view') || 
                   $user->hasPermission('category.create') || 
                   $user->hasPermission('category.edit') || 
                   $user->hasPermission('category.delete');
        });

        Gate::define('manage-products', function (User $user) {
            return $user->hasPermission('product.view') || 
                   $user->hasPermission('product.create') || 
                   $user->hasPermission('product.edit') || 
                   $user->hasPermission('product.delete');
        });

        Gate::define('manage-orders', function (User $user) {
            return $user->hasPermission('order.view') || 
                   $user->hasPermission('order.create') || 
                   $user->hasPermission('order.edit') || 
                   $user->hasPermission('order.delete') ||
                   $user->hasPermission('order.process');
        });

        Gate::define('manage-reviews', function (User $user) {
            return $user->hasPermission('review.view') || 
                   $user->hasPermission('review.moderate') || 
                   $user->hasPermission('review.delete');
        });

        Gate::define('view-reports', function (User $user) {
            return $user->hasPermission('report.view') || 
                   $user->hasPermission('report.export');
        });

        Gate::define('manage-settings', function (User $user) {
            return $user->hasPermission('setting.view') || 
                   $user->hasPermission('setting.edit');
        });

        // Gate cho admin access
        Gate::define('admin-access', function (User $user) {
            return $user->hasPermission('dashboard.view') || 
                   $user->isAdmin();
        });
    }
}
