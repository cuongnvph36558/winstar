<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Danh sách yêu thích + TOP sản phẩm phổ biến
     */
    public function index()
    {
        // danh sách bản ghi yêu thích (paginate để chia trang)
        $favorites = Favorite::with(['user', 'product'])
            ->latest()
            ->paginate(20);

        // top 10 sản phẩm có nhiều lượt yêu thích nhất (ưu tiên cả lượt xem)
        $products = Product::withCount('favorites')
            ->orderByDesc('favorites_count')
            ->orderByDesc('view')
            ->take(10)
            ->get();

        return view('admin.favorite.index', compact('favorites', 'products'));
    }

    /**
     * Form thêm mới bản ghi yêu thích
     */
    public function create()
    {
        $users    = User::all();
        $products = Product::all();
        return view('admin.favorite.create', compact('users', 'products'));
    }

    /**
     * Lưu bản ghi yêu thích
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        Favorite::firstOrCreate([
            'user_id'    => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return redirect()->route('admin.favorite.index')
            ->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    /**
     * Danh sách yêu thích của 1 user
     */
    public function userFavorites($user_id)
    {
        $favorites = Favorite::with('product')
            ->where('user_id', $user_id)
            ->latest()
            ->get();

        return view('admin.favorite.user', compact('favorites'));
    }

    /**
     * Xoá bản ghi yêu thích theo ID
     */
    public function destroy($id)
    {
        $favorite = Favorite::findOrFail($id);
        $favorite->delete();

        return redirect()->back()->with('success', 'Đã xoá sản phẩm yêu thích!');
    }
}