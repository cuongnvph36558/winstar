<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Feature;

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

    $feature = Feature::with('items')->first(); // Thêm dòng này

    return view('client.home', compact('products', 'banners', 'feature'));
}
}