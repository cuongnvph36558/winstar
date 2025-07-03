<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

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
        return view('client.home', compact('banners', 'productBestSeller'));
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
