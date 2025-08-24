<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function GetAllCoupon(Request $request)
    {
        $query = Coupon::withCount('couponUsers as coupon_users_count');

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('vip_level')) {
            $query->where('vip_level', $request->vip_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $coupons = $query->latest()->paginate(15);
        return view('admin.coupon.index', compact('coupons'));
    }

    public function CreateCoupon()
    {
        $defaultStartDate = now()->format('Y-m-d');
        return view('admin.coupon.create', compact('defaultStartDate'));
    }

    public function StoreCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'vip_level' => 'nullable|string|in:Bronze,Silver,Gold,Platinum,Diamond',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0|max:999999.99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_order_value' => 'nullable|numeric|min:0|max:999999999.99',
            'max_discount_value' => 'nullable|numeric|min:0|max:999999999.99',
            'usage_limit' => 'nullable|integer|min:1|max:999999',
            'usage_limit_per_user' => 'nullable|integer|min:1|max:999999',
            'exchange_points' => 'nullable|integer|min:0|max:999999',
            'validity_days' => 'nullable|integer|min:1|max:365',
            'status' => 'required|in:0,1',
        ], [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.max' => 'Mã giảm giá không được quá 255 ký tự.',
            'name.max' => 'Tên mã giảm giá không được quá 255 ký tự.',
            'description.max' => 'Mô tả không được quá 1000 ký tự.',
            'vip_level.in' => 'VIP level không hợp lệ.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Giá trị giảm là bắt buộc.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'discount_value.max' => 'Giá trị giảm không được vượt quá 999,999.99.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'min_order_value.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Đơn hàng tối thiểu phải lớn hơn 0.',
            'min_order_value.max' => 'Đơn hàng tối thiểu không được vượt quá 999,999,999.99.',
            'max_discount_value.numeric' => 'Giá trị giảm tối đa phải là số.',
            'max_discount_value.min' => 'Giá trị giảm tối đa phải lớn hơn 0.',
            'max_discount_value.max' => 'Giá trị giảm tối đa không được vượt quá 999,999,999.99.',
            'usage_limit.integer' => 'Số lần sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Số lần sử dụng phải lớn hơn 0.',
            'usage_limit.max' => 'Số lần sử dụng không được vượt quá 999,999.',
            'usage_limit_per_user.integer' => 'Số lần sử dụng/người phải là số nguyên.',
            'usage_limit_per_user.min' => 'Số lần sử dụng/người phải lớn hơn 0.',
            'usage_limit_per_user.max' => 'Số lần sử dụng/người không được vượt quá 999,999.',
            'exchange_points.integer' => 'Điểm đổi phải là số nguyên.',
            'exchange_points.min' => 'Điểm đổi phải lớn hơn hoặc bằng 0.',
            'exchange_points.max' => 'Điểm đổi không được vượt quá 999,999.',
            'validity_days.integer' => 'Số ngày hiệu lực phải là số nguyên.',
            'validity_days.min' => 'Số ngày hiệu lực phải lớn hơn 0.',
            'validity_days.max' => 'Số ngày hiệu lực không được vượt quá 365.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        // Handle empty exchange_points value
        if (empty($validated['exchange_points']) || $validated['exchange_points'] === '') {
            $validated['exchange_points'] = 0;
        }

        Coupon::create($validated);
        return redirect()->route('admin.coupon.index')->with('success', 'Tạo mã giảm giá thành công.');
    }

    public function ShowCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.detail', compact('coupon'));
    }

    public function EditCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->start_date =
            $coupon->start_date instanceof \Carbon\Carbon ?
            $coupon->start_date : \Carbon\Carbon::parse($coupon->start_date);

        $coupon->end_date =
            $coupon->end_date instanceof \Carbon\Carbon ?
            $coupon->end_date : \Carbon\Carbon::parse($coupon->end_date);

        return view('admin.coupon.edit', compact('coupon'));
    }

    public function UpdateCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'vip_level' => 'nullable|string|in:Bronze,Silver,Gold,Platinum,Diamond',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0|max:999999.99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_order_value' => 'nullable|numeric|min:0|max:999999999.99',
            'max_discount_value' => 'nullable|numeric|min:0|max:999999999.99',
            'usage_limit' => 'nullable|integer|min:1|max:999999',
            'usage_limit_per_user' => 'nullable|integer|min:1|max:999999',
            'exchange_points' => 'nullable|integer|min:0|max:999999',
            'validity_days' => 'nullable|integer|min:1|max:365',
            'status' => 'required|in:0,1',
        ], [
            'name.max' => 'Tên mã giảm giá không được quá 255 ký tự.',
            'description.max' => 'Mô tả không được quá 1000 ký tự.',
            'vip_level.in' => 'VIP level không hợp lệ.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Giá trị giảm là bắt buộc.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'discount_value.max' => 'Giá trị giảm không được vượt quá 999,999.99.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'min_order_value.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Đơn hàng tối thiểu phải lớn hơn 0.',
            'min_order_value.max' => 'Đơn hàng tối thiểu không được vượt quá 999,999,999.99.',
            'max_discount_value.numeric' => 'Giá trị giảm tối đa phải là số.',
            'max_discount_value.min' => 'Giá trị giảm tối đa phải lớn hơn 0.',
            'max_discount_value.max' => 'Giá trị giảm tối đa không được vượt quá 999,999,999.99.',
            'usage_limit.integer' => 'Số lần sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Số lần sử dụng phải lớn hơn 0.',
            'usage_limit.max' => 'Số lần sử dụng không được vượt quá 999,999.',
            'usage_limit_per_user.integer' => 'Số lần sử dụng/người phải là số nguyên.',
            'usage_limit_per_user.min' => 'Số lần sử dụng/người phải lớn hơn 0.',
            'usage_limit_per_user.max' => 'Số lần sử dụng/người không được vượt quá 999,999.',
            'exchange_points.integer' => 'Điểm đổi phải là số nguyên.',
            'exchange_points.min' => 'Điểm đổi phải lớn hơn hoặc bằng 0.',
            'exchange_points.max' => 'Điểm đổi không được vượt quá 999,999.',
            'validity_days.integer' => 'Số ngày hiệu lực phải là số nguyên.',
            'validity_days.min' => 'Số ngày hiệu lực phải lớn hơn 0.',
            'validity_days.max' => 'Số ngày hiệu lực không được vượt quá 365.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        // Handle empty exchange_points value
        if (empty($validated['exchange_points']) || $validated['exchange_points'] === '') {
            $validated['exchange_points'] = 0;
        }

        $coupon->update($validated);
        return redirect()->route('admin.coupon.index')->with('success', 'Cập nhật mã giảm giá thành công.');
    }

    public function DeleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupon.index')->with('success', 'Xóa mềm mã giảm giá thành công.');
    }

    public function TrashCoupon()
    {
        $coupons = Coupon::onlyTrashed()->paginate(10);
        return view('admin.coupon.restore', compact('coupons'));
    }

    public function RestoreCoupon($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->restore();
        return redirect()->route('admin.coupon.trash')->with('success', 'Khôi phục mã giảm giá thành công.');
    }

    public function ForceDeleteCoupon($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->forceDelete();
        return redirect()->route('admin.coupon.trash')->with('success', 'Xóa vĩnh viễn mã giảm giá thành công.');
    }
}
