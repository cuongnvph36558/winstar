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
use Illuminate\Support\Facades\Log;
use App\Events\CardUpdate;
use App\Events\UserActivity;

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

        $subtotal = $cartItems->sum(function ($item) {
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
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100'
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
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

        $variant = null;
        if ($request->variant_id) {
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
        } else {
            // Không có variant, refresh product để lấy stock mới nhất
            $product->refresh();
        }

        // Lấy hoặc tạo giỏ hàng cho user
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        // Lấy chi tiết giỏ hàng hiện tại (nếu có)
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();
        $currentCartQuantity = $cartDetail ? $cartDetail->quantity : 0;

        // Tính tổng số lượng sau khi thêm mới
        $totalQuantityAfterAdd = $currentCartQuantity + $request->quantity;
        
        // Lấy stock quantity và price dựa trên variant hoặc product
        $stockQuantity = $variant ? $variant->stock_quantity : $product->stock_quantity;
        
        // DEBUG: Log các giá trị để kiểm tra
        Log::info('Stock check debug', [
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'request_quantity' => $request->quantity,
            'current_cart_quantity' => $currentCartQuantity,
            'total_quantity_after_add' => $totalQuantityAfterAdd,
            'stock_quantity' => $stockQuantity,
            'available_quantity' => max(0, $stockQuantity - $currentCartQuantity)
        ]);
        
        if ($variant) {
            $price = ($variant->promotion_price && $variant->promotion_price > 0 && $variant->promotion_price < $variant->price)
                ? $variant->promotion_price
                : $variant->price;
        } else {
            $price = ($product->promotion_price && $product->promotion_price > 0 && $product->promotion_price < $product->price)
                ? $product->promotion_price
                : $product->price;
        }
        
        // Nếu tồn kho <= 0 thì không cho phép mua
        if ($stockQuantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng!',
                'current_stock' => $stockQuantity,
                'cart_quantity' => $currentCartQuantity,
                'available_quantity' => 0
            ], 400);
        }
        
        // Kiểm tra xem có đủ stock không
        $availableQuantity = max(0, $stockQuantity - $currentCartQuantity);
        
        if ($request->quantity > $availableQuantity) {
            return response()->json([
                'success' => false,
                'message' => $availableQuantity > 0
                    ? "Chỉ có thể thêm tối đa {$availableQuantity} sản phẩm nữa vào giỏ hàng (đã có {$currentCartQuantity} trong giỏ, tổng kho: {$stockQuantity})"
                    : "Không thể thêm sản phẩm vào giỏ hàng (đã có đủ {$currentCartQuantity} trong giỏ, tổng kho: {$stockQuantity})",
                'current_stock' => $stockQuantity,
                'cart_quantity' => $currentCartQuantity,
                'request_quantity' => $request->quantity,
                'available_quantity' => $availableQuantity
            ], 400);
        }

        // Sử dụng database transaction
        try {
            DB::beginTransaction();

            $newQuantity = $request->quantity;

            if ($cartDetail) {
                // Kiểm tra tổng số lượng sau khi cộng thêm
                $newQuantity = $cartDetail->quantity + $request->quantity;

                // Kiểm tra không vượt quá stock
                if ($newQuantity > $stockQuantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng trong giỏ hàng sẽ vượt quá kho! Bạn đã có ' . 
                                   $cartDetail->quantity . ' sản phẩm, còn lại trong kho: ' . 
                                   ($stockQuantity - $cartDetail->quantity) . ' sản phẩm.'
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
                $cartDetail->price = $price; // Cập nhật giá mới nhất
                $cartDetail->save();
            } else {
                // Thêm sản phẩm mới vào giỏ
                CartDetail::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                    'price' => $price
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[ADD TO CART ERROR] ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!',
                'error' => $e->getMessage() // GỬI RA LỖI THẬT
            ], 500);
        }

        // Lấy thông tin stock và cart quantity mới nhất sau khi thêm
        if ($variant) {
            $variant->refresh();
            $currentStock = $variant->stock_quantity;
        } else {
            $product->refresh();
            $currentStock = $product->stock_quantity;
        }
        
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
        $response = [
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'redirect' => route('client.cart'),
        ];

        $response['stock_info'] = [
            'current_stock' => $currentStock,
            'cart_quantity' => $newCartQuantity,
            'available_to_add' => max(0, $currentStock - $newCartQuantity)
        ];

        // Dispatch CardUpdate event for realtime notification
        event(new CardUpdate(Auth::user(), $product, 'added', $newCartQuantity));
        
        // Dispatch UserActivity event for admin notification
        event(new UserActivity(Auth::user(), 'add_to_cart', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $request->quantity
        ]));

        return response()->json($response);
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
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['variant', 'product'])
            ->first();

        if ($cartDetail) {
            // Kiểm tra stock quantity - ưu tiên variant, nếu không có thì dùng product
            $stockQuantity = $cartDetail->variant ? $cartDetail->variant->stock_quantity : $cartDetail->product->stock_quantity;
            
            // Nếu tồn kho <= 0 thì không cho phép cập nhật
            if ($stockQuantity <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã hết hàng hoặc tồn kho âm, không thể mua!',
                    'max_quantity' => 0,
                    'current_stock' => $stockQuantity
                ], 400);
            }
            if ($request->quantity > $stockQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Không đủ hàng trong kho! Chỉ còn {$stockQuantity} sản phẩm có sẵn.",
                    'max_quantity' => $stockQuantity,
                    'current_stock' => $stockQuantity
                ], 400);
            }

            $cartDetail->quantity = $request->quantity;
            $cartDetail->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng!',
                'current_stock' => $stockQuantity
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
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if ($cartDetail) {
            // Lưu lại thông tin product trước khi xóa
            $product = Product::find($cartDetail->product_id);
            $variantId = $cartDetail->variant_id;
            $cartDetail->delete();

            // Lấy lại số lượng sản phẩm này trong giỏ sau khi xóa
            $cart = Cart::where('user_id', Auth::id())->first();
            $newCartQuantity = 0;
            if ($cart) {
                $newCartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('product_id', $product->id)
                    ->where('variant_id', $variantId)
                    ->first();
                $newCartQuantity = $newCartDetail ? $newCartDetail->quantity : 0;
            }

            // Dispatch CardUpdate event for realtime notification
            event(new CardUpdate(Auth::user(), $product, 'removed', $newCartQuantity));

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
     * Lấy thông tin stock và cart quantity real-time cho một variant hoặc product
     */
    public function getVariantStock(Request $request)
    {
        Log::info('getVariantStock called with data', $request->all());

        try {
            $request->validate([
                'variant_id' => 'nullable|integer|exists:product_variants,id',
                'product_id' => 'required_without:variant_id|integer|exists:products,id'
            ]);
        } catch (\Exception $e) {
            Log::error('Validation failed in getVariantStock', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . $e->getMessage()
            ], 422);
        }

        $variant = null;
        $product = null;
        $stockQuantity = 0;
        $price = 0;

        if ($request->variant_id) {
            Log::info('Processing variant request for variant_id', ['variant_id' => $request->variant_id]);
            $variant = ProductVariant::find($request->variant_id);
            
            if (!$variant) {
                Log::warning('Variant not found', ['variant_id' => $request->variant_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Variant không tồn tại'
                ], 404);
            }
            
            $stockQuantity = $variant->stock_quantity;
            $price = $variant->price;
            Log::info('Variant found', ['id' => $variant->id, 'stock' => $stockQuantity, 'price' => $price]);
        } else {
            Log::info('Processing product request for product_id', ['product_id' => $request->product_id]);
            $product = Product::find($request->product_id);
            
            if (!$product) {
                Log::warning('Product not found', ['product_id' => $request->product_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại'
                ], 404);
            }
            
            $stockQuantity = $product->stock_quantity;
            $price = $product->price;
            Log::info('Product found', ['id' => $product->id, 'stock' => $stockQuantity, 'price' => $price]);
        }

        // Lấy số lượng hiện có trong giỏ hàng (chỉ cho user đã đăng nhập)
        $currentCartQuantity = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $productIdForQuery = $request->product_id ?? ($variant ? $variant->product_id : null);
                Log::info('Checking cart for', ['cart_id' => $cart->id, 'product_id' => $productIdForQuery, 'variant_id' => $request->variant_id]);
                
                $existingCartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('product_id', $productIdForQuery)
                    ->where('variant_id', $request->variant_id)
                    ->first();
                    
                $currentCartQuantity = $existingCartDetail ? $existingCartDetail->quantity : 0;
                Log::info('Cart quantity found', ['quantity' => $currentCartQuantity]);
            } else {
                Log::info('No cart found for user');
            }
        } else {
            Log::info('User not authenticated');
        }

        $availableToAdd = max(0, $stockQuantity - $currentCartQuantity);

        $response = [
            'success' => true,
            'current_stock' => $stockQuantity,
            'cart_quantity' => $currentCartQuantity,
            'available_to_add' => $availableToAdd,
            'max_quantity' => min($stockQuantity, 100),
            'price' => $price
        ];

        Log::info('getVariantStock response', $response);

        return response()->json($response);
    }
}
