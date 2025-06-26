<?php

namespace App\Http\Controllers\Client;

use App\Models\Color;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function product(Request $request)
    {   
        $query = Product::with(['category', 'variants'])->where('status', 1);
        
        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // Tìm kiếm theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Tìm kiếm theo khoảng giá (trong ProductVariant)
        $minPriceFilter = $request->min_price;
        $maxPriceFilter = $request->max_price;
        
        if (($minPriceFilter !== null && $minPriceFilter >= 0) || ($maxPriceFilter !== null && $maxPriceFilter > 0)) {
            $query->whereHas('variants', function($variantQuery) use ($minPriceFilter, $maxPriceFilter) {
                if ($minPriceFilter !== null && $minPriceFilter >= 0) {
                    $variantQuery->where('price', '>=', $minPriceFilter);
                }
                if ($maxPriceFilter !== null && $maxPriceFilter > 0) {
                    $variantQuery->where('price', '<=', $maxPriceFilter);
                }
            });
        }
        
        // Lấy dữ liệu với phân trang
        $products = $query->latest()->paginate(12);
        
        // Lấy danh sách categories cho dropdown
        $categories = Category::all();
        
        // Lấy khoảng giá min/max cho slider
        $priceRange = ProductVariant::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $minPrice = $priceRange->min_price ?? 0;
        $maxPrice = $priceRange->max_price ?? 100000000;
        
        return view('client.product.list-product', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }
    public function detailProduct($id)
    {
        $product = Product::findOrFail($id);
        
        $variant = ProductVariant::where('product_id', $product->id)->first();
        $variantStorages = Storage::whereIn('id', ProductVariant::where('product_id', $product->id)->pluck('storage_id'))->get();
        $variantColors = Color::whereIn('id', ProductVariant::where('product_id', $product->id)->pluck('color_id'))->get();
        $productAsCategory = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->where('status', 1)->get();
        
        return view('client.product.single-product', compact('product', 'variant', 'variantStorages', 'variantColors', 'productAsCategory'));
    }
    
}
