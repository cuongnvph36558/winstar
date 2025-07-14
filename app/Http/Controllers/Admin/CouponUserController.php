<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponUserController extends Controller
{
public function index()
{
    $couponUsers = CouponUser::with(['user', 'coupon'])->latest()->paginate(20);
    return view('admin.coupon-users.index', compact('couponUsers'));
}


}
