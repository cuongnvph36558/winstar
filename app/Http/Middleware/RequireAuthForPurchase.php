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
            // Debug: Log request information
            \Log::info('RequireAuthForPurchase middleware', [
                'path' => $request->path(),
                'method' => $request->method(),
                'ajax' => $request->ajax(),
                'expectsJson' => $request->expectsJson(),
                'X-Requested-With' => $request->header('X-Requested-With'),
                'Content-Type' => $request->header('Content-Type'),
                'Accept' => $request->header('Accept')
            ]);

            // Nếu là AJAX request hoặc có header X-Requested-With, trả về JSON với redirect URL
            if ($request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->header('Accept') === 'application/json') {
                \Log::info('Returning JSON response for AJAX request');
                $response = response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để tiếp tục!',
                    'redirect_to_login' => true,
                    'login_url' => route('login')
                ], 401);
                
                \Log::info('Response content:', [
                    'status' => $response->getStatusCode(),
                    'content' => $response->getContent()
                ]);
                
                return $response;
            }

            // Nếu là form submit thông thường, redirect đến login với thông báo
            \Log::info('Redirecting to login page for non-AJAX request');
            session()->flash('error', 'Vui lòng đăng nhập để thực hiện chức năng này!');
            return redirect()->route('login');
        }

        return $next($request);
    }
} 