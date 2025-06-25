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
        
        // Share cart count with all views
        View::composer('*', function ($view) {
            $cartCount = 0;
            
            try {
                if (Auth::check()) {
                    // User đã đăng nhập - lấy từ database với fresh query
                    $cart = Cart::where('user_id', Auth::id())->first();
                    if ($cart) {
                        // Force fresh query to avoid cache issues
                        $cartCount = CartDetail::where('cart_id', $cart->id)
                            ->select(DB::raw('COALESCE(SUM(quantity), 0) as total'))
                            ->value('total') ?: 0;
                    }
                } else {
                    // Guest user - lấy từ session
                    $sessionCart = Session::get('cart', []);
                    if (!empty($sessionCart) && is_array($sessionCart)) {
                        $quantities = array_column($sessionCart, 'quantity');
                        $cartCount = array_sum(array_filter($quantities, 'is_numeric'));
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
