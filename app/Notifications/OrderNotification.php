<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $type = 'placed')
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting('Xin chào ' . $this->order->receiver_name);

        switch ($this->type) {
            case 'placed':
                $message->line('Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi.')
                    ->line('Mã đơn hàng của bạn là: #' . $this->order->id)
                    ->line('Tổng giá trị đơn hàng: ' . number_format($this->order->total_amount, 0, ',', '.') . 'đ');
                
                if ($this->order->payment_method == 'bank_transfer') {
                    $message->line('Thông tin chuyển khoản:')
                        ->line('Ngân hàng: Vietcombank')
                        ->line('Số tài khoản: 1234567890')
                        ->line('Chủ tài khoản: NGUYEN VAN A')
                        ->line('Nội dung: Thanh toan don hang #' . $this->order->id);
                }
                break;

            case 'status_update':
                $message->line('Đơn hàng #' . $this->order->id . ' của bạn đã được cập nhật.')
                    ->line('Trạng thái mới: ' . $this->getStatusText($this->order->status));
                break;

            case 'payment_success':
                $message->line('Thanh toán cho đơn hàng #' . $this->order->id . ' đã được xác nhận.')
                    ->line('Cảm ơn bạn đã mua hàng!');
                break;

            case 'payment_failed':
                $message->line('Thanh toán cho đơn hàng #' . $this->order->id . ' không thành công.')
                    ->line('Vui lòng thử lại hoặc chọn phương thức thanh toán khác.');
                break;
        }

        $message->action('Xem chi tiết đơn hàng', route('client.order.track', $this->order->id))
            ->line('Cảm ơn bạn đã tin tưởng chúng tôi!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'type' => $this->type,
            'status' => $this->order->status,
        ];
    }

    /**
     * Get notification subject based on type
     */
    private function getSubject(): string
    {
        switch ($this->type) {
            case 'placed':
                return 'Xác nhận đơn hàng #' . $this->order->id;
            case 'status_update':
                return 'Cập nhật trạng thái đơn hàng #' . $this->order->id;
            case 'payment_success':
                return 'Xác nhận thanh toán đơn hàng #' . $this->order->id;
            case 'payment_failed':
                return 'Thanh toán không thành công - Đơn hàng #' . $this->order->id;
            default:
                return 'Thông tin đơn hàng #' . $this->order->id;
        }
    }

    /**
     * Get human readable status text
     */
    private function getStatusText(string $status): string
    {
        switch ($status) {
            case 'pending':
                return 'Chờ xử lý';
            case 'processing':
                return 'Đang xử lý';
            case 'shipping':
                return 'Đang giao hàng';
            case 'completed':
                return 'Đã hoàn thành';
            case 'cancelled':
                return 'Đã hủy';
            default:
                return $status;
        }
    }
} 