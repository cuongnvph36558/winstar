<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Models\AboutPage;

class HomeController extends Controller
{

    public function index()
    {
        $banners = Banner::where('status', 1)->orderBy('id', 'desc')->get();

        $productBestSeller = OrderDetail::with('product')
            ->orderBy('quantity', 'desc')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->limit(10)
            ->get();

        $feature = Feature::with('items')->first();

        // 🔽 Thêm dòng này để lấy bài viết mới nhất
        $latestPosts = Post::with('author')
            ->withCount('comments')
            ->where('status', 1)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // 🔁 Đừng quên truyền biến xuống view
        return view('client.home', compact('banners', 'productBestSeller', 'feature', 'latestPosts'));
    }

    public function contact()
    {
        return view('client.contact.index');
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
        $about = AboutPage::first();
        return view('client.about.index', compact('about'));
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
