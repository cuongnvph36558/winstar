<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Ajax\LocationController;
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
    Route::post('store', [UserController::class, 'store'])->name('user.store')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit', [UserController::class, 'edit'])-> where(['id' => '[0=9]+'])->name('user.edit')->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update', [UserController::class, 'update'])-> where(['id' => '[0=9]+'])->name('user.update')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/delete', [UserController::class, 'delete'])-> where(['id' => '[0=9]+'])->name('user.delete')->middleware(AuthenticateMiddleware::class);
    Route::delete('{id}/destroy', [UserController::class, 'destroy'])-> where(['id' => '[0=9]+'])->name('user.destroy')->middleware(AuthenticateMiddleware::class);
});

//AJAX
Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index')->middleware('admin');
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus')->middleware('admin');
Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll')->middleware('admin');

Route::get('admin', [AuthController::class, 'index'])->name('auth.admin');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');