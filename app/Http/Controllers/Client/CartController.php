<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        // Bắt buộc đăng nhập để xem giỏ hàng
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng!');
        }

        // Lấy giỏ hàng của user đã đăng nhập
        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart ? CartDetail::where('cart_id', $cart->id)
            ->with(['product', 'variant.color', 'variant.storage'])
            ->get() : collect();

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $shipping = $subtotal > 0 ? 30000 : 0; // 30,000đ phí ship
        $total = $subtotal + $shipping;

        return view('client.cart-checkout.cart', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request)
    {
        // Kiểm tra đăng nhập trước
        if (!Auth::check()) {
            // Nếu là AJAX request, trả về JSON với redirect URL
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
                    'redirect_to_login' => true,
                    'login_url' => route('login')
                ], 401);
            }
            
            // Nếu là form submit thông thường, redirect đến login
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!');
        }

        // Validate cơ bản
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100'
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'variant_id.required' => 'Vui lòng chọn phiên bản sản phẩm.',
            'variant_id.exists' => 'Phiên bản sản phẩm không tồn tại.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'quantity.max' => 'Số lượng không được vượt quá 100.'
        ]);

        // Lấy sản phẩm và variant
        $product = Product::where('id', $request->product_id)
                          ->where('status', 1)
                          ->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh!'
            ], 404);
        }

        $variant = ProductVariant::where('id', $request->variant_id)
                                 ->where('product_id', $request->product_id)
                                 ->whereNull('deleted_at')
                                 ->first();
        
        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên bản sản phẩm không tồn tại hoặc không thuộc sản phẩm này!'
            ], 404);
        }

        // Refresh variant để lấy stock mới nhất (tránh race condition)
        $variant->refresh();
        
        // Tính tổng số lượng đã có trong giỏ hàng (chỉ cho user đã đăng nhập)
        $currentCartQuantity = 0;
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $existingCartDetail = CartDetail::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();
            $currentCartQuantity = $existingCartDetail ? $existingCartDetail->quantity : 0;
        }
        
        // Tính tổng số lượng sau khi thêm mới
        $totalQuantityAfterAdd = $currentCartQuantity + $request->quantity;
        
        // Kiểm tra stock quantity với số lượng hiện tại
        if ($variant->stock_quantity < $totalQuantityAfterAdd) {
            $availableQuantity = max(0, $variant->stock_quantity - $currentCartQuantity);
            return response()->json([
                'success' => false,
                'message' => $availableQuantity > 0 
                    ? "Không đủ hàng trong kho! Bạn đã có {$currentCartQuantity} sản phẩm trong giỏ. Chỉ có thể thêm tối đa {$availableQuantity} sản phẩm nữa."
                    : "Không đủ hàng trong kho! Bạn đã có {$currentCartQuantity} sản phẩm trong giỏ, không thể thêm thêm.",
                'current_stock' => $variant->stock_quantity,
                'cart_quantity' => $currentCartQuantity,
                'available_to_add' => $availableQuantity
            ], 400);
        }

        // Sử dụng database transaction
        try {
            DB::beginTransaction();
            
            // User đã đăng nhập - lưu vào database
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            
            $cartDetail = CartDetail::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();

            $newQuantity = $request->quantity;
            
            if ($cartDetail) {
                // Kiểm tra tổng số lượng sau khi cộng thêm
                $newQuantity = $cartDetail->quantity + $request->quantity;
                
                // Kiểm tra không vượt quá stock
                if ($newQuantity > $variant->stock_quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng trong giỏ hàng sẽ vượt quá kho! Bạn đã có ' . 
                                   $cartDetail->quantity . ' sản phẩm, còn lại trong kho: ' . 
                                   ($variant->stock_quantity - $cartDetail->quantity) . ' sản phẩm.'
                    ], 400);
                }
                
                // Kiểm tra giới hạn tối đa mỗi user (100 sản phẩm)
                if ($newQuantity > 100) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể thêm quá 100 sản phẩm cùng loại vào giỏ hàng!'
                    ], 400);
                }
                
                // Cập nhật số lượng
                $cartDetail->quantity = $newQuantity;
                $cartDetail->price = $variant->price; // Cập nhật giá mới nhất
                $cartDetail->save();
            } else {
                // Thêm sản phẩm mới vào giỏ
                CartDetail::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                    'price' => $variant->price
                ]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!'
            ], 500);
        }

        // Lấy thông tin stock và cart quantity mới nhất sau khi thêm
        $variant->refresh();
        $newCartQuantity = 0;
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $newCartDetail = CartDetail::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();
            $newCartQuantity = $newCartDetail ? $newCartDetail->quantity : 0;
        }
        
        // Kiểm tra nếu là fallback submission (không phải AJAX)
        if ($request->has('fallback_submit') || !$request->ajax()) {
            // Normal form submission - redirect directly
            return redirect()->route('client.cart');
        }
        
        // AJAX response
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'redirect' => route('client.cart'),
            'stock_info' => [
                'current_stock' => $variant->stock_quantity,
                'cart_quantity' => $newCartQuantity,
                'available_to_add' => max(0, $variant->stock_quantity - $newCartQuantity)
            ]
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCart(Request $request)
    {
        // Bắt buộc đăng nhập
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để cập nhật giỏ hàng!',
                'redirect_to_login' => true,
                'login_url' => route('login')
            ], 401);
        }

        $request->validate([
            'cart_detail_id' => 'required',
            'quantity' => 'required|integer|min:1|max:100'
        ], [
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'quantity.max' => 'Số lượng không được vượt quá 100.'
        ]);

        $cartDetail = CartDetail::where('id', $request->cart_detail_id)
            ->whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('variant')
            ->first();

        if ($cartDetail) {
            // Kiểm tra stock quantity
            $variant = $cartDetail->variant;
            if ($request->quantity > $variant->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Không đủ hàng trong kho! Chỉ còn {$variant->stock_quantity} sản phẩm có sẵn.",
                    'max_quantity' => $variant->stock_quantity,
                    'current_stock' => $variant->stock_quantity
                ], 400);
            }

            $cartDetail->quantity = $request->quantity;
            $cartDetail->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng!',
                'current_stock' => $variant->stock_quantity
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể cập nhật giỏ hàng!'
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart(Request $request)
    {
        // Bắt buộc đăng nhập
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng!',
                'redirect_to_login' => true,
                'login_url' => route('login')
            ], 401);
        }

        $request->validate([
            'cart_detail_id' => 'required'
        ]);

        $cartDetail = CartDetail::where('id', $request->cart_detail_id)
            ->whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if ($cartDetail) {
            $cartDetail->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể xóa sản phẩm!'
        ]);
    }

    /**
     * Đếm số loại sản phẩm khác nhau trong giỏ hàng (không phải tổng số lượng)
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        // Đếm số loại sản phẩm khác nhau (distinct items)
        $count = $cart ? CartDetail::where('cart_id', $cart->id)->count() : 0;

        return response()->json(['count' => $count]);
    }

    /**
     * Lấy thông tin stock và cart quantity real-time cho một variant
     */
    public function getVariantStock(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id'
        ]);

        $variant = ProductVariant::find($request->variant_id);
        
        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Variant không tồn tại'
            ], 404);
        }

        // Lấy số lượng hiện có trong giỏ hàng (chỉ cho user đã đăng nhập)
        $currentCartQuantity = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $existingCartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('variant_id', $request->variant_id)
                    ->first();
                $currentCartQuantity = $existingCartDetail ? $existingCartDetail->quantity : 0;
            }
        }

        $availableToAdd = max(0, $variant->stock_quantity - $currentCartQuantity);

        return response()->json([
            'success' => true,
            'current_stock' => $variant->stock_quantity,
            'cart_quantity' => $currentCartQuantity,
            'available_to_add' => $availableToAdd,
            'max_quantity' => min($variant->stock_quantity, 100),
            'price' => $variant->price
        ]);
    }
}
