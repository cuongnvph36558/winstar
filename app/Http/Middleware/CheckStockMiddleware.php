<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartDetail;
use Symfony\Component\HttpFoundation\Response;

class CheckStockMiddleware
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug: Log middleware execution
        \Log::info('CheckStockMiddleware executing', [
            'path' => $request->path(),
            'method' => $request->method(),
            'is_checkout' => $request->is('checkout'),
            'is_place_order' => $request->is('place-order')
        ]);

        // Chỉ kiểm tra cho các route đặt hàng
        if (!$request->is('order/*') && !$request->is('checkout') && !$request->is('place-order') && !$request->is('*/checkout') && !$request->is('*/place-order')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return $next($request);
        }

        try {
            $user = Auth::user();
            
            // Kiểm tra nếu là buy now checkout
            $tempCartId = session('temp_cart_id');
            if ($tempCartId) {
                $cart = Cart::where('id', $tempCartId)
                    ->where('user_id', $user->id)
                    ->first();
                    
                if ($cart) {
                    $cartItems = CartDetail::with(['product', 'variant'])
                        ->where('cart_id', $cart->id)
                        ->get();

                    foreach ($cartItems as $item) {
                        $stockCheck = $this->stockService->checkAvailableStock(
                            $item->product_id,
                            $item->variant_id,
                            $item->quantity
                        );

                        if (!$stockCheck['available']) {
                            $productName = $item->product->name;
                            if ($item->variant) {
                                $productName .= " - {$item->variant->variant_name}";
                            }
                            return redirect()->route('client.cart')
                                ->with('error', "Sản phẩm '{$productName}' {$stockCheck['message']}. Vui lòng kiểm tra lại giỏ hàng.");
                        }
                    }
                }
            } else {
                // Kiểm tra giỏ hàng thường
                $cart = Cart::where('user_id', $user->id)->first();
                if ($cart) {
                    $cartItems = CartDetail::with(['product', 'variant'])
                        ->where('cart_id', $cart->id)
                        ->get();

                    foreach ($cartItems as $item) {
                        $stockCheck = $this->stockService->checkAvailableStock(
                            $item->product_id,
                            $item->variant_id,
                            $item->quantity
                        );

                        if (!$stockCheck['available']) {
                            $productName = $item->product->name;
                            if ($item->variant) {
                                $productName .= " - {$item->variant->variant_name}";
                            }
                            return redirect()->route('client.cart')
                                ->with('error', "Sản phẩm '{$productName}' {$stockCheck['message']}. Vui lòng kiểm tra lại giỏ hàng.");
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            // Log lỗi nhưng không chặn request
            \Log::warning('Stock check middleware error: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $next($request);
    }
}
