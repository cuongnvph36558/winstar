<?php

namespace App\Http\Controllers\Client;

use App\Models\Color;
use App\Models\Review;
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

        // Lấy khoảng giá min/max cho slider DỰA TRÊN GIÁ THẤP NHẤT của mỗi sản phẩm
        $allProductsQuery = Product::where('status', 1);

        // Lấy tất cả các product_id có trong query hiện tại (trước khi phân trang)
        $productIds = (clone $query)->pluck('products.id');

        // Lấy giá thấp nhất của mỗi sản phẩm trong danh sách đã lọc
        $minPricesOfProducts = ProductVariant::whereIn('product_id', $productIds)
            ->selectRaw('product_id, MIN(price) as min_price')
            ->groupBy('product_id')
            ->pluck('min_price');

        // Tính min và max của các giá thấp nhất đó
        $minPrice = $minPricesOfProducts->min() ?? 0;
        $maxPrice = $minPricesOfProducts->max() ?? 100000000;

        return view('client.product.list-product', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }
    
    public function detailProduct($id)
    {
        $product = Product::findOrFail($id);

        $variant = ProductVariant::where('product_id', $product->id)->first();
        $variantStorages = Storage::whereIn('id', ProductVariant::where('product_id', $product->id)->pluck('storage_id'))->get();
        $variantColors = Color::whereIn('id', ProductVariant::where('product_id', $product->id)->pluck('color_id'))->get();
        $productAsCategory = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->where('status', 1)->get();

        // Lấy đánh giá với thông tin user và sắp xếp theo thời gian mới nhất
        $reviews = Review::where('product_id', $product->id)
            ->where('status', 1)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Tính toán rating trung bình
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        // Thống kê rating theo từng mức
        $ratingStats = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        return view('client.product.single-product', compact(
            'product',
            'variant',
            'variantStorages',
            'variantColors',
            'productAsCategory',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingStats'
        ));
    }

    public function addReview(Request $request, $id)
    {
        try {
            // Validate dữ liệu
            $request->validate([
                'rating' => 'required|integer|between:1,5',
                'content' => 'required|string|max:1000',
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'status' => 'nullable|integer|between:0,1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'rating.required' => 'Vui lòng chọn số sao đánh giá',
                'rating.between' => 'Đánh giá phải từ 1 đến 5 sao',
                'content.required' => 'Vui lòng nhập nội dung đánh giá',
                'content.max' => 'Nội dung đánh giá không được quá 1000 ký tự',
                'name.max' => 'Tên không được quá 255 ký tự',
                'email.email' => 'Email không đúng định dạng',
                'email.max' => 'Email không được quá 255 ký tự',
                'status.between' => 'Trạng thái phải là 0 hoặc 1',
                'image.image' => 'Hình ảnh phải là định dạng ảnh',
                'image.mimes' => 'Hình ảnh phải là định dạng jpeg, png, jpg, gif, svg',
                'image.max' => 'Hình ảnh không được quá 2MB',
            ]);

            // Kiểm tra sản phẩm tồn tại
            $product = Product::findOrFail($id);

            // Kiểm tra user đã đăng nhập chưa
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thêm đánh giá!',
                    'redirect_to_login' => true,
                    'login_url' => route('login')
                ], 401);
            }

            $user = auth()->user();

            // Kiểm tra user đã đánh giá sản phẩm này chưa
            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đánh giá sản phẩm này rồi! Chỉ có thể đánh giá một lần cho mỗi sản phẩm.'
                ], 400);
            }

            // Xử lý upload ảnh nếu có
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/reviews', $fileName);
                $imagePath = 'reviews/' . $fileName;
            }

            // Tạo đánh giá mới
            Review::create([
                'user_id' => $user->id,
                'product_id' => $id,
                'name' => $request->name ?: $user->name,
                'email' => $request->email ?: $user->email,
                'rating' => $request->rating,
                'content' => $request->content,
                'image' => $imagePath,
                'status' => 0 // Mặc định là chưa duyệt
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá của bạn đã được thêm thành công!',
                'redirect' => route('client.single-product', $id)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding review: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm đánh giá. Vui lòng thử lại!'
            ], 500);
        }
    }
}
