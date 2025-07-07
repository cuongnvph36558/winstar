<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 1)->orderBy('id', 'desc')->get();
        $productBestSeller = OrderDetail::with(['product' => function($query) {
                $query->withoutTrashed(); // Chỉ lấy sản phẩm chưa bị xóa
            }, 'order'])
            ->select('order_details.*')
            ->orderBy('quantity', 'desc')
            ->whereHas('order') // Chỉ lấy order details có order
            ->whereHas('product') // Chỉ lấy order details có product tồn tại
            ->limit(8)
            ->get();
            
        // Lấy sản phẩm được yêu thích nhiều nhất
        $products = Product::withCount('favorites')
            ->orderByDesc('favorites_count')
            ->orderByDesc('view')
            ->where('status', 1) // Chỉ lấy sản phẩm đang hoạt động
            ->limit(8)
            ->get();
            
        return view('client.home', compact('banners', 'productBestSeller', 'products'));
    }

    public function blog()
    {
        return view('client.blog.list-blog');
    }

    public function loginRegister()
    {
        return view('client.auth.login-register');
    }

    public function about()
    {
        return view('client.about.index');
    }

    public function cart()
    {
        return view('client.cart-checkout.cart');
    }

    public function checkout()
    {
        return view('client.cart-checkout.checkout');
    }
}
