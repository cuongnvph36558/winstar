<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{RoleController, StatController, BannerController, CategoryController, CommentController, ContactController, CouponController, FavoriteController, FeatureController, OrderController, PermissionController, PostController, Product\ProductController, Product\Variant\ProductVariant, UserController};
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ClientPostController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\FavoriteController as ClientFavoriteController;
use App\Http\Controllers\Client\CommentController as ClientCommentController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Admin\AboutController;


// ================= Client Routes =================
// Routes for client interface
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/contact', [HomeController::class, 'contact'])->name('client.contact');
Route::get('/blog', [ClientPostController::class, 'index'])->name('client.blog');
Route::get('/login-register', [HomeController::class, 'loginRegister'])->name('client.login-register');
Route::get('/about', [HomeController::class, 'about'])->name('client.about');


// Trang chủ client (hiển thị content 1)
Route::get('/', [HomeController::class, 'index'])->name('home');


// comment
Route::post('/comment/store', [ClientCommentController::class, 'store'])->name('client.comment.store');


// Product-related routes - should only use one controller consistently
Route::get('/product', [ClientProductController::class, 'product'])->name('client.product');
Route::get('/product/{id}', [ClientProductController::class, 'detailProduct'])->name('client.single-product');
Route::post('/add-review/{id}', [ClientProductController::class, 'addReview'])->name('client.add-review');

// Cart & Checkout
Route::middleware(['auth'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('client.cart');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('client.cart.destroy');


    // Order routes
    Route::prefix('order')->group(function () {
        // Checkout process
        Route::get('/checkout', [ClientOrderController::class, 'checkout'])->name('client.checkout');
        Route::post('/place-order', [ClientOrderController::class, 'placeOrder'])->name('client.place-order');
        Route::get('/success/{order}', [ClientOrderController::class, 'success'])->name('client.order.success');

        // Order management
        Route::get('/list', [ClientOrderController::class, 'index'])->name('client.order.list');
        Route::get('/{order}', [ClientOrderController::class, 'show'])->name('client.order.show');
        Route::get('/{order}/track', [ClientOrderController::class, 'track'])->name('client.order.track');
        Route::put('/{order}/cancel', [ClientOrderController::class, 'cancel'])->name('client.order.cancel');
    });

    // Payment routes
    Route::prefix('payment')->group(function () {
        // MoMo Payment
        Route::post('/momo', [ClientOrderController::class, 'momo_payment'])->name('momo.payment');
        Route::post('/momo-ipn', [ClientOrderController::class, 'momoIPN'])->name('client.order.momo-ipn');

        // VNPay Payment
        Route::get('/vnpay-return', [ClientOrderController::class, 'vnpayReturn'])->name('client.order.vnpay-return');

        // ZaloPay Payment
        Route::post('/zalopay-callback', [ClientOrderController::class, 'zalopayCallback'])->name('client.order.zalopay-callback');

        // PayPal Payment
        Route::get('/paypal-success', [ClientOrderController::class, 'paypalSuccess'])->name('client.order.paypal-success');
        Route::get('/paypal-cancel', [ClientOrderController::class, 'paypalCancel'])->name('client.order.paypal-cancel');
    });

    // Coupon routes
    Route::post('/apply-coupon', [ClientOrderController::class, 'applyCoupon'])->name('client.apply-coupon');
});

//Blog (post)
Route::get('/blog', [ClientPostController::class, 'index'])->name('client.blog');
Route::get('/blog/{id}', [ClientPostController::class, 'show'])->name('client.posts.show');

// Favorites
Route::get('/', [\App\Http\Controllers\Client\FavoriteController::class, 'index'])->name('client.home');

// Cart routes
Route::middleware(['auth'])->group(function () {
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('client.add-to-cart');
    Route::post('/update-cart', [CartController::class, 'updateCart'])->name('client.update-cart');
    Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('client.remove-from-cart');
    Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('client.cart-count');
    Route::get('/variant-stock', [CartController::class, 'getVariantStock'])->name('client.variant-stock');
});

// ================= Authentication =================
Route::get('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('login', [AuthenticationController::class, 'postLogin'])->name('postLogin');
Route::get('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('register', [AuthenticationController::class, 'postRegister'])->name('postRegister');
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('auth/google', [AuthenticationController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthenticationController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Password Reset
Route::get('forgot-password', fn() => view('auth.forgot-password'))->middleware('guest')->name('password.request');
Route::post('forgot-password', [AuthenticationController::class, 'sendResetLink'])->middleware('guest')->name('password.email');
Route::get('reset-password/{token}', fn(string $token) => view('auth.reset-password', ['token' => $token]))->middleware('guest')->name('password.reset');
Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])->middleware('guest')->name('password.update');

// ================= Admin Routes =================
Route::prefix('admin')->middleware(['admin.access'])->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('admin.dashboard')->middleware('permission:dashboard.view');
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard')->middleware('permission:dashboard.view');

    // Category
    Route::prefix('category')->middleware([])->group(function () {
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

        Route::fallback(function () {
            return view('admin.404');
        });
    });
    //order
    Route::group(['prefix' => 'order'], function () {
        // Đơn hàng bị xoá mềm
        Route::get('/trash', [OrderController::class, 'trash'])->name('admin.order.trash');
        Route::post('/restore/{id}', [OrderController::class, 'restore'])->name('admin.order.restore');
        Route::delete('/force-delete/{id}', [OrderController::class, 'forceDelete'])->name('admin.order.force-delete');

        // CRUD đơn hàng
        Route::get('/', [OrderController::class, 'index'])->name('admin.order.index');
        Route::post('/store', [OrderController::class, 'store'])->name('admin.order.store');
        Route::get('/create', [OrderController::class, 'create'])->name('admin.order.create');
        Route::put('/update/{id}', [OrderController::class, 'update'])->name('admin.order.update');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('admin.order.edit');
        Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('admin.order.delete');
        Route::get('/{id}', [OrderController::class, 'show'])->name('admin.order.show');
    });
    // User
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index')->middleware('permission:user.view');
        Route::get('/{id}', [UserController::class, 'show'])->name('admin.users.show')->middleware('permission:user.view');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware('permission:user.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('admin.users.update')->middleware('permission:user.edit');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy')->middleware('permission:user.delete');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status')->middleware('permission:user.edit');
        Route::get('/{id}/roles', [UserController::class, 'roles'])->name('admin.users.roles')->middleware('permission:user.manage_roles');
        Route::put('/{id}/roles', [UserController::class, 'updateRoles'])->name('admin.users.update-roles')->middleware('permission:user.manage_roles');
        Route::get('/{id}/permissions', [UserController::class, 'permissions'])->name('admin.users.permissions')->middleware('permission:user.view');
    });

    // Roles & Permissions
    Route::resource('roles', RoleController::class, ['as' => 'admin'])->middleware('permission:role.view,role.create,role.edit,role.delete');
    Route::prefix('roles')->group(function () {
        Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->name('admin.roles.permissions')->middleware('permission:role.manage_permissions');
        Route::put('/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('admin.roles.update-permissions')->middleware('permission:role.manage_permissions');

        Route::fallback(function () {
            return view('admin.404');
        });
    });
    Route::resource('permissions', PermissionController::class, ['as' => 'admin'])->middleware('permission:permission.view,permission.create,permission.edit,permission.delete');
    Route::prefix('permissions')->group(function () {
        Route::get('/bulk-create', [PermissionController::class, 'bulkCreate'])->name('admin.permissions.bulk-create')->middleware('permission:permission.create');
        Route::post('/bulk-store', [PermissionController::class, 'bulkStore'])->name('admin.permissions.bulk-store')->middleware('permission:permission.create');

        Route::fallback(function () {
            return view('admin.404');
        });
    });

    // Product
    Route::prefix('product')->group(function () {
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
        Route::get('/{id}', [ProductController::class, 'ShowProduct'])->name('admin.product.show-product');

        Route::fallback(function () {
            return view('admin.404');
        });
    });

    Route::prefix('about')->group(function () {
        Route::get('/', [AboutController::class, 'index'])->name('admin.about.index');
        Route::get('/create', [AboutController::class, 'create'])->name('admin.about.create');
        Route::post('/store', [AboutController::class, 'store'])->name('admin.about.store');
        Route::get('/edit', [AboutController::class, 'edit'])->name('admin.about.edit');
        Route::post('/update', [AboutController::class, 'update'])->name('admin.about.update');
    });

    // Route fallback khi không khớp bất kỳ route nào
    Route::fallback(function () {
        return view('admin.404');
    });

    // Banner
    Route::prefix('banner')->group(function () {
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

        Route::fallback(function () {
            return view('admin.404');
        });
    });

    // Chỉnh sửa nội dung trang chủ
    Route::prefix('features')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])->name('admin.features.index');       // Danh sách
        Route::get('/create', [FeatureController::class, 'create'])->name('admin.features.create'); // Form thêm
        Route::post('/store', [FeatureController::class, 'store'])->name('admin.features.store');   // Xử lý thêm
        Route::get('/edit/{id}', [FeatureController::class, 'edit'])->name('admin.features.edit');  // Form sửa
        Route::put('/update/{id}', [FeatureController::class, 'update'])->name('admin.features.update'); // Xử lý sửa
        Route::delete('/delete/{id}', [FeatureController::class, 'destroy'])->name('admin.features.destroy'); // Xoá
    });

    // Contact
    Route::prefix('contacts')->controller(ContactController::class)->name('contacts.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/show', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');

        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/replied', 'replied')->name('replied');
        Route::delete('{id}/destroy',  'destroy')->name('destroy');
    });


    // Favorites
    Route::prefix('favorite')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('admin.favorite.index');
        Route::get('/create', [FavoriteController::class, 'create'])->name('admin.favorite.create'); // Hiển thị form thêm
        Route::post('/add', [FavoriteController::class, 'store'])->name('admin.favorite.store');     // Xử lý thêm mới
        Route::get('/user/{user_id}', [FavoriteController::class, 'userFavorites'])->name('admin.favorite.user');
        Route::delete('/remove', [FavoriteController::class, 'destroy'])->name('admin.favorite.destroy');
    });


    /*** Comment */

    Route::get('comment', [CommentController::class, 'index'])->name('admin.comment.index-comment');
    Route::get('comment/product/{id}', [CommentController::class, 'showCommentsByProduct'])->name('admin.comment.by-product');
    Route::put('/admin/comment/{id}/toggle-status', [CommentController::class, 'toggleStatus'])->name('admin.comment.toggle-status');

    // Coupon
    Route::prefix('coupon')->group(function () {
        Route::get('/trash', [CouponController::class, 'TrashCoupon'])->name('admin.coupon.trash');
        Route::post('/restore/{id}', [CouponController::class, 'RestoreCoupon'])->name('admin.coupon.restore');
        Route::delete('/force-delete/{id}', [CouponController::class, 'ForceDeleteCoupon'])->name('admin.coupon.force-delete');
        Route::get('/', [CouponController::class, 'GetAllCoupon'])->name('admin.coupon.index');
        Route::post('/store', [CouponController::class, 'StoreCoupon'])->name('admin.coupon.store');
        Route::get('/create', [CouponController::class, 'CreateCoupon'])->name('admin.coupon.create');
        Route::put('/update/{id}', [CouponController::class, 'UpdateCoupon'])->name('admin.coupon.update');
        Route::get('/edit/{id}', [CouponController::class, 'EditCoupon'])->name('admin.coupon.edit');
        Route::delete('/delete/{id}', [CouponController::class, 'DeleteCoupon'])->name('admin.coupon.delete');
        Route::get('/{id}', [CouponController::class, 'ShowCoupon'])->name('admin.coupon.show');
    });

    /*** Reviews - Đánh giá */
    Route::group(['prefix' => 'reviews'], function () {
        Route::get('/', [App\Http\Controllers\Admin\ReviewController::class, 'listReview'])->name('admin.reviews.list');
        Route::patch('/update-status/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    });
    Route::fallback(function () {
        return view('admin.404');
    });

    // Post
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('admin.posts.index');
        Route::get('/create', [PostController::class, 'create'])->name('admin.posts.create');
        Route::post('/store', [PostController::class, 'store'])->name('admin.posts.store');
        Route::get('/edit/{post}', [PostController::class, 'edit'])->name('admin.posts.edit');
        Route::put('/update/{post}', [PostController::class, 'update'])->name('admin.posts.update');
        Route::delete('/delete/{post}', [PostController::class, 'destroy'])->name('admin.posts.delete');
        Route::get('/detail/{post}', [PostController::class, 'show'])->name('admin.posts.detail');
    });

    // THỐNG KÊ
    Route::get('/statistics', [StatController::class, 'index'])->name('admin.statistics.index');
    // Fallback
    Route::fallback(fn() => view('admin.404'));
});


Route::prefix('client')->name('client.')->group(
    function () {
        Route::prefix('contact')->controller(ClientContactController::class)->name('contact.')->group(function () {
            Route::get('/index', [ContactController::class, 'index'])->name('index');
            Route::post('/', 'store')->middleware('auth')->name('store');
        });
    }
);
