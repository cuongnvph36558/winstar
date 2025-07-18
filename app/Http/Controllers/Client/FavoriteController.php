<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\{Product, Banner, Favorite};
use App\Events\FavoriteUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function getFavoriteProduct()
    {

        if (Auth::check()) {
            // Nếu user đã đăng nhập, lấy sản phẩm mà user đã yêu thích
            $favorites = Favorite::where('user_id', Auth::id())
                ->with(['product' => function ($query) {
                    $query->withCount('favorites')->with('variants');
                }])
                ->orderByDesc('created_at') // Sắp xếp theo thời gian yêu thích
                ->get();
            
            $products = $favorites->pluck('product');
        } else {
            // Nếu chưa đăng nhập, hiển thị 10 sản phẩm được yêu thích nhiều nhất
            $products = Product::withCount('favorites')
                ->with('variants')
                ->orderByDesc('favorites_count')
                ->orderByDesc('view')
                ->take(10)
                ->get();
        }

        // Trả về view client.favorite.index với dữ liệu
        return view('client.favorite.index', compact('products'));
    }

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     */
    public function addToFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $productId = $request->input('product_id');
        $user = Auth::user();
        
        // Validate product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        }
        
        // Check if already favorited
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();
            
        if ($existingFavorite) {
            return response()->json(['success' => false]);
        }
        
        // Tạo mới favorite
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);

        // Lấy favorite count mới
        $favoriteCount = Favorite::where('product_id', $productId)->count();

        // Broadcast event
        broadcast(new FavoriteUpdated($user, $product, 'added', $favoriteCount));

        return response()->json([
            'success' => true, 
            'action' => 'added',
            'message' => 'Đã thêm vào danh sách yêu thích',
            'favorite_count' => $favoriteCount
        ]);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     */
    public function removeFromFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Vui lòng đăng nhập',
                'redirect' => route('client.login'),
            ]);
        }

        $productId = $request->input('product_id');
        $user = Auth::user();
        
        // Lấy thông tin product trước khi xóa
        $product = Product::find($productId);
        
        $deleted = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            // Đếm favorite count mới sau khi xóa
            $favoriteCount = Favorite::where('product_id', $productId)->count();
            
            // Broadcast event
            broadcast(new FavoriteUpdated($user, $product, 'removed', $favoriteCount));
            
            return response()->json([
                'success' => true, 
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'favorite_count' => $favoriteCount
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không có trong danh sách yêu thích']);
        }
    }

    /**
     * Toggle favorite status (thêm nếu chưa có, xóa nếu đã có)
     */
    public function toggleFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $productId = $request->input('product_id');
        $user = Auth::user();
        $product = Product::find($productId);
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $favoriteCount = Favorite::where('product_id', $productId)->count();
            
            // Broadcast event
            broadcast(new FavoriteUpdated($user, $product, 'removed', $favoriteCount));
            
            return response()->json([
                'success' => true, 
                'action' => 'removed', 
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'favorite_count' => $favoriteCount
            ]);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            
            $favoriteCount = Favorite::where('product_id', $productId)->count();
            
            // Broadcast event
            broadcast(new FavoriteUpdated($user, $product, 'added', $favoriteCount));
            
            return response()->json([
                'success' => true, 
                'action' => 'added', 
                'message' => 'Đã thêm vào danh sách yêu thích',
                'favorite_count' => $favoriteCount
            ]);
        }
    }

    /**
     * Lấy số lượng sản phẩm yêu thích của user
     */
    public function getFavoriteCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Favorite::where('user_id', Auth::id())->count();
        
        return response()->json(['count' => $count]);
    }
}