<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Kiểm tra nếu user chưa xác nhận email
            if (!$user->email_verified_at) {
                // Nếu đang ở trang xác nhận email thì cho phép
                if ($request->routeIs('verify.email') || $request->routeIs('verify.email.post') || $request->routeIs('resend.verification')) {
                    return $next($request);
                }
                
                // Nếu không phải admin/staff thì bắt buộc xác nhận email
                if (!$user->hasRole('admin') && !$user->hasRole('super_admin') && !$user->hasRole('staff')) {
                    return redirect()->route('verify.email')->with('error', 'Vui lòng xác nhận email trước khi tiếp tục');
                }
            }
        }

        return $next($request);
    }
}
