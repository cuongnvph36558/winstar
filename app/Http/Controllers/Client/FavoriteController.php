<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Feature;
use App\Models\Post;

class FavoriteController extends Controller
{
    public function index()
    {
        $products = Product::withCount('favorites')
            ->orderByDesc('favorites_count')
            ->orderByDesc('view')
            ->take(10)
            ->get()
            ->filter();

        $banners = Banner::orderByDesc('id')->get();

        $feature = Feature::with('items')->first();

        $latestPosts = Post::with('author')
            ->withCount('comments')
            ->where('status', 1)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $productBestSeller = Product::orderByDesc('sold')
            ->take(8)
            ->get();

        return view('client.home', compact('products', 'banners', 'feature', 'latestPosts', 'productBestSeller'));
    }
}