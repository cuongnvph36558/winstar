<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $variants = ProductVariant::all();
        return view('admin.orders.create', compact('variants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'shipping_address' => 'nullable|string',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'receiver_name' => $data['receiver_name'],
            'receiver_phone' => $data['receiver_phone'],
            'address' => $data['address'],
            'shipping_address' => $data['shipping_address'] ?? $data['address'],
            'payment_method' => $data['payment_method'],
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        $totalAmount = 0;

        foreach ($data['items'] as $item) {
            $variant = ProductVariant::findOrFail($item['variant_id']);
            $quantity = $item['quantity'];
            $price = $variant->price;
            $lineTotal = $price * $quantity;

            $order->details()->create([
                'variant_id' => $variant->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $totalAmount += $lineTotal;
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('admin.order.index')->with('success', 'Tạo đơn hàng thành công.');
    }

    public function show($id)
    {
        $order = Order::with('details.variant')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,cancelled',
        ]);


        $order->status = $data['status'];
        $order->save();
        event(new OrderStatusUpdated($order));
        return redirect()->route('admin.order.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Đã xoá đơn hàng.');
    }

    public function trash()
    {
        $orders = Order::onlyTrashed()->latest()->paginate(10);
        return view('admin.orders.trash', compact('orders'));
    }
}
