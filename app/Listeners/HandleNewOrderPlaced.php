<?php

namespace App\Listeners;

use App\Events\NewOrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleNewOrderPlaced implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewOrderPlaced $event): void
    {
        $order = $event->order;
        
        Log::info('New order placed - handling notification', [
            'order_id' => $order->id,
            'order_code' => $order->code_order,
            'user_id' => $order->user_id,
            'total_amount' => $order->total_amount
        ]);

        // Có thể thêm logic gửi email thông báo cho admin ở đây
        // Mail::to('admin@example.com')->send(new NewOrderNotification($order));
        
        // Hoặc gửi notification qua database
        // $adminUsers = User::where('role', 'admin')->get();
        // foreach ($adminUsers as $admin) {
        //     $admin->notify(new NewOrderNotification($order));
        // }
    }
} 