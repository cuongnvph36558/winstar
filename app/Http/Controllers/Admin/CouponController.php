<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function GetAllCoupon(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $coupons = $query->latest()->paginate(10);
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
            'code' => 'required|string|unique:coupons',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_order_value' => 'required|numeric|min:0',
            'max_discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'usage_limit_per_user' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

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
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_order_value' => 'required|numeric|min:0',
            'max_discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'usage_limit_per_user' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

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
