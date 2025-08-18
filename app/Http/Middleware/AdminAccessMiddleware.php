<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập');
        }

        $user = auth()->user();

        // Cho phép admin và staff vào admin panel
        if (!$user->hasRole('admin') && !$user->hasRole('staff')) {
            // Chỉ user thường bị chặn
            if ($user->hasRole('user')) {
                return redirect('/')->with('error', 'Người dùng không được truy cập khu vực quản trị');
            }
            
            // Các role khác
            return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực quản trị');
        }

        return $next($request);
    }
}
