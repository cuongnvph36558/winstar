<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
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
});

// Route::get('/', [Controller::class, 'test']);
