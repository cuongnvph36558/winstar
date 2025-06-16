<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StorageController;

/** Admin*/
Route::prefix('admin')->group(function () {
    // Add route for admin dashboard/home page
    Route::get('/', function() {
        return view('admin.dashboard'); // Make sure you have this view
    })->name('admin.dashboard');

    /*** Category*/
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'GetAllCategory'])->name('admin.category.index-category');
        Route::get('/create', [CategoryController::class, 'CreateCategory'])->name('admin.category.create-category');
        Route::post('/store', [CategoryController::class, 'StoreCategory'])->name('admin.category.store');
        Route::get('/restore-category', [CategoryController::class, 'TrashCategory'])->name('admin.category.restore-category');
        Route::get('/restore/{id}', [CategoryController::class, 'RestoreCategory'])->name('admin.category.restore');
        Route::get('/force-delete/{id}', [CategoryController::class, 'ForceDeleteCategory'])->name('admin.category.force-delete');
        Route::get('/edit/{id}', [CategoryController::class, 'EditCategory'])->name('admin.category.edit-category');
        Route::get('/{id}', [CategoryController::class, 'ShowCategory'])->name('admin.category.show-category');
        Route::put('/update/{id}', [CategoryController::class, 'UpdateCategory'])->name('admin.category.update-category');
        Route::delete('/delete/{id}', [CategoryController::class, 'DeleteCategory'])->name('admin.category.delete');
    });

});

        /*** Product */
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [ProductController::class, 'GetAllProduct'])->name('admin.product.index-product');
        Route::get('/create', [ProductController::class, 'CreateProduct'])->name('admin.product.create-product');
        Route::post('/store', [ProductController::class, 'StoreProduct'])->name('admin.product.store');
        Route::get('/restore-product', [ProductController::class, 'TrashProduct'])->name('admin.product.restore-product');
        Route::get('/restore/{id}', [ProductController::class, 'RestoreProduct'])->name('admin.product.restore');
        Route::get('/force-delete/{id}', [ProductController::class, 'ForceDeleteProduct'])->name('admin.product.force-delete');
        Route::get('/edit/{id}', [ProductController::class, 'EditProduct'])->name('admin.product.edit-product');
        Route::get('/{id}', [ProductController::class, 'ShowProduct'])->name('admin.product.show-product');
        Route::put('/update/{id}', [ProductController::class, 'UpdateProduct'])->name('admin.product.update-product');
        Route::delete('/delete/{id}', [ProductController::class, 'DeleteProduct'])->name('admin.product.delete');
    });

    /*** Color */
    Route::prefix('color')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('admin.color.index');
        Route::get('/create', [ColorController::class, 'create'])->name('admin.color.create');
        Route::post('/store', [ColorController::class, 'store'])->name('admin.color.store');
        Route::get('/edit/{id}', [ColorController::class, 'edit'])->name('admin.color.edit');
        Route::put('/update/{id}', [ColorController::class, 'update'])->name('admin.color.update');
        Route::delete('/delete/{id}', [ColorController::class, 'destroy'])->name('admin.color.delete');
        Route::get('/{id}', [ColorController::class, 'show'])->name('admin.color.show');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('storages', [StorageController::class, 'index'])->name('storage.index');
    Route::get('storages/create', [StorageController::class, 'create'])->name('storage.create');
    Route::post('storages/store', [StorageController::class, 'store'])->name('storage.store');
    Route::get('storages/edit/{id}', [StorageController::class, 'edit'])->name('storage.edit');
    Route::put('storages/update/{id}', [StorageController::class, 'update'])->name('storage.update');
    Route::delete('storages/delete/{id}', [StorageController::class, 'destroy'])->name('storage.delete');
});




// Route::get('/', [Controller::class, 'test']);
