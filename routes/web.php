<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


/**
 * Admin
 */
Route::prefix('admin')->group(function () {
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
    //testtt
});
