# Hệ thống Realtime Đơn hàng - Hướng dẫn sử dụng

## Tổng quan
Hệ thống realtime đơn hàng đã được đơn giản hóa và tối ưu hóa để hoạt động ổn định. Khi admin hoặc client thay đổi trạng thái đơn hàng, trang còn lại sẽ tự động reload để cập nhật thông tin.

## Cấu trúc hệ thống

### 1. Event Broadcasting
- **Event chính**: `OrderStatusUpdated` (đã xóa `OrderUpdated` trùng lặp)
- **Channels**: 
  - `orders` - Channel công khai cho tất cả cập nhật đơn hàng
  - `admin.orders` - Channel riêng cho admin
  - `user.{userId}` - Channel riêng cho từng user

### 2. Frontend Scripts
- **Admin Layout**: `resources/views/layouts/admin.blade.php`
- **Client Layout**: `resources/views/layouts/client.blade.php`
- **Cơ chế**: Đơn giản - chỉ reload page khi nhận event

### 3. Backend Controllers
- **Admin**: `app/Http/Controllers/Admin/OrderController.php`
- **Client**: `app/Http/Controllers/Client/OrderController.php`
- **Model**: `app/Models/Order.php` (tự động trigger event khi status thay đổi)

## Cách hoạt động

### Khi Admin thay đổi trạng thái đơn hàng:
1. Admin cập nhật trạng thái trong admin panel
2. Controller gọi `event(new OrderStatusUpdated($order, $oldStatus, $newStatus))`
3. Event được broadcast đến các channels
4. Client page tự động reload để hiển thị trạng thái mới

### Khi Client xác nhận nhận hàng:
1. Client click "Xác nhận đã nhận hàng"
2. Controller gọi `event(new OrderStatusUpdated($order, $oldStatus, $newStatus))`
3. Event được broadcast đến các channels
4. Admin page tự động reload để hiển thị trạng thái mới

## Cấu hình

### Broadcasting Configuration
```php
// config/broadcasting.php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY', 'localkey123'),
    'secret' => env('PUSHER_APP_SECRET', 'localsecret123'),
    'app_id' => env('PUSHER_APP_ID', 'local-app'),
    'options' => [
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 6001),
        'scheme' => env('PUSHER_SCHEME', 'http'),
        'encrypted' => false,
        'useTLS' => false,
        'cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
    ],
],
```

### Frontend Script
```javascript
// Pusher setup
window.pusher = new Pusher('localkey123', {
    cluster: 'mt1',
    wsHost: '127.0.0.1',
    wsPort: 6001,
    forceTLS: false
});

// Subscribe to channels
const ordersChannel = window.pusher.subscribe('orders');
const adminOrdersChannel = window.pusher.subscribe('admin.orders');

// Listen for events
ordersChannel.bind('OrderStatusUpdated', function(data) {
    console.log('📦 Order update received - reloading page');
    location.reload();
});

adminOrdersChannel.bind('OrderStatusUpdated', function(data) {
    console.log('📦 Admin order update received - reloading page');
    location.reload();
});
```

## Testing

### Test Route
Truy cập `/test-realtime` để test hệ thống realtime:
- Tự động thay đổi trạng thái đơn hàng đầu tiên
- Gửi event broadcast
- Trả về JSON response với thông tin test

### Manual Testing
1. Mở 2 tab: Admin Orders và Client Orders
2. Thay đổi trạng thái đơn hàng ở một tab
3. Tab còn lại sẽ tự động reload và hiển thị trạng thái mới

## Troubleshooting

### Kiểm tra WebSocket Connection
1. Mở Developer Tools > Console
2. Tìm log: "✅ Realtime listeners setup - page will reload on order updates"
3. Nếu không thấy, kiểm tra:
   - Laravel WebSocket server có đang chạy không
   - Cấu hình broadcasting có đúng không
   - Pusher key có đúng không

### Kiểm tra Event Broadcasting
1. Mở Developer Tools > Network > WS
2. Kiểm tra WebSocket connection
3. Xem có event được gửi không

### Log Files
- Laravel logs: `storage/logs/laravel.log`
- WebSocket logs: Console của Laravel WebSocket server

## Lưu ý quan trọng

1. **Đơn giản hóa**: Hệ thống đã được đơn giản hóa, chỉ reload page thay vì update DOM phức tạp
2. **Performance**: Reload page đảm bảo dữ liệu luôn chính xác và đồng bộ
3. **Stability**: Ít lỗi hơn so với update DOM realtime
4. **Maintenance**: Dễ bảo trì và debug hơn

## Các file đã được sửa

### Xóa file trùng lặp:
- `app/Events/OrderUpdated.php` (đã xóa)

### Cập nhật files:
- `app/Events/OrderStatusUpdated.php` - Cải thiện event
- `app/Http/Controllers/Admin/OrderController.php` - Xóa OrderUpdated
- `app/Http/Controllers/Client/OrderController.php` - Đã sử dụng OrderStatusUpdated
- `resources/views/layouts/admin.blade.php` - Đơn giản hóa script
- `resources/views/layouts/client.blade.php` - Đơn giản hóa script
- `resources/views/admin/orders/index.blade.php` - Xóa script phức tạp
- `resources/views/admin/orders/show.blade.php` - Xóa script phức tạp
- `resources/views/client/order/list.blade.php` - Xóa script phức tạp

### Thêm:
- `routes/web.php` - Thêm test route
- `REALTIME_README.md` - Hướng dẫn này 