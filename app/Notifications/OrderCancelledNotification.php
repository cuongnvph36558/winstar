<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $user;
    public $cancellationReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, User $user, $cancellationReason)
    {
        $this->order = $order;
        $this->user = $user;
        $this->cancellationReason = $cancellationReason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Đơn hàng #' . $this->order->code_order . ' đã bị hủy')
            ->greeting('Xin chào Admin!')
            ->line('Có một đơn hàng mới đã bị hủy bởi khách hàng.')
            ->line('Thông tin đơn hàng:')
            ->line('- Mã đơn hàng: #' . $this->order->code_order)
            ->line('- Khách hàng: ' . $this->user->name . ' (' . $this->user->email . ')')
            ->line('- Tổng tiền: ' . number_format($this->order->total_amount) . 'đ')
            ->line('- Lý do hủy: ' . $this->cancellationReason)
            ->line('- Thời gian hủy: ' . $this->order->cancelled_at->format('d/m/Y H:i:s'))
            ->action('Xem chi tiết đơn hàng', url('/admin/orders/' . $this->order->id))
            ->line('Vui lòng kiểm tra và xử lý theo quy định của công ty.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->code_order,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'total_amount' => $this->order->total_amount,
            'cancellation_reason' => $this->cancellationReason,
            'cancelled_at' => $this->order->cancelled_at,
            'message' => "Đơn hàng #{$this->order->code_order} đã bị hủy bởi {$this->user->name}",
            'type' => 'order_cancelled'
        ];
    }
}
