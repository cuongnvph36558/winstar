<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateStatisticsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Chỉ cập nhật thống kê khi truy cập trang admin và cache đã hết hạn
        if ($request->is('admin*') && !Cache::has('statistics_updated')) {
            try {
                // Chạy command cập nhật thống kê trong background
                Artisan::queue('orders:update-stats');

                // Cache để tránh chạy quá nhiều lần
                Cache::put('statistics_updated', true, now()->addMinutes(5));
            } catch (\Exception $e) {
                // Log lỗi nhưng không làm gián đoạn request
                Log::error('Lỗi cập nhật thống kê: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
