<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireAuthForPurchase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            // Nếu là AJAX request, trả về JSON với redirect URL
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để tiếp tục!',
                    'redirect_to_login' => true,
                    'login_url' => route('login')
                ], 401);
            }

            // Nếu là form submit thông thường, redirect đến login với thông báo
            session()->flash('error', 'Vui lòng đăng nhập để thực hiện chức năng này!');
            return redirect()->route('login');
        }

        return $next($request);
    }
} 