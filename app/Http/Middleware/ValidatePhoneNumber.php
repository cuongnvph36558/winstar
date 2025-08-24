<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ValidatePhoneNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only validate if phone number is present in the request
        if ($request->has('phone') && !empty($request->phone)) {
            $phone = $request->phone;
            $userId = $request->user() ? $request->user()->id : null;
            
            // Validate phone number format
            if (!preg_match('/^(0|\+84)[0-9]{9,10}$/', $phone)) {
                return back()->withErrors(['phone' => 'Số điện thoại không đúng định dạng. Vui lòng nhập số điện thoại hợp lệ.'])->withInput();
            }
            
            // Check uniqueness
            $query = User::where('phone', $phone);
            if ($userId) {
                $query->where('id', '!=', $userId);
            }
            
            if ($query->exists()) {
                return back()->withErrors(['phone' => 'Số điện thoại đã được sử dụng bởi tài khoản khác.'])->withInput();
            }
        }

        return $next($request);
    }
}
