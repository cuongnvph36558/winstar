<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)->latest()->get();
        return view('client.product.list-product', compact('products'));
    }
        
    
}
