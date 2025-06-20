<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TinTucController;


Route::prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    /*** Category */
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'GetAllCategory'])->name('category.index-category');
        Route::get('/create', [CategoryController::class, 'CreateCategory'])->name('category.create-category');
        Route::post('/store', [CategoryController::class, 'StoreCategory'])->name('category.store');
        Route::get('/restore-category', [CategoryController::class, 'TrashCategory'])->name('category.restore-category');
        Route::get('/restore/{id}', [CategoryController::class, 'RestoreCategory'])->name('category.restore');
        Route::get('/force-delete/{id}', [CategoryController::class, 'ForceDeleteCategory'])->name('category.force-delete');
        Route::get('/edit/{id}', [CategoryController::class, 'EditCategory'])->name('category.edit-category');
        Route::get('/{id}', [CategoryController::class, 'ShowCategory'])->name('category.show-category');
        Route::put('/update/{id}', [CategoryController::class, 'UpdateCategory'])->name('category.update-category');
        Route::delete('/delete/{id}', [CategoryController::class, 'DeleteCategory'])->name('category.delete');
    });

    /*** Tin tức */
    Route::resource('tin-tuc', TinTucController::class);
    Route::post('tin-tuc/{id}/toggle', [TinTucController::class, 'toggle'])->name('tin-tuc.toggle');
=======
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\Variant\ProductVariant;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\Variant\ProductVariant;

// Client
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/contact', [HomeController::class, 'contact'])->name('client.contact');
Route::get('/blog', [HomeController::class, 'blog'])->name('client.blog');
Route::get('/login-register', [HomeController::class, 'loginRegister'])->name('client.login-register');
Route::get('/about', [HomeController::class, 'about'])->name('client.about');
Route::get('/product', [HomeController::class, 'product'])->name('client.product');
Route::get('/single-product/{id}', [HomeController::class, 'singleProduct'])->name('client.single-product');
Route::get('/cart', [HomeController::class, 'cart'])->name('client.cart');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('client.checkout');




/** Admin*/
Route::prefix('admin')->middleware(['admin.access'])->group(function () {
    // Add route for admin dashboard/home page
    Route::get('/', function () {
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

    /*** Product */
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [ProductController::class, 'GetAllProduct'])->name('admin.product.index-product');
        Route::get('/create', [ProductController::class, 'CreateProduct'])->name('admin.product.create-product');
        Route::post('/store', [ProductController::class, 'StoreProduct'])->name('admin.product.store');
        Route::get('/restore-product', [ProductController::class, 'TrashProduct'])->name('admin.product.restore-product');
        Route::get('/restore/{id}', [ProductController::class, 'RestoreProduct'])->name('admin.product.restore');
        Route::get('/force-delete/{id}', [ProductController::class, 'ForceDeleteProduct'])->name('admin.product.force-delete');
        Route::get('/trash-variant', [ProductController::class, 'TrashProductVariant'])->name('admin.product.product-variant.trash');
        Route::get('/restore-variant/{id}', [ProductController::class, 'RestoreProductVariant'])->name('admin.product.product-variant.restore');
        Route::get('/force-delete-variant/{id}', [ProductController::class, 'ForceDeleteProductVariant'])->name('admin.product.product-variant.force-delete');
        Route::get('/edit/{id}', [ProductController::class, 'EditProduct'])->name('admin.product.edit-product');
        Route::put('/update/{id}', [ProductController::class, 'UpdateProduct'])->name('admin.product.update-product');
        Route::delete('/delete/{id}', [ProductController::class, 'DeleteProduct'])->name('admin.product.delete');
        Route::get('/create-variant/{id}', [ProductController::class, 'CreateProductVariant'])->name('admin.product.product-variant.create');
        Route::post('/store-variant', [ProductController::class, 'StoreProductVariant'])->name('admin.product.product-variant.store');
        Route::get('/edit-variant/{id}', [ProductController::class, 'EditProductVariant'])->name('admin.product.product-variant.edit');
        Route::put('/update-variant/{id}', [ProductController::class, 'UpdateProductVariant'])->name('admin.product.product-variant.update');
        Route::delete('/delete-variant/{id}', [ProductController::class, 'DeleteProductVariant'])->name('admin.product.product-variant.delete');
        Route::get('/list-variant', [ProductVariant::class, 'GetAllProductVariant'])->name('admin.product.product-variant.variant.list-variant');
        Route::get('/create-color-variant', [ProductVariant::class, 'CreateColorVariant'])->name('admin.product.product-variant.variant.create-color');
        Route::get('/create-storage-variant', [ProductVariant::class, 'CreateStorageVariant'])->name('admin.product.product-variant.variant.create-storage');
        Route::post('/store-color-variant', [ProductVariant::class, 'StoreColorVariant'])->name('admin.product.product-variant.variant.store-color');
        Route::post('/store-storage-variant', [ProductVariant::class, 'StoreStorageVariant'])->name('admin.product.product-variant.variant.store-storage');
        Route::get('/edit-color-variant/{id}', [ProductVariant::class, 'EditColorVariant'])->name('admin.product.product-variant.variant.edit-color');
        Route::get('/edit-storage-variant/{id}', [ProductVariant::class, 'EditStorageVariant'])->name('admin.product.product-variant.variant.edit-storage');
        Route::put('/update-color-variant/{id}', [ProductVariant::class, 'UpdateColorVariant'])->name('admin.product.product-variant.variant.update-color');
        Route::put('/update-storage-variant/{id}', [ProductVariant::class, 'UpdateStorageVariant'])->name('admin.product.product-variant.variant.update-storage');
        Route::delete('/delete-color-variant/{id}', [ProductVariant::class, 'DeleteColorVariant'])->name('admin.product.product-variant.variant.delete-color');
        Route::delete('/delete-storage-variant/{id}', [ProductVariant::class, 'DeleteStorageVariant'])->name('admin.product.product-variant.variant.delete-storage');
        // Move this route below other specific routes to avoid conflicts
        Route::get('/{id}', [ProductController::class, 'ShowProduct'])->name('admin.product.show-product');
    });

    /*** Banner */
    Route::group(['prefix' => 'banner'], function () {
        Route::get('/', [BannerController::class, 'index'])->name('admin.banner.index-banner');
        Route::get('/restore-banner', [BannerController::class, 'trash'])->name('admin.banner.restore-banner');
        Route::get('/restore/{id}', [BannerController::class, 'restore'])->name('admin.banner.restore');
        Route::delete('/force-delete/{id}', [BannerController::class, 'forceDelete'])->name('admin.banner.force-delete');
        Route::get('/detail/{id}', [BannerController::class, 'detail'])->name('admin.banner.detail-banner');
        Route::get('/create', [BannerController::class, 'create'])->name('admin.banner.create-banner');
        Route::post('/store', [BannerController::class, 'store'])->name('admin.banner.store-banner');
        Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('admin.banner.edit-banner');
        Route::put('/update/{id}', [BannerController::class, 'update'])->name('admin.banner.update-banner');
        Route::delete('/delete/{id}', [BannerController::class, 'destroy'])->name('admin.banner.destroy-banner');
    });
    
    Route::fallback(function () {
        return view('admin.404');
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


