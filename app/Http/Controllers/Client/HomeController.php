<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.home');
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
    public function product()
    {
        return view('client.product.list-product');
    }
    public function singleProduct($id)
    {
        return view('client.product.single-product');
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
