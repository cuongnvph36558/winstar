<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use App\Models\Cart;
use App\Models\CartDetail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure pagination to use Bootstrap
        Paginator::useBootstrap();
        
        // Share cart count with all views (count distinct products, not total quantity)
        View::composer('*', function ($view) {
            $cartCount = 0;
            
            try {
                if (Auth::check()) {
                    // User đã đăng nhập - đếm số loại sản phẩm khác nhau (không phải tổng số lượng)
                    $cart = Cart::where('user_id', Auth::id())->first();
                    if ($cart) {
                        // Đếm số record trong cart_details (số loại sản phẩm khác nhau)
                        $cartCount = CartDetail::where('cart_id', $cart->id)->count();
                    }
                } else {
                    // Guest user - đếm số phần tử trong session cart (số loại sản phẩm khác nhau)
                    $sessionCart = Session::get('cart', []);
                    if (!empty($sessionCart) && is_array($sessionCart)) {
                        $cartCount = count($sessionCart);
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't break the view
                Log::warning('Cart count calculation error: ' . $e->getMessage());
                $cartCount = 0;
            }
            
            $view->with('globalCartCount', $cartCount);
        });
    }
}
