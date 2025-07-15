# 🚀 Hệ thống Realtime cho Winstar E-commerce

## 📋 Tổng quan

Hệ thống realtime này cung cấp các tính năng cập nhật trực tiếp cho:
- ✅ **Favorite Products**: Cập nhật số lượt yêu thích realtime
- ✅ **Live Notifications**: Thông báo khi có người khác thích/bỏ thích sản phẩm  
- ✅ **Activity Feed**: Theo dõi hoạt động của người dùng khác
- ✅ **Live Indicators**: Hiển thị trạng thái kết nối realtime

## 🛠️ Cài đặt

### 1. Cấu hình Environment

Thêm vào file `.env`:

```env
# Broadcasting Configuration
BROADCAST_DRIVER=pusher

# Pusher Configuration  
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Queue Configuration (required for broadcasting)
QUEUE_CONNECTION=database
```

### 2. Tạo Database Tables

```bash
# Tạo jobs table cho queue system
php artisan queue:table
php artisan migrate
```

### 3. Cài đặt Pusher

1. Đăng ký tài khoản tại [Pusher.com](https://pusher.com)
2. Tạo app mới
3. Copy credentials vào file `.env`

### 4. Khởi động Queue Worker

```bash
# Chạy queue worker để xử lý broadcasting
php artisan queue:work

# Hoặc chạy trong background
php artisan queue:work --daemon
```

## 🎯 Tính năng Realtime

### 1. **Live Favorite Updates**
- Số lượt yêu thích cập nhật ngay lập tức khi có người thích/bỏ thích
- Animation highlight khi có thay đổi
- Sync cross-browser/device

### 2. **Realtime Notifications**  
- Toast notifications ở góc phải màn hình
- Thông báo khi có hoạt động mới từ người dùng khác
- Auto-hide với timer

### 3. **Activity Feed**
- Button notification floating ở góc phải
- Hiển thị 20 hoạt động gần nhất
- Real-time counter badge
- Timestamp "time ago"

### 4. **Live Status Indicators**
- Chấm xanh nhấp nháy cho biết đang kết nối
- "Cập nhật realtime" trong subtitle

## 🔧 Cách sử dụng

### Broadcasting Events

```php
use App\Events\FavoriteUpdated;

// Trigger event khi có favorite action
$user = Auth::user();
$product = Product::find($productId);
$favoriteCount = Favorite::where('product_id', $productId)->count();

broadcast(new FavoriteUpdated($user, $product, 'added', $favoriteCount));
```

### Frontend Listening

```javascript
// Listen to public channel
window.Echo.channel('favorites')
    .listen('.favorite.updated', (e) => {
        console.log('Favorite update:', e);
        // Handle the update
    });

// Listen to private user channel  
window.Echo.private('user.' + userId)
    .listen('.favorite.updated', (e) => {
        // Personal notifications
    });
```

## 🧪 Testing

### Test Broadcasting

```bash
# Test command để kiểm tra broadcast hoạt động
php artisan test:broadcast
```

### Debug Mode

1. Mở browser console
2. Vào trang favorites  
3. Mở tab mới, thích/bỏ thích sản phẩm
4. Xem realtime updates trong tab đầu

## 📡 Channels

### Public Channels
- `favorites` - Tất cả cập nhật favorite
- `product.{id}` - Cập nhật cho sản phẩm cụ thể

### Private Channels  
- `user.{id}` - Thông báo cá nhân cho user

## 🎨 UI Components

### Activity Toggle Button
```html
<button class="activity-toggle" onclick="toggleActivityFeed()">
    <i class="fa fa-bell"></i>
    <span class="activity-count">5</span>
</button>
```

### Live Indicator
```html
<span class="live-indicator"></span>
```

### Toast Notifications
```javascript
window.RealtimeNotifications.showToast('success', 'Tiêu đề', 'Nội dung');
```

## 🔧 Troubleshooting

### Không nhận được events?

1. **Kiểm tra Pusher credentials**
   ```bash
   php artisan tinker
   >>> broadcast(new App\Events\FavoriteUpdated(...));
   ```

2. **Kiểm tra queue worker**
   ```bash
   php artisan queue:work --verbose
   ```

3. **Kiểm tra browser console**
   - Có lỗi connection không?
   - Echo được khởi tạo đúng chưa?

### Queue worker không chạy?

```bash
# Clear queue
php artisan queue:clear

# Restart queue  
php artisan queue:restart

# Chạy queue:work với debug
php artisan queue:work --verbose --tries=3
```

### Pusher connection issues?

1. Kiểm tra firewall/proxy settings
2. Verify Pusher app settings
3. Check network connectivity

## 🚀 Production Deployment

### 1. Process Manager
```bash
# Sử dụng Supervisor để quản lý queue worker
sudo apt install supervisor

# Tạo config file
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

### 2. Supervisor Config
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
```

### 3. HTTPS Required
- Pusher yêu cầu HTTPS trong production
- Update PUSHER_SCHEME=https

## 📈 Performance

### Optimization Tips
1. **Sử dụng Redis** cho queue driver thay vì database
2. **Rate limiting** cho events
3. **Cleanup old jobs** định kỳ
4. **Monitor Pusher usage** để tránh vượt quota

### Redis Setup
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 🎉 Kết quả

Sau khi setup xong, bạn sẽ có:

✅ **Realtime favorite updates** - Số lượt yêu thích cập nhật ngay lập tức  
✅ **Live notifications** - Thông báo realtime về hoạt động người dùng  
✅ **Activity feed** - Theo dõi hoạt động trực tiếp  
✅ **Cross-browser sync** - Đồng bộ giữa các thiết bị  
✅ **Beautiful UI** - Giao diện đẹp với animations  

**Demo:** Mở 2 browser tabs, thích sản phẩm ở tab này và xem realtime update ở tab kia! 🎊 