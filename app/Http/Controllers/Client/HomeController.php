<?php

namespace App\Http\Controllers\Client;

use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Feature;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\AboutPage;
use App\Models\OrderDetail;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

    public function index()
    {
        $banners = Banner::where('status', 1)
            ->orderByDesc('id')
            ->get();

        $productBestSeller = OrderDetail::with('product')
            ->whereHas('order')
            ->whereHas('product')
            ->orderByDesc('quantity')
            ->limit(8)
            ->get();

        $feature = Feature::with('items')
            ->where('status', 'active')
            ->first() ?? new \App\Models\Feature(['title' => 'Không có tiêu đề']);

        $latestPosts = Post::with('author')
            ->withCount('comments')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $mainVideo = Video::latest()->first();

        $productsFavorite = Product::withCount('favorites')
            ->orderByDesc('favorites_count')
            ->orderByDesc('view')
            ->limit(8)
            ->get();

        $services = Service::orderBy('order')->get();

        return view('client.home', compact(
            'banners',
            'productBestSeller',
            'feature',
            'latestPosts',
            'mainVideo',
            'productsFavorite',
            'services'
        ));
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

    public function profile()
    {
        if (Auth::check()) {
            $user =  Auth::user();
        }
        return view('client.profile.index')->with([
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::user()->id);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address
        ];
        $user->update($data);
        return redirect()->back();
    }
}


