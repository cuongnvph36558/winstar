<?php

namespace App\Http\Controllers\Client;

use App\Models\Color;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function product(Request $request)
    {   
        // Debug: Log request parameters for debugging (commented out to reduce log spam)
        // Log::info('=== PRODUCT SEARCH DEBUG ===');
        // Log::info('Request params:', $request->all());
        
        $query = Product::with(['category', 'variants'])->where('status', 1);
        
        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // Tìm kiếm theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Tìm kiếm theo khoảng giá
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }
        
        // Sắp xếp theo giá
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low_high':
                $query->leftJoin('product_variants as pv_sort', 'products.id', '=', 'pv_sort.product_id')
                      ->selectRaw('products.*, MIN(pv_sort.price) as min_price')
                      ->groupBy('products.id', 'products.name', 'products.category_id', 'products.image', 'products.status', 'products.created_at', 'products.updated_at')
                      ->orderBy('min_price', 'asc');
                break;
            case 'price_high_low':
                $query->leftJoin('product_variants as pv_sort', 'products.id', '=', 'pv_sort.product_id')
                      ->selectRaw('products.*, MAX(pv_sort.price) as max_price')
                      ->groupBy('products.id', 'products.name', 'products.category_id', 'products.image', 'products.status', 'products.created_at', 'products.updated_at')
                      ->orderBy('max_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // 'latest'
                $query->latest();
                break;
        }
        
        // Lấy dữ liệu với phân trang - KHÔNG gọi latest() nữa để tránh ghi đè sort
        $products = $query->paginate(12);
        
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
