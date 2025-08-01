# Sửa Lỗi Thống Kê Đơn Hàng

## Vấn đề đã được khắc phục:

### 1. **Trừ kho bị trùng lặp**
- **Trước**: Kho bị trừ 2 lần trong `placeOrder()` và `processCOD()`
- **Sau**: Chỉ trừ kho khi thanh toán thành công (COD) hoặc khi xác nhận thanh toán online

### 2. **Thống kê không cập nhật realtime**
- **Trước**: Views thống kê không được refresh tự động
- **Sau**: Tự động cập nhật thống kê khi truy cập trang admin

### 3. **Thiếu xử lý điểm tích lũy**
- **Trước**: Điểm không được tích khi đơn hàng hoàn thành
- **Sau**: Tự động tích điểm khi đơn hàng chuyển sang trạng thái "completed"

### 4. **Lỗi property không tồn tại**
- **Trước**: Lỗi `Undefined property: stdClass::$variant_name`, `$total_usage`, `$total_orders`
- **Sau**: Sử dụng đúng tên trường từ database views

## Các file đã được tạo/sửa:

### 1. **Command cập nhật thống kê**
```bash
php artisan orders:update-stats
```
- File: `app/Console/Commands/UpdateOrderStatistics.php`
- Chức năng: Cập nhật điểm tích lũy và refresh views thống kê

### 2. **Event và Listener**
- File: `app/Events/OrderStatusUpdated.php`
- File: `app/Listeners/HandleOrderStatusUpdate.php`
- Chức năng: Tự động xử lý khi trạng thái đơn hàng thay đổi

### 3. **Middleware tự động cập nhật**
- File: `app/Http/Middleware/UpdateStatisticsMiddleware.php`
- Chức năng: Tự động cập nhật thống kê khi truy cập trang admin

### 4. **Order Model**
- Thêm event listener để trigger khi trạng thái thay đổi

## Cách sử dụng:

### 1. **Cập nhật thống kê thủ công**
```bash
php artisan orders:update-stats
```

### 2. **Tự động cập nhật**
- Thống kê sẽ tự động cập nhật khi truy cập trang admin
- Cache 5 phút để tránh chạy quá nhiều lần

### 3. **Xử lý điểm tích lũy**
- Điểm sẽ tự động được tích khi đơn hàng chuyển sang "completed"
- Có thể chạy command để xử lý các đơn hàng cũ

## Lưu ý:

1. **Queue**: Nếu sử dụng queue, hãy chạy:
```bash
php artisan queue:work
```

2. **Cache**: Nếu muốn force cập nhật thống kê:
```bash
php artisan cache:clear
```

3. **Database**: Đảm bảo các views đã được tạo:
```bash
php artisan migrate
```

## Kiểm tra:

1. Tạo đơn hàng mới
2. Chuyển trạng thái sang "completed"
3. Kiểm tra điểm tích lũy của user
4. Kiểm tra thống kê trong admin dashboard

## Troubleshooting:

- Nếu thống kê không cập nhật: Chạy `php artisan orders:update-stats`
- Nếu điểm không tích: Kiểm tra log trong `storage/logs/laravel.log`
- Nếu có lỗi database: Kiểm tra cấu hình database và chạy migration 
