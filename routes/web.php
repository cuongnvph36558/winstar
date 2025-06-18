<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\ProductController;
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

/** Client*/
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/contact', [HomeController::class, 'contact'])->name('client.contact');
Route::get('/blog', [HomeController::class, 'blog'])->name('client.blog');
Route::get('/login-register', [HomeController::class, 'loginRegister'])->name('client.login-register');
Route::get('/about', [HomeController::class, 'about'])->name('client.about');
Route::get('/product', [HomeController::class, 'product'])->name('client.product');
Route::get('/product/{id}', [HomeController::class, 'singleProduct'])->name('client.single-product');
Route::get('/cart', [HomeController::class, 'cart'])->name('client.cart');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('client.checkout');

Route::fallback(function () {
    return view('client.404');
});
/** Admin*/
Route::prefix('admin')->group(function () {
    // Add route for admin dashboard/home page
    Route::get('/', function () {
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
        // Move this route below other specific routes to avoid conflicts
        Route::get('/{id}', [CategoryController::class, 'ShowCategory'])->name('admin.category.show-category');
        Route::put('/update/{id}', [CategoryController::class, 'UpdateCategory'])->name('admin.category.update-category');
        Route::delete('/delete/{id}', [CategoryController::class, 'DeleteCategory'])->name('admin.category.delete');
    });

        /*** Product */
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [ProductController::class, 'GetAllProduct'])->name('admin.product.index-product');
        Route::get('/create', [ProductController::class, 'CreateProduct'])->name('admin.product.create-product');
        Route::post('/store', [ProductController::class, 'StoreProduct'])->name('admin.product.store');
        Route::get('/restore-product', [ProductController::class, 'TrashProduct'])->name('admin.product.restore-product');
        Route::get('/restore/{id}', [ProductController::class, 'RestoreProduct'])->name('admin.product.restore');
        Route::get('/force-delete/{id}', [ProductController::class, 'ForceDeleteProduct'])->name('admin.product.force-delete');
        Route::get('/trash-variant', [ProductController::class, 'TrashProductVariant'])->name('admin.product.variant.restore');
        Route::get('/restore-variant/{id}', [ProductController::class, 'RestoreProductVariant'])->name('admin.product.variant.restore-variant');
        Route::get('/force-delete-variant/{id}', [ProductController::class, 'ForceDeleteProductVariant'])->name('admin.product.variant.force-delete-variant');
        Route::get('/edit/{id}', [ProductController::class, 'EditProduct'])->name('admin.product.edit-product');
        Route::get('/{id}', [ProductController::class, 'ShowProduct'])->name('admin.product.show-product');
        Route::put('/update/{id}', [ProductController::class, 'UpdateProduct'])->name('admin.product.update-product');
        Route::delete('/delete/{id}', [ProductController::class, 'DeleteProduct'])->name('admin.product.delete');
        Route::get('/create-variant/{id}', [ProductController::class, 'CreateProductVariant'])->name('admin.product.variant.create');
        Route::post('/store-variant', [ProductController::class, 'StoreProductVariant'])->name('admin.product.variant.store');
        Route::get('/edit-variant/{id}', [ProductController::class, 'EditProductVariant'])->name('admin.product.variant.edit');
        Route::put('/update-variant/{id}', [ProductController::class, 'UpdateProductVariant'])->name('admin.product.variant.update');
        Route::delete('/delete-variant/{id}', [ProductController::class, 'DeleteProductVariant'])->name('admin.product.variant.delete');
    });
    Route::fallback(function () {
        return view('admin.404');
    });
});
