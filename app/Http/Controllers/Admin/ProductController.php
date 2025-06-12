<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
public function GetAllProduct(Request $request)
{
    try {
        // Khởi tạo query
        $query = Product::with('category');

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Phân trang
        $products = $query->orderBy('id', 'desc')->paginate(10);

        // Lấy danh sách tất cả category để hiện filter
        $categories = Category::all();

        return view('admin.product.index-product', compact('products', 'categories'));

    } catch (\Exception $e) {
        Log::error('Error in GetAllProduct: ' . $e->getMessage());
        return back()->with('error', 'An error occurred while fetching products');
    }
}


    public function CreateProduct()
    {
        $categories = Category::all();
        return view('admin.product.create-product', compact('categories'));
    }

public function StoreProduct(Request $request)
{
    $request->validate([
        'name'         => 'required|string|max:255',
        'category_id'  => 'required|exists:categories,id',
        'description'  => 'nullable|string',
        'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        'variant_name'   => 'required|string|max:255',
        'price'          => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'sku'            => 'required|string|max:255',
        'image_variant'  => 'nullable|array',
        'image_variant.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    DB::beginTransaction();

    try {
        // Upload ảnh chính sản phẩm
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Tạo sản phẩm
        $product = Product::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image'       => $imagePath,
            'status'      => 1,
            'view'        => 0,
        ]);

        // Upload ảnh biến thể nếu có
        $variantImages = [];
        if ($request->hasFile('image_variant')) {
            foreach ($request->file('image_variant') as $img) {
                $variantImages[] = $img->store('product_variants', 'public');
            }
        }

        // Tạo biến thể
        ProductVariant::create([
            'product_id'     => $product->id,
            'variant_name'   => $request->variant_name,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'sku'            => $request->sku,
            'image_variant'  => json_encode($variantImages),
            'storage'        => $request->storage ?? null,
            'size'           => $request->size ?? null,
            'color'          => $request->color ?? null,
        ]);

        DB::commit();

        return redirect()->route('admin.product.index-product')->with('success', 'Thêm sản phẩm thành công.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to create product: ' . $e->getMessage());
    }
}

    // public function EditProduct($id)
    // {
    //     $product = Product::findOrFail($id);
    //     $categories = Category::all();
    //     return view('admin.product.edit-product', compact('product', 'categories'));
    // }

    // public function UpdateProduct(Request $request, $id)
    // {
    //     $product = Product::findOrFail($id);

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'category_id' => 'nullable|exists:categories,id',
    //     ]);

    //     $product->update($request->all());

    //     return redirect()->route('admin.product.index-product')->with('success', 'Cập nhật sản phẩm thành công');
    // }

    // public function ShowProduct($id)
    // {
    //     $product = Product::with('category')->findOrFail($id);
    //     return view('admin.product.detail-product', compact('product'));
    // }

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Đã xoá sản phẩm (mềm)');
    }

    public function TrashProduct()
    {
        $products = Product::onlyTrashed()->get();
        return view('admin.product.restore-product', compact('products'));
    }

    public function RestoreProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->back()->with('success', 'Khôi phục sản phẩm thành công');
    }

    public function ForceDeleteProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        return redirect()->back()->with('success', 'Xoá vĩnh viễn sản phẩm');
    }
}
