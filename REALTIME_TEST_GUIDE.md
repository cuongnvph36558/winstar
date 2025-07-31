# 🧪 Hướng Dẫn Test Realtime System

## 📋 Kiểm Tra Trước Khi Test

### 1. Kiểm tra cấu hình
```bash
# Kiểm tra broadcasting config
php artisan config:show broadcasting

# Kiểm tra websockets config  
php artisan config:show websockets
```

### 2. Kiểm tra database
```bash
# Kiểm tra có dữ liệu test không
php artisan tinker
>>> App\Models\User::count()
>>> App\Models\Product::count() 
>>> App\Models\Order::count()
```

## 🚀 Các Bước Test

### Bước 1: Khởi động Queue Worker
```bash
# Terminal 1: Chạy queue worker
php artisan queue:work

# Hoặc chạy trong background
php artisan queue:work --daemon
```

### Bước 2: Khởi động Laravel WebSockets
```bash
# Terminal 2: Chạy websockets server
php artisan websockets:serve
```

### Bước 3: Test với trang web
1. Mở trình duyệt: `http://localhost:8000/test-realtime`
2. Kiểm tra connection status
3. Click các button test

### Bước 4: Test với Command Line
```bash
# Test favorite broadcast
php artisan test:broadcast favorite

# Test cart broadcast  
php artisan test:broadcast cart

# Test order broadcast
php artisan test:broadcast order
```

## 🎯 Test Cases

### Test Case 1: Connection Test
- **Mục tiêu:** Kiểm tra kết nối WebSocket
- **Bước thực hiện:**
  1. Mở `/test-realtime`
  2. Kiểm tra status "✅ Kết nối thành công!"
  3. Click "Test Connection"

### Test Case 2: Favorite Broadcast Test
- **Mục tiêu:** Test event FavoriteUpdated
- **Bước thực hiện:**
  1. Mở 2 tab `/test-realtime`
  2. Tab 1: Click "Send Test Favorite Event"
  3. Tab 2: Xem có nhận được event không

### Test Case 3: Cart Broadcast Test
- **Mục tiêu:** Test event CardUpdate
- **Bước thực hiện:**
  1. Mở 2 tab `/test-realtime`
  2. Tab 1: Click "Send Test Cart Event"
  3. Tab 2: Xem có nhận được event không

### Test Case 4: Order Broadcast Test
- **Mục tiêu:** Test event OrderStatusUpdated
- **Bước thực hiện:**
  1. Mở 2 tab `/test-realtime`
  2. Tab 1: Click "Send Test Order Event"
  3. Tab 2: Xem có nhận được event không

## 🔧 Troubleshooting

### Lỗi 1: "Connection failed"
**Nguyên nhân:** WebSockets server chưa chạy
**Giải pháp:**
```bash
php artisan websockets:serve
```

### Lỗi 2: "No events received"
**Nguyên nhân:** Queue worker chưa chạy
**Giải pháp:**
```bash
php artisan queue:work
```

### Lỗi 3: "No user/product found"
**Nguyên nhân:** Database trống
**Giải pháp:**
```bash
php artisan db:seed
```

### Lỗi 4: "Broadcast failed"
**Nguyên nhân:** Broadcasting config sai
**Giải pháp:**
```bash
php artisan config:cache
php artisan queue:restart
```

## 📊 Kiểm Tra Logs

### Queue Logs
```bash
# Xem queue logs
tail -f storage/logs/laravel.log | grep "queue"
```

### WebSocket Logs
```bash
# Xem websocket logs
tail -f storage/logs/laravel.log | grep "websocket"
```

## 🎯 Test Trên Các Trang Thực Tế

### 1. Test Favorite Page
1. Mở `/favorite` trên 2 browser
2. Browser 1: Thích/bỏ thích sản phẩm
3. Browser 2: Xem số lượt thích có cập nhật không

### 2. Test Product List
1. Mở `/product` trên 2 browser
2. Browser 1: Thích sản phẩm
3. Browser 2: Xem có highlight animation không

### 3. Test Cart Page
1. Mở `/cart` trên 2 browser
2. Browser 1: Thêm/xóa sản phẩm
3. Browser 2: Xem cart count có cập nhật không

## ✅ Checklist Test

- [ ] Queue worker đang chạy
- [ ] WebSockets server đang chạy
- [ ] Connection test thành công
- [ ] Favorite broadcast hoạt động
- [ ] Cart broadcast hoạt động
- [ ] Order broadcast hoạt động
- [ ] Cross-browser sync hoạt động
- [ ] Activity feed hiển thị
- [ ] Toast notifications hoạt động

## 🚨 Lưu Ý Quan Trọng

1. **Luôn chạy queue worker** khi test broadcast
2. **Luôn chạy websockets server** khi test realtime
3. **Clear cache** nếu có thay đổi config
4. **Kiểm tra database** có dữ liệu test
5. **Test cross-browser** để đảm bảo sync

## 📞 Hỗ Trợ

Nếu gặp vấn đề, kiểm tra:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console: F12 → Console
3. Network tab: F12 → Network → WS
4. Queue status: `php artisan queue:work --verbose` 