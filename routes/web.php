<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController; //  Đúng controller
use App\Http\Controllers\Backend\UserController;
use App\Http\Middleware\AuthenticateMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Backend route
Route::get('dashborad/index', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('admin');
// User
Route::group(['prefix'=> 'backend/user'], function(){
    Route::get('index', [UserController::class, 'index'])->name('user.index')->middleware(AuthenticateMiddleware::class);
    Route::get('create', [UserController::class, 'create'])->name('user.create')->middleware(AuthenticateMiddleware::class);
});

Route::get('admin', [AuthController::class, 'index'])->name('auth.admin');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');