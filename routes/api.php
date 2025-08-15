<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Order status check API
Route::get('/orders/{order}/status', function (Request $request, $orderId) {
    $order = \App\Models\Order::find($orderId);
    
    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }
    
    // Check if user is authorized to view this order
    if (auth()->check() && $order->user_id !== auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    return response()->json([
        'success' => true,
        'order' => [
            'id' => $order->id,
            'status' => $order->status,
            'is_received' => $order->is_received,
            'updated_at' => $order->updated_at
        ]
    ]);
})->name('api.order.status');
