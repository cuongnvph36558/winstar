<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;

class CouponUserController extends Controller
{
public function index()
{
    $couponUsers = CouponUser::with(['user', 'coupon'])->latest()->paginate(20);
    return view('admin.coupon-users.index', compact('couponUsers'));
}
public function user()
{
    return $this->belongsTo(User::class);
}

public function coupon()
{
    return $this->belongsTo(Coupon::class);
}
public function show($id)
{
    $couponUser = CouponUser::with(['user', 'coupon'])->findOrFail($id);
    return view('admin.coupon-users.show', compact('couponUser'));


}
}