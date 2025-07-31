# Hướng dẫn sử dụng trang Thống kê

## Tổng quan
Trang thống kê cho phép admin xem các dữ liệu thống kê quan trọng của hệ thống với khả năng lọc theo thời gian từ ngày này đến ngày kia.

## Tính năng chính

### 1. Lọc theo thời gian
- **Tất cả**: Hiển thị thống kê tổng quan không có filter
- **Theo ngày**: Lọc theo một ngày cụ thể
- **Theo tuần**: Lọc theo một tuần cụ thể  
- **Theo tháng**: Lọc theo một tháng cụ thể
- **Khoảng thời gian tùy chỉnh**: Chọn từ ngày đến ngày

### 2. Thống kê tổng quan
- **Doanh thu**: Tổng doanh thu trong khoảng thời gian đã chọn
- **Đơn hàng**: Số lượng đơn hàng trong khoảng thời gian
- **Người dùng**: Số người dùng mới đăng ký hoặc đang hoạt động
- **Sản phẩm**: Số sản phẩm mới tạo hoặc đang bán

### 3. Biểu đồ và báo cáo
- Biểu đồ doanh thu 6 tháng gần nhất
- Top 5 sản phẩm bán chạy
- Top mã giảm giá được sử dụng nhiều nhất
- Doanh thu theo danh mục
- Đơn hàng theo trạng thái

## Cách sử dụng

### Lọc theo khoảng thời gian tùy chỉnh
1. Chọn "Khoảng thời gian tùy chỉnh" từ dropdown "Loại lọc"
2. Nhập ngày bắt đầu vào trường "Từ ngày"
3. Nhập ngày kết thúc vào trường "Đến ngày"
4. Nhấn "Lọc dữ liệu"

### Lọc theo ngày/tuần/tháng
1. Chọn loại lọc mong muốn (ngày/tuần/tháng)
2. Nhập giá trị tương ứng
3. Nhấn "Lọc dữ liệu"

### Xem thống kê tổng quan
- Để xem thống kê tổng quan, chọn "Tất cả" và nhấn "Lọc dữ liệu"

## Các tính năng bổ sung

### Auto-refresh
- Dữ liệu sẽ tự động làm mới mỗi 5 phút
- Có thể tắt tính năng này bằng cách comment code trong file JavaScript

### Responsive Design
- Giao diện tương thích với mọi thiết bị
- Tối ưu hóa cho mobile và tablet

### Export và Print
- Có thể in báo cáo thống kê
- Hỗ trợ xuất dữ liệu (cần phát triển thêm)

## Cấu trúc file

```
app/Http/Controllers/Admin/StatController.php    # Controller xử lý logic
resources/views/admin/stats/index.blade.php      # View hiển thị
public/js/chart-dashboard.js                     # JavaScript xử lý tương tác
public/admin/css/dashboard-stats.css             # CSS tùy chỉnh
```

## Cấu hình

### Thêm route (đã có sẵn)
```php
Route::get('/statistics', [StatController::class, 'index'])->name('admin.statistics.index');
```

### Thêm menu (đã có sẵn)
```php
<li class="{{ request()->is('admin/statistics*') ? 'active' : '' }}">
    <a href="{{ route('admin.statistics.index') }}">
        <i class="fa fa-bar-chart-o"></i> <span class="nav-label">Thống kê</span>
    </a>
</li>
```

## Tùy chỉnh

### Thêm thống kê mới
1. Thêm logic vào `StatController::index()`
2. Truyền dữ liệu qua view
3. Hiển thị trong template

### Thay đổi giao diện
- Chỉnh sửa file `dashboard-stats.css`
- Cập nhật template trong `index.blade.php`

### Thêm biểu đồ mới
1. Tạo canvas element trong view
2. Thêm logic JavaScript trong `chart-dashboard.js`
3. Truyền dữ liệu từ controller

## Lưu ý quan trọng

1. **Performance**: Với dữ liệu lớn, nên sử dụng cache hoặc pagination
2. **Security**: Đảm bảo user có quyền truy cập trang thống kê
3. **Data Accuracy**: Kiểm tra tính chính xác của dữ liệu thống kê
4. **Backup**: Sao lưu dữ liệu thống kê định kỳ

## Troubleshooting

### Lỗi thường gặp
1. **Không hiển thị dữ liệu**: Kiểm tra database connection và permissions
2. **Biểu đồ không load**: Kiểm tra Chart.js library
3. **Filter không hoạt động**: Kiểm tra JavaScript console

### Debug
- Mở Developer Tools (F12)
- Kiểm tra Console tab để xem lỗi JavaScript
- Kiểm tra Network tab để xem request/response

## Phiên bản
- Version: 1.0.0
- Cập nhật lần cuối: 2024
- Framework: Laravel
- Frontend: Bootstrap + jQuery + Chart.js 
