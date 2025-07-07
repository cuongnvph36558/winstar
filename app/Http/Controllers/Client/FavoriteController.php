<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\{Product, Banner, Favorite};
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
                    $query->withCount('favorites');
                }])
                ->orderByDesc('created_at') // Sắp xếp theo thời gian yêu thích
                ->get();
            
            $products = $favorites->pluck('product');
        } else {
            // Nếu chưa đăng nhập, hiển thị 10 sản phẩm được yêu thích nhiều nhất
            $products = Product::withCount('favorites')
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
        
        // Kiểm tra xem đã tồn tại chưa
        $existingFavorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingFavorite) {
            // Nếu đã tồn tại, vẫn trả về success để tránh hiển thị lỗi
            return response()->json([
                'success' => true, 
                'action' => 'already_exists',
                'message' => 'Sản phẩm đã có trong danh sách yêu thích'
            ]);
        }
        
        // Tạo mới nếu chưa tồn tại
        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);

        return response()->json([
            'success' => true, 
            'action' => 'added',
            'message' => 'Đã thêm vào danh sách yêu thích'
        ]);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     */
    public function removeFromFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $productId = $request->input('product_id');
        
        $deleted = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Đã xóa khỏi danh sách yêu thích']);
        } else {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong danh sách yêu thích']);
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
        
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'action' => 'removed', 'message' => 'Đã xóa khỏi danh sách yêu thích']);
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            return response()->json(['success' => true, 'action' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích']);
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
