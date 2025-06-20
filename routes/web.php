<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\AuthenticationController;

// Client
Route::get('/', [HomeController::class, 'index'])->name('client.home');



/** Admin*/
Route::prefix('admin')->middleware(['admin.access'])->group(function () {
    // Add route for admin dashboard/home page
    Route::get('/', function() {
        return view('admin.dashboard'); // Make sure you have this view
    })->name('admin.dashboard')->middleware('permission:dashboard.view');
    Route::get('/dashboard', function() {
        return view('admin.dashboard'); // Make sure you have this view
    })->name('admin.dashboard')->middleware('permission:dashboard.view');
    /*** Category*/
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'GetAllCategory'])->name('admin.category.index-category')->middleware('permission:category.view');
        Route::get('/create', [CategoryController::class, 'CreateCategory'])->name('admin.category.create-category')->middleware('permission:category.create');
        Route::post('/store', [CategoryController::class, 'StoreCategory'])->name('admin.category.store')->middleware('permission:category.create');
        Route::get('/restore-category', [CategoryController::class, 'TrashCategory'])->name('admin.category.restore-category')->middleware('permission:category.view');
        Route::get('/restore/{id}', [CategoryController::class, 'RestoreCategory'])->name('admin.category.restore')->middleware('permission:category.edit');
        Route::get('/force-delete/{id}', [CategoryController::class, 'ForceDeleteCategory'])->name('admin.category.force-delete')->middleware('permission:category.delete');
        Route::get('/edit/{id}', [CategoryController::class, 'EditCategory'])->name('admin.category.edit-category')->middleware('permission:category.edit');
        Route::get('/{id}', [CategoryController::class, 'ShowCategory'])->name('admin.category.show-category')->middleware('permission:category.view');
        Route::put('/update/{id}', [CategoryController::class, 'UpdateCategory'])->name('admin.category.update-category')->middleware('permission:category.edit');
        Route::delete('/delete/{id}', [CategoryController::class, 'DeleteCategory'])->name('admin.category.delete')->middleware('permission:category.delete');
    });

    // User Management Routes (chỉ xem, sửa, xóa - không tạo vì có đăng ký)
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index')->middleware('permission:user.view');
        Route::get('/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show')->middleware('permission:user.view');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit')->middleware('permission:user.edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update')->middleware('permission:user.edit');
        Route::delete('/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy')->middleware('permission:user.delete');
        Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggle-status')->middleware('permission:user.edit');
        
        // User roles management
        Route::get('/{id}/roles', [App\Http\Controllers\Admin\UserController::class, 'roles'])->name('admin.users.roles')->middleware('permission:user.manage_roles');
        Route::put('/{id}/roles', [App\Http\Controllers\Admin\UserController::class, 'updateRoles'])->name('admin.users.update-roles')->middleware('permission:user.manage_roles');
        Route::get('/{id}/permissions', [App\Http\Controllers\Admin\UserController::class, 'permissions'])->name('admin.users.permissions')->middleware('permission:user.view');
    });

    // Role Management Routes
    Route::resource('roles', RoleController::class, ['as' => 'admin'])->middleware('permission:role.view,role.create,role.edit,role.delete');
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->name('admin.roles.permissions')->middleware('permission:role.manage_permissions');
        Route::put('/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('admin.roles.update-permissions')->middleware('permission:role.manage_permissions');
    });

    // Permission Management Routes
    Route::resource('permissions', PermissionController::class, ['as' => 'admin'])->middleware('permission:permission.view,permission.create,permission.edit,permission.delete');
    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/bulk-create', [PermissionController::class, 'bulkCreate'])->name('admin.permissions.bulk-create')->middleware('permission:permission.create');
        Route::post('/bulk-store', [PermissionController::class, 'bulkStore'])->name('admin.permissions.bulk-store')->middleware('permission:permission.create');
    });

});

// Authentication Routes
Route::get('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('login', [AuthenticationController::class, 'postLogin'])->name('postLogin');
Route::get('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('register', [AuthenticationController::class, 'postRegister'])->name('postRegister');
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

// Google OAuth Routes
Route::get('auth/google', [AuthenticationController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthenticationController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Password Reset Routes
Route::get('forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('forgot-password', [AuthenticationController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');

