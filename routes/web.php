<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController; // ✅ Đúng controller
use App\Http\Middleware\AuthenticateMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index')->middleware(AuthenticateMiddleware::class); // ✅ Sử dụng middleware auth
Route::get('admin', [AuthController::class, 'index'])->name('auth.admin');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');