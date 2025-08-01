<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá sản phẩm
     */
    public function store(Request $request)
    {
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
            return redirect()->back()
                ->with('error', 'Bạn không có quyền đánh giá sản phẩm này hoặc đơn hàng chưa hoàn thành');
        }

        // Kiểm tra sản phẩm có trong đơn hàng không
        $orderDetail = $order->orderDetails()
            ->where('product_id', $request->product_id)
            ->first();

        if (!$orderDetail) {
            return redirect()->back()
                ->with('error', 'Sản phẩm này không có trong đơn hàng của bạn');
        }

        // Kiểm tra đã đánh giá chưa
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Bạn đã đánh giá sản phẩm này rồi');
        }

        try {
            // Tạo đánh giá mới
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'rating' => $request->rating,
                'content' => $request->content,
                'status' => 1, // Active by default
            ]);

            return redirect()->back()
                ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm! Đánh giá của bạn sẽ được hiển thị sau khi được duyệt.');

        } catch (\Exception $e) {
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