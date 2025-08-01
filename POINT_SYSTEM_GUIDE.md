# 🎯 Hướng Dẫn Hệ Thống Tích Điểm

## 📋 Tổng Quan Hệ Thống

Hệ thống tích điểm cho phép khách hàng:
- ✅ Tích điểm khi mua hàng
- ✅ Đổi điểm lấy voucher
- ✅ Sử dụng voucher giảm giá
- ✅ Theo dõi lịch sử giao dịch điểm
- ✅ Nâng cấp level VIP

## 🛒 Cách Mua Hàng Và Tích Điểm

### **Bước 1: Đăng ký/Đăng nhập**
```
1. Truy cập: http://localhost:8000
2. Click "Đăng ký" hoặc "Đăng nhập"
3. Điền thông tin cá nhân
```

### **Bước 2: Chọn sản phẩm**
```
1. Duyệt danh mục sản phẩm
2. Chọn sản phẩm muốn mua
3. Click "Thêm vào giỏ hàng"
```

### **Bước 3: Thanh toán**
```
1. Vào giỏ hàng: http://localhost:8000/cart
2. Kiểm tra sản phẩm và số lượng
3. Click "Thanh toán"
4. Điền thông tin giao hàng
5. Chọn phương thức thanh toán:
   - COD (Thanh toán khi nhận hàng)
   - MoMo
   - VNPay
```

### **Bước 4: Tích điểm tự động**
```
✅ Điểm sẽ được tích tự động khi:
- Đơn hàng chuyển sang trạng thái "completed"
- Hệ thống tính điểm dựa trên:
  + Giá trị đơn hàng
  + Level VIP của khách hàng
```

## 💰 Cách Tính Điểm

### **Công thức tính điểm:**
```
Điểm tích = Giá trị đơn hàng × Tỷ lệ tích điểm
```

### **Tỷ lệ tích điểm theo level:**
- **Bronze** (Mặc định): 5% = 0.05
- **Silver**: 7% = 0.07  
- **Gold**: 10% = 0.10
- **Platinum**: 15% = 0.15

### **Ví dụ:**
```
Đơn hàng: 1,000,000 VND
Level: Bronze (5%)
Điểm tích: 1,000,000 × 0.05 = 50,000 điểm
```

## 🎁 Đổi Điểm Lấy Voucher

### **Bước 1: Xem điểm hiện tại**
```
1. Đăng nhập vào tài khoản
2. Vào trang "Điểm tích lũy"
3. Xem số điểm hiện có
```

### **Bước 2: Chọn voucher**
```
1. Xem danh sách voucher có sẵn
2. Chọn voucher muốn đổi
3. Kiểm tra điểm cần thiết
```

### **Bước 3: Đổi voucher**
```
1. Click "Đổi voucher"
2. Xác nhận giao dịch
3. Nhận mã voucher
```

## 🏷️ Sử Dụng Voucher

### **Khi thanh toán:**
```
1. Trong trang thanh toán
2. Nhập mã voucher vào ô "Mã giảm giá"
3. Click "Áp dụng"
4. Xem giảm giá được áp dụng
```

## 📊 Theo Dõi Điểm

### **Xem thông tin điểm:**
```
- Tổng điểm: Điểm hiện có
- Điểm đã tích: Tổng điểm đã nhận
- Điểm đã dùng: Điểm đã đổi voucher
- Điểm hết hạn: Điểm không còn hiệu lực
```

### **Lịch sử giao dịch:**
```
- Ngày tích điểm
- Nguồn tích điểm (đơn hàng nào)
- Số điểm tích
- Ngày hết hạn
```

## 🏆 Level VIP

### **Cách nâng cấp level:**
```
- Tích lũy điểm theo thời gian
- Mua hàng thường xuyên
- Tổng giá trị đơn hàng cao
```

### **Quyền lợi level:**
- **Bronze**: Tỷ lệ tích điểm 5%
- **Silver**: Tỷ lệ tích điểm 7%
- **Gold**: Tỷ lệ tích điểm 10%
- **Platinum**: Tỷ lệ tích điểm 15%

## ⏰ Thời Hạn Điểm

### **Quy định:**
```
- Điểm có hiệu lực: 12 tháng
- Sau 12 tháng: Điểm tự động hết hạn
- Hệ thống tự động xử lý điểm hết hạn
```

## 🔧 Lệnh Hệ Thống

### **Cập nhật thống kê điểm:**
```bash
php artisan orders:update-stats
```

### **Xử lý điểm hết hạn:**
```bash
# Tự động chạy hàng ngày
php artisan schedule:run
```

### **Tạo điểm thưởng (Admin):**
```php
$pointService->giveBonusPoints($user, 1000, 'Điểm thưởng khuyến mãi');
```

## 🚨 Lưu Ý Quan Trọng

### **Điều kiện tích điểm:**
- ✅ Đơn hàng phải hoàn thành (status = 'completed')
- ✅ Khách hàng phải đăng nhập
- ✅ Đơn hàng hợp lệ (không bị hủy)

### **Điều kiện đổi voucher:**
- ✅ Có đủ điểm
- ✅ Voucher còn hiệu lực
- ✅ Không vượt quá giới hạn đổi

### **Điều kiện sử dụng voucher:**
- ✅ Voucher chưa hết hạn
- ✅ Đơn hàng đủ điều kiện (giá trị tối thiểu)
- ✅ Chưa được sử dụng

## 📞 Hỗ Trợ

### **Nếu gặp vấn đề:**
1. Kiểm tra log: `storage/logs/laravel.log`
2. Chạy command: `php artisan orders:update-stats`
3. Xóa cache: `php artisan cache:clear`
4. Liên hệ admin để hỗ trợ

### **Test hệ thống:**
1. Tạo đơn hàng test
2. Chuyển trạng thái sang "completed"
3. Kiểm tra điểm được tích
4. Test đổi voucher
5. Test sử dụng voucher

---

**🎉 Chúc bạn sử dụng hệ thống tích điểm hiệu quả!** 
