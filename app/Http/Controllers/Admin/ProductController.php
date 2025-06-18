<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
public function GetAllProduct(Request $request)
{
    try {
        // Khởi tạo query
        $query = Product::with('category');

        if($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)->appends($request->query());

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

            'image_variant'  => json_encode($variantImages),

        ]);

        DB::commit();

        return redirect()->route('admin.product.index-product')->with('success', 'Thêm sản phẩm thành công.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to create product: ' . $e->getMessage());
    }
}

    public function ShowProduct($id)
    {
        $category = Category::all();
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.product.detail-product', compact('product', 'category'));
    }

    public function EditProduct($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();

        return view('admin.product.edit-product', compact('product', 'categories'));
    }

public function UpdateProduct(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $product = Product::findOrFail($id);

    // Cập nhật thông tin sản phẩm
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'status' => $request->status,
    ]);

    // Cập nhật lại cột image
    $product->update(['image' => $imagePath]);
}

    // Xử lý variant
    $variant = $product->variants()->first(); // hoặc find theo ID nếu có nhiều

    if ($variant) {
        $data = [
            'variant_name' => $request->variant_name,
            'storage' => $request->storage,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'color_id' => $request->color_id,
            'storage_id' => $request->storage_id,
        ];

        // Nếu có ảnh mới cho variant
        if ($request->hasFile('image_variant')) {
            // Xóa ảnh cũ nếu có
            if ($variant->image_variant && Storage::disk('public')->exists($variant->image_variant)) {
                Storage::disk('public')->delete($variant->image_variant);
            }

            // Lưu ảnh mới
            $imagePath = $request->file('image_variant')[0]->store('variants', 'public');
            $data['image_variant'] = $imagePath;
    if ($request->hasFile('image')) {
        // Xoá ảnh cũ nếu có
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Lưu ảnh mới
        $imagePath = $request->file('image')->store('products', 'public');

        // Cập nhật lại cột image
        $product->update(['image' => $imagePath]);
    }

    return redirect()->route('admin.product.index-product')->with('success', 'Cập nhật sản phẩm thành công!');
}

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Đã xoá sản phẩm thành công! Vui lòng khôi phục lại nếu cần.');
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

        public function ShowProduct($id)
    {
        $product = product::findOrFail($id);
        return view('admin.product.detail-product', compact('product'));
    }

    public function CreateProductVariant($id){
        $product = Product::findOrFail($id);
        return view('admin.product.variant.create', compact('product'));
    }

    public function StoreProductVariant(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:100',
            'image_variant.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ],[
            'product_id.required' => 'Sản phẩm không tồn tại',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'variant_name.required' => 'Tên biến thể không được để trống',
            'price.required' => 'Giá không được để trống',
            'price.numeric' => 'Giá phải là số',
            'stock_quantity.required' => 'Số lượng không được để trống',
            'stock_quantity.integer' => 'Số lượng phải là số',
            'sku.required' => 'SKU không được để trống',
            'sku.unique' => 'SKU đã tồn tại',
            'sku.max' => 'SKU không được vượt quá 255 ký tự',
            'color.max' => 'Màu sắc không được vượt quá 100 ký tự',
            'size.max' => 'Kích thước không được vượt quá 100 ký tự',
            'storage.max' => 'Bộ nhớ không được vượt quá 100 ký tự',
            'image_variant.*.image' => 'Ảnh phải là định dạng ảnh',
            'image_variant.*.mimes' => 'Ảnh phải là định dạng jpeg, jpg, png, webp',
            'image_variant.*.max' => 'Ảnh không được vượt quá 2MB',
        ],[
            "price"=>"Giá sản phẩm",
            "stock_quantity"=>"Số lượng sản phẩm",
            "sku"=>"Mã sản phẩm",
            "color"=>"Màu sắc",
            "size"=>"Kích thước",
            "storage"=>"Bộ nhớ",
            "image_variant.*"=>"Ảnh biến thể",
        ]);

        $variant = ProductVariant::create([
            'product_id' => $request->product_id,
            'variant_name' => $request->variant_name,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'color' => $request->color,
            'size' => $request->size,
            'storage' => $request->storage,
        ]);

        // Handle variant images if uploaded
        if ($request->hasFile('image_variant')) {
            $imagePaths = [];
            foreach ($request->file('image_variant') as $image) {
                $imagePath = $image->store('product-variants', 'public');
                $imagePaths[] = $imagePath;
            }
            $variant->update(['image_variant' => json_encode($imagePaths)]);
        }

        return redirect()->route('admin.product.show-product', $request->product_id)->with('success', 'Thêm biến thể sản phẩm thành công!');
    }

    public function EditProductVariant($id){
        $variant = ProductVariant::findOrFail($id);
        return view('admin.product.variant.edit', compact('variant'));
    }

    public function UpdateProductVariant(Request $request, $id){
        $request->validate([
            'variant_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $id,
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:100',
            'image_variant.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'variant_name.required' => 'Tên biến thể không được để trống',
            'price.required' => 'Giá không được để trống',
            'price.numeric' => 'Giá phải là số',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0',
            'stock_quantity.required' => 'Số lượng không được để trống',
            'stock_quantity.integer' => 'Số lượng phải là số',
            'stock_quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0',
            'sku.required' => 'SKU không được để trống',
            'sku.unique' => 'SKU đã tồn tại',
            'sku.max' => 'SKU không được vượt quá 255 ký tự',
            'color.max' => 'Màu sắc không được vượt quá 100 ký tự',
            'size.max' => 'Kích thước không được vượt quá 100 ký tự',
            'storage.max' => 'Bộ nhớ không được vượt quá 100 ký tự',
            'image_variant.*.image' => 'Ảnh phải là định dạng ảnh',
            'image_variant.*.mimes' => 'Ảnh phải là định dạng jpeg, jpg, png, webp',
            'image_variant.*.max' => 'Ảnh không được vượt quá 2MB',
        ], [
            "price" => "Giá sản phẩm",
            "stock_quantity" => "Số lượng sản phẩm",
            "sku" => "Mã sản phẩm",
            "color" => "Màu sắc",
            "size" => "Kích thước",
            "storage" => "Bộ nhớ",
            "image_variant.*" => "Ảnh biến thể",
        ]);

        $variant = ProductVariant::findOrFail($id);
        
        $variant->update([
            'variant_name' => $request->variant_name,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'color' => $request->color,
            'size' => $request->size,
            'storage' => $request->storage,
        ]);

        // Handle variant images if uploaded
        if ($request->hasFile('image_variant')) {
            $imagePaths = [];
            foreach ($request->file('image_variant') as $image) {
                $imagePath = $image->store('product-variants', 'public');
                $imagePaths[] = $imagePath;
            }
            $variant->update(['image_variant' => json_encode($imagePaths)]);
        }

        return redirect()->route('admin.product.show-product', $variant->product_id)->with('success', 'Cập nhật biến thể sản phẩm thành công!');
    }

    public function DeleteProductVariant($id){
        $variant = ProductVariant::findOrFail($id);
        $variant->delete();
        return redirect()->route('admin.product.show-product', $variant->product_id)->with('success', 'Xóa biến thể sản phẩm thành công!');
    }

    public function TrashProductVariant(){
        $variants = ProductVariant::onlyTrashed()->get();
        return view('admin.product.variant.restore', compact('variants'));
    }

    public function RestoreProductVariant($id){
        $variant = ProductVariant::onlyTrashed()->findOrFail($id);
        $variant->restore();
        return redirect()->back()->with('success', 'Khôi phục biến thể sản phẩm thành công');
    }

    public function ForceDeleteProductVariant($id){
        $variant = ProductVariant::onlyTrashed()->findOrFail($id);
        $variant->forceDelete();
        return redirect()->back()->with('success', 'Xoá vĩnh viễn biến thể sản phẩm');
    }
}
