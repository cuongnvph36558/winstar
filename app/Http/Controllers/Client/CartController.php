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
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        if (Auth::check()) {
            // Lấy giỏ hàng của user đã đăng nhập
            $cart = Cart::where('user_id', Auth::id())->first();
            $cartItems = $cart ? CartDetail::where('cart_id', $cart->id)
                ->with(['product', 'variant.color', 'variant.storage'])
                ->get() : collect();
        } else {
            // Lấy giỏ hàng từ session cho guest
            $cartItems = collect();
            $sessionCart = Session::get('cart', []);
            
            foreach ($sessionCart as $item) {
                $product = Product::find($item['product_id']);
                $variant = ProductVariant::find($item['variant_id']);
                
                if ($product && $variant) {
                    $cartItems->push((object)[
                        'id' => $item['id'],
                        'product' => $product,
                        'variant' => $variant,
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]);
                }
            }
        }

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
        
        // Tính tổng số lượng đã có trong giỏ hàng
        $currentCartQuantity = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $existingCartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('product_id', $request->product_id)
                    ->where('variant_id', $request->variant_id)
                    ->first();
                $currentCartQuantity = $existingCartDetail ? $existingCartDetail->quantity : 0;
            }
        } else {
            $sessionCart = Session::get('cart', []);
            $itemId = $request->product_id . '_' . $request->variant_id;
            $currentCartQuantity = isset($sessionCart[$itemId]) ? $sessionCart[$itemId]['quantity'] : 0;
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
            
            if (Auth::check()) {
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
            } else {
                // Guest user - lưu vào session
                $sessionCart = Session::get('cart', []);
                $itemId = $request->product_id . '_' . $request->variant_id;
                
                $newQuantity = $request->quantity;
                
                if (isset($sessionCart[$itemId])) {
                    // Kiểm tra tổng số lượng sau khi cộng thêm
                    $newQuantity = $sessionCart[$itemId]['quantity'] + $request->quantity;
                    
                    // Kiểm tra không vượt quá stock
                    if ($newQuantity > $variant->stock_quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tổng số lượng trong giỏ hàng sẽ vượt quá kho! Bạn đã có ' . 
                                       $sessionCart[$itemId]['quantity'] . ' sản phẩm, còn lại trong kho: ' . 
                                       ($variant->stock_quantity - $sessionCart[$itemId]['quantity']) . ' sản phẩm.'
                        ], 400);
                    }
                    
                    // Kiểm tra giới hạn tối đa
                    if ($newQuantity > 100) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không thể thêm quá 100 sản phẩm cùng loại vào giỏ hàng!'
                        ], 400);
                    }
                    
                    $sessionCart[$itemId]['quantity'] = $newQuantity;
                    $sessionCart[$itemId]['price'] = $variant->price; // Cập nhật giá mới nhất
                } else {
                    $sessionCart[$itemId] = [
                        'id' => $itemId,
                        'product_id' => $request->product_id,
                        'variant_id' => $request->variant_id,
                        'quantity' => $request->quantity,
                        'price' => $variant->price
                    ];
                }
                
                Session::put('cart', $sessionCart);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!'
            ], 500);
        }

        // Lưu thông báo vào session để hiển thị trong cart page
        Session::flash('cart_success', 'Đã thêm sản phẩm "' . $product->name . '" vào giỏ hàng thành công!');
        
        // Lấy thông tin stock và cart quantity mới nhất sau khi thêm
        $variant->refresh();
        $newCartQuantity = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $newCartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('product_id', $request->product_id)
                    ->where('variant_id', $request->variant_id)
                    ->first();
                $newCartQuantity = $newCartDetail ? $newCartDetail->quantity : 0;
            }
        } else {
            $sessionCart = Session::get('cart', []);
            $itemId = $request->product_id . '_' . $request->variant_id;
            $newCartQuantity = isset($sessionCart[$itemId]) ? $sessionCart[$itemId]['quantity'] : 0;
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
        $request->validate([
            'cart_detail_id' => 'required',
            'quantity' => 'required|integer|min:1|max:100'
        ], [
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'quantity.max' => 'Số lượng không được vượt quá 100.'
        ]);

        if (Auth::check()) {
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
        } else {
            $sessionCart = Session::get('cart', []);
            $itemId = $request->cart_detail_id;
            
            if (isset($sessionCart[$itemId])) {
                // Lấy thông tin variant để kiểm tra stock
                $variant = ProductVariant::find($sessionCart[$itemId]['variant_id']);
                if ($variant && $request->quantity > $variant->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Không đủ hàng trong kho! Chỉ còn {$variant->stock_quantity} sản phẩm có sẵn.",
                        'max_quantity' => $variant->stock_quantity,
                        'current_stock' => $variant->stock_quantity
                    ], 400);
                }

                $sessionCart[$itemId]['quantity'] = $request->quantity;
                Session::put('cart', $sessionCart);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật giỏ hàng!',
                    'current_stock' => $variant ? $variant->stock_quantity : 0
                ]);
            }
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
        $request->validate([
            'cart_detail_id' => 'required'
        ]);

        if (Auth::check()) {
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
        } else {
            $sessionCart = Session::get('cart', []);
            $itemId = $request->cart_detail_id;
            
            if (isset($sessionCart[$itemId])) {
                unset($sessionCart[$itemId]);
                Session::put('cart', $sessionCart);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể xóa sản phẩm!'
        ]);
    }

    /**
     * Đếm số lượng item trong giỏ hàng
     */
    public function getCartCount()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            $count = $cart ? CartDetail::where('cart_id', $cart->id)->sum('quantity') : 0;
        } else {
            $sessionCart = Session::get('cart', []);
            $count = array_sum(array_column($sessionCart, 'quantity'));
        }

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

        // Lấy số lượng hiện có trong giỏ hàng
        $currentCartQuantity = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $existingCartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('variant_id', $request->variant_id)
                    ->first();
                $currentCartQuantity = $existingCartDetail ? $existingCartDetail->quantity : 0;
            }
        } else {
            $sessionCart = Session::get('cart', []);
            $itemId = $variant->product_id . '_' . $request->variant_id;
            $currentCartQuantity = isset($sessionCart[$itemId]) ? $sessionCart[$itemId]['quantity'] : 0;
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
