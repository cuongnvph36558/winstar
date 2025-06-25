<?php

namespace App\Http\Controllers\Client;

use App\Models\Color;
use App\Models\Product;
use App\Models\Storage;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function product()
    {
        $products = Product::where('status', 1)->latest()->paginate(12);
        return view('client.product.list-product', compact('products'));
    }
    public function detailProduct($id)
    {
        $product = Product::find($id);
        $variant = ProductVariant::where('product_id', $product->id)->first();
        $variantStorages = Storage::whereIn('id', ProductVariant::where('product_id', $product->id)->pluck('storage_id'))->get();
        $variantColors = Color::whereIn('id', ProductVariant::where('product_id', $product->id)->pluck('color_id'))->get();
        $productAsCategory = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->get();
        return view('client.product.single-product', compact('product', 'variant', 'variantStorages', 'variantColors', 'productAsCategory'));
    }
    
}
