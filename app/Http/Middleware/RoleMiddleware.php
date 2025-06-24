<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này');
        }

        $user = auth()->user();

        // Kiểm tra user có ít nhất một role được yêu cầu không
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
        return $next($request);
            }
        }

        // Nếu không có quyền, trả về 403
        abort(403, 'Bạn không có quyền truy cập trang này');
    }
}
