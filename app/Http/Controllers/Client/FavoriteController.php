<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Banner;

class FavoriteController extends Controller
{
    public function index()
    {
        // Lấy 10 sản phẩm được yêu thích nhất, sắp xếp theo lượt yêu thích và lượt xem
        $products = Product::withCount('favorites')
            ->orderByDesc('favorites_count')
            ->orderByDesc('view')
            ->take(10)
            ->get()
            ->filter(); // Lọc các sản phẩm null nếu có

        // Lấy danh sách banner sắp xếp theo id mới nhất (nếu không có cột position)
        $banners = Banner::orderByDesc('id')->get();

        // Trả về view client.home với dữ liệu
        return view('client.home', compact('products', 'banners'));
    }
}