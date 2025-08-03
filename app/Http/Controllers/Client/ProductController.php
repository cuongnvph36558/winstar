<?php

namespace App\Http\Controllers\Client;

use App\Models\Color;
use App\Models\Review;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function product(Request $request)
    {
        $query = Product::with(['category', 'variants'])
            ->withCount('favorites')
            ->withCount(['reviews as reviews_count'])
            ->withAvg('reviews', 'rating')
            ->where('status', 1);

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Tìm kiếm theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Tìm kiếm theo khoảng giá - ưu tiên promotion_price nếu có
        if ($request->filled('min_price')) {
            $query->whereRaw('CASE 
                WHEN promotion_price IS NOT NULL AND promotion_price > 0 THEN promotion_price 
                ELSE price 
            END >= ?', [$request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('CASE 
                WHEN promotion_price IS NOT NULL AND promotion_price > 0 THEN promotion_price 
                ELSE price 
            END <= ?', [$request->max_price]);
        }

        // Lọc theo yêu thích
        if ($request->filled('filter_favorites')) {
            switch ($request->filter_favorites) {
                case 'most_favorited':
                    $query->orderBy('favorites_count', 'desc');
                    break;
                case 'least_favorited':
                    $query->orderBy('favorites_count', 'asc');
                    break;
            }
        }
        
        // Lọc theo đánh giá sao
        if ($request->filled('filter_rating')) {
            $ratingFilter = (int) $request->filter_rating;
            $query->whereHas('reviews', function($q) use ($ratingFilter) {
                $q->where('rating', '>=', $ratingFilter)
                  ->where('status', 1);
            });
        }
        
        // Lọc theo lượt xem
        if ($request->filled('filter_views')) {
            switch ($request->filter_views) {
                case 'most_viewed':
                    $query->orderBy('view', 'desc');
                    break;
                case 'least_viewed':
                    $query->orderBy('view', 'asc');
                    break;
            }
        }
        
        // Lọc theo số lượng người mua
        if ($request->filled('filter_buyers')) {
            switch ($request->filter_buyers) {
                case 'most_buyers':
                    $query->withCount(['orderDetails as buyers_count' => function($q) {
                        $q->whereHas('order', function($orderQ) {
                            $orderQ->where('status', 'completed');
                        });
                    }])->orderBy('buyers_count', 'desc');
                    break;
                case 'least_buyers':
                    $query->withCount(['orderDetails as buyers_count' => function($q) {
                        $q->whereHas('order', function($orderQ) {
                            $orderQ->where('status', 'completed');
                        });
                    }])->orderBy('buyers_count', 'asc');
                    break;
            }
        }
        
        // Lọc theo dung lượng
        if ($request->filled('filter_storage')) {
            $storageFilter = $request->filter_storage;
            $query->whereHas('variants.storage', function($q) use ($storageFilter) {
                $q->where('name', 'like', '%' . $storageFilter . '%');
            });
        }
        
        // Sắp xếp sản phẩm
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low_high':
                $query->orderByRaw('CASE 
                    WHEN promotion_price IS NOT NULL AND promotion_price > 0 THEN promotion_price 
                    ELSE price 
                END ASC');
                break;
            case 'price_high_low':
                $query->orderByRaw('CASE 
                    WHEN promotion_price IS NOT NULL AND promotion_price > 0 THEN promotion_price 
                    ELSE price 
                END DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // 'latest'
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Lấy dữ liệu với phân trang
        $products = $query->paginate(12);

        // Lấy danh sách categories cho dropdown
        $categories = Category::all();

        // Khoảng giá cố định từ 0đ đến 50 triệu
        $minPrice = 0;
        $maxPrice = 50000000;

        return view('client.product.list-product', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    public function detailProduct($id)
    {
        $product = Product::with('category')->findOrFail($id); // Gán thêm quan hệ category

        // Tăng số lượt xem lên 1
        $product->increment('view');

        $variant = ProductVariant::where('product_id', $product->id)->first();

        $variantStorages = Storage::whereIn(
            'id',
            ProductVariant::where('product_id', $product->id)->pluck('storage_id')
        )->get();

        $variantColors = Color::whereIn(
            'id',
            ProductVariant::where('product_id', $product->id)->pluck('color_id')
        )->get();

        $productAsCategory = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->get();

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

        // Kiểm tra user đã mua sản phẩm này thành công chưa
        $hasPurchased = false;
        if (auth()->check()) {
            $hasPurchased = \App\Models\Order::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereHas('orderDetails', function($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->exists();
        }

        // Đếm tổng số người đã mua sản phẩm này
        $totalBuyers = \App\Models\Order::where('status', 'completed')
            ->whereHas('orderDetails', function($query) use ($id) {
                $query->where('product_id', $id);
            })
            ->distinct('user_id')
            ->count('user_id');

        return view('client.product.single-product', compact(
            'product',
            'variant',
            'variantStorages',
            'variantColors',
            'productAsCategory',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingStats',
            'hasPurchased',
            'totalBuyers'
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

            // Kiểm tra user đã mua sản phẩm này thành công chưa
            $hasPurchased = \App\Models\Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereHas('orderDetails', function($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->exists();

            if (!$hasPurchased) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chỉ có thể đánh giá sản phẩm sau khi đã mua hàng thành công!'
                ], 403);
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
                'name' => $request->input('name') ?: $user->name,
                'email' => $request->input('email') ?: $user->email,
                'rating' => $request->input('rating'),
                'content' => $request->input('content'),
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
