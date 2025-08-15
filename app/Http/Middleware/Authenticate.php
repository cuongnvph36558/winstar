<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Thêm thông báo flash message khi redirect đến login
            session()->flash('error', 'Vui lòng đăng nhập để tiếp tục!');
        }
        
        return $request->expectsJson() ? null : route('login');
    }
}
