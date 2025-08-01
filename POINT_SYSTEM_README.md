# Hệ thống tích điểm (Point System)

## Tổng quan

Hệ thống tích điểm cho phép người dùng tích điểm khi mua hàng, đổi điểm lấy voucher và quản lý level VIP. Hệ thống bao gồm:

- **Tích điểm**: Tự động tích điểm khi mua hàng dựa trên giá trị đơn hàng và level VIP
- **Đổi điểm**: Đổi điểm lấy voucher giảm giá
- **Level VIP**: 5 cấp độ từ Bronze đến Diamond với tỷ lệ tích điểm khác nhau
- **Quản lý voucher**: Admin có thể tạo và quản lý voucher
- **Xử lý hết hạn**: Tự động xử lý điểm hết hạn

## Cấu trúc Database

### Bảng `points`
- `user_id`: ID người dùng
- `total_points`: Tổng điểm hiện tại
- `earned_points`: Tổng điểm đã tích
- `used_points`: Tổng điểm đã sử dụng
- `expired_points`: Tổng điểm đã hết hạn

### Bảng `point_transactions`
- `user_id`: ID người dùng
- `type`: Loại giao dịch (earn, use, expire, bonus)
- `points`: Số điểm (+ hoặc -)
- `description`: Mô tả giao dịch
- `reference_type`: Loại đối tượng tham chiếu (order, voucher, bonus)
- `reference_id`: ID đối tượng tham chiếu
- `expiry_date`: Ngày hết hạn điểm
- `is_expired`: Đã hết hạn chưa

### Bảng `point_vouchers`
- `name`: Tên voucher
- `description`: Mô tả
- `points_required`: Số điểm cần để đổi
- `discount_value`: Giá trị giảm giá
- `discount_type`: Loại giảm giá (percentage, fixed)
- `min_order_value`: Giá trị đơn hàng tối thiểu
- `max_usage`: Số lần sử dụng tối đa
- `current_usage`: Số lần đã sử dụng
- `start_date`: Ngày bắt đầu hiệu lực
- `end_date`: Ngày kết thúc hiệu lực
- `is_active`: Trạng thái hoạt động

### Bảng `user_point_vouchers`
- `user_id`: ID người dùng
- `point_voucher_id`: ID voucher
- `voucher_code`: Mã voucher
- `status`: Trạng thái (active, used, expired)
- `expiry_date`: Ngày hết hạn voucher
- `used_in_order_id`: ID đơn hàng đã sử dụng
- `used_at`: Thời gian sử dụng

## Level VIP

| Level | Điểm cần | Tỷ lệ tích điểm |
|-------|----------|-----------------|
| Bronze | 0-499 | 5% |
| Silver | 500-1,999 | 8% |
| Gold | 2,000-4,999 | 10% |
| Platinum | 5,000-9,999 | 12% |
| Diamond | 10,000+ | 15% |

## Cách sử dụng

### 1. Tích điểm tự động
Điểm sẽ được tích tự động khi đơn hàng hoàn thành:
```php
// Trong OrderController
if ($order->status === 'completed') {
    $this->pointService->earnPointsFromOrder($order->user, $order);
}
```

### 2. Đổi điểm lấy voucher
```php
$result = $pointService->exchangePointsForVoucher($user, $voucherId);
```

### 3. Sử dụng voucher
```php
$result = $pointService->useVoucherInOrder($voucherCode, $order);
```

### 4. Thêm điểm thưởng
```php
$pointService->giveBonusPoints($user, $points, $description);
```

## Routes

### Client Routes
- `GET /points` - Trang điểm của user
- `GET /points/vouchers` - Trang đổi voucher
- `POST /points/exchange-voucher` - Đổi điểm lấy voucher
- `GET /points/history` - Lịch sử giao dịch
- `GET /points/api/info` - API lấy thông tin điểm
- `GET /points/api/available-vouchers` - API lấy voucher có thể đổi
- `GET /points/api/user-vouchers` - API lấy voucher của user

### Admin Routes
- `GET /admin/points` - Danh sách điểm của tất cả users
- `GET /admin/points/{userId}` - Chi tiết điểm của user
- `POST /admin/points/add-bonus` - Thêm điểm thưởng
- `GET /admin/points/statistics` - Thống kê điểm
- `POST /admin/points/process-expired` - Xử lý điểm hết hạn
- `GET /admin/points/vouchers` - Quản lý voucher
- `GET /admin/points/vouchers/create` - Tạo voucher mới
- `POST /admin/points/vouchers` - Lưu voucher mới
- `GET /admin/points/vouchers/{id}/edit` - Chỉnh sửa voucher
- `PUT /admin/points/vouchers/{id}` - Cập nhật voucher
- `DELETE /admin/points/vouchers/{id}` - Xóa voucher

## Commands

### Xử lý điểm hết hạn
```bash
php artisan points:process-expired
```

## Seeder

### Tạo dữ liệu mẫu voucher
```bash
php artisan db:seed --class=PointVoucherSeeder
```

## Cấu hình

### Thêm vào Kernel để chạy tự động
Thêm vào `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Xử lý điểm hết hạn hàng ngày lúc 00:00
    $schedule->command('points:process-expired')->daily();
}
```

## Tính năng nâng cao

### 1. Tích hợp với checkout
Thêm trường voucher vào form checkout và xử lý trong OrderController.

### 2. Thông báo
Gửi email thông báo khi:
- Tích điểm thành công
- Đổi voucher thành công
- Điểm sắp hết hạn
- Lên level VIP

### 3. Báo cáo
- Thống kê điểm theo thời gian
- Báo cáo voucher được sử dụng
- Phân tích hành vi người dùng

### 4. Tích hợp thanh toán
Cho phép sử dụng điểm để thanh toán một phần đơn hàng.

## Lưu ý

1. **Bảo mật**: Kiểm tra quyền truy cập cho tất cả API
2. **Performance**: Index database cho các truy vấn thường xuyên
3. **Backup**: Backup dữ liệu điểm thường xuyên
4. **Audit**: Log tất cả giao dịch điểm để kiểm tra
5. **Testing**: Viết test cho tất cả chức năng quan trọng 
