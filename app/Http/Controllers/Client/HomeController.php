<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 1)->orderBy('id', 'desc')->get();
        return view('client.home', compact('banners'));
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

    public function profile() {
        if(Auth::check()) {
            $user =  Auth::user();
        }
        return view('client.profile.index')->with([
            'user' => $user
        ]);
    }
}
