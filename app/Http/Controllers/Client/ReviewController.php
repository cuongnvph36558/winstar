<?php

namespace App\Http\Controllers\Client;

use App\Events\NewReviewAdded;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá sản phẩm
     */
    public function store(Request $request)
    {
        // Debug logging
        Log::info('Review store called from order page', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'csrf_token' => $request->input('_token')
        ]);
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5',
            'content' => 'required|string|min:10|max:1000',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm để đánh giá',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'order_id.required' => 'Vui lòng cung cấp thông tin đơn hàng',
            'order_id.exists' => 'Đơn hàng không tồn tại',
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.integer' => 'Số sao phải là số nguyên',
            'rating.between' => 'Số sao phải từ 1 đến 5',
            'content.required' => 'Vui lòng nhập nội dung đánh giá',
            'content.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự',
            'content.max' => 'Nội dung đánh giá không được quá 1000 ký tự',
        ]);

        if ($validator->fails()) {
            // Return JSON response for AJAX requests
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kiểm tra quyền đánh giá
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            // Return JSON response for AJAX requests
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền đánh giá sản phẩm này hoặc đơn hàng chưa hoàn thành'
                ], 403);
            }

            return redirect()->back()
                ->with('error', 'Bạn không có quyền đánh giá sản phẩm này hoặc đơn hàng chưa hoàn thành');
        }

        // Kiểm tra sản phẩm có trong đơn hàng không
        $orderDetail = $order->orderDetails()
            ->where('product_id', $request->product_id)
            ->first();

        if (!$orderDetail) {
            // Return JSON response for AJAX requests
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm này không có trong đơn hàng của bạn'
                ], 403);
            }

            return redirect()->back()
                ->with('error', 'Sản phẩm này không có trong đơn hàng của bạn');
        }

        // Kiểm tra xem đã đánh giá sản phẩm này trong đơn hàng này chưa
        $existingReview = Review::where('user_id', Auth::id())
            ->where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            // Return JSON response for AJAX requests
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi!'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi!');
        }

        try {
            // Tạo đánh giá mới
            $review = Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'rating' => $request->rating,
                'content' => $request->content,
                'status' => 1, // Active by default
            ]);

            // Load relationships for event
            $review->load(['user', 'product']);

            // Dispatch event
            try {
                event(new NewReviewAdded($review));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast NewReviewAdded event: ' . $e->getMessage());
            }

            // Return JSON response for AJAX requests
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cảm ơn bạn đã đánh giá sản phẩm! Bạn có thể đánh giá các sản phẩm khác trong đơn hàng này.'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm! Bạn có thể đánh giá các sản phẩm khác trong đơn hàng này.');

        } catch (\Exception $e) {
            Log::error('Error creating review: ' . $e->getMessage());
            
            // Return JSON response for AJAX requests
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại.');
        }
    }

    /**
     * Hiển thị form đánh giá cho đơn hàng
     */
    public function showReviewForm(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này');
        }

        // Kiểm tra trạng thái đơn hàng
        if ($order->status !== 'completed') {
            return redirect()->route('client.order.show', $order)
                ->with('error', 'Chỉ có thể đánh giá khi đơn hàng đã hoàn thành');
        }

        return view('client.reviews.create', compact('order'));
    }
} 