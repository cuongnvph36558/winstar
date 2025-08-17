# Hướng dẫn Test VNPay

## 1. Chuẩn bị

Đảm bảo đã cấu hình đúng trong file `.env`:

```env
VNPAY_TMN_CODE=1VYBIYQP
VNPAY_HASH_SECRET=NOH6MBGNLQL9O9OMMFMZ2AX8NIEP50W1
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
```

## 2. Các bước test

### Bước 1: Tạo đơn hàng test
1. Truy cập trang checkout: `/order/checkout`
2. Chọn sản phẩm bất kỳ
3. Chọn phương thức thanh toán: **VNPay**
4. Điền thông tin đơn hàng
5. Click "Đặt hàng ngay"

### Bước 2: Thanh toán trên VNPay sandbox
1. Sẽ được redirect đến trang VNPay sandbox
2. Chọn ngân hàng bất kỳ
3. Sử dụng thông tin test:
   - **Số thẻ:** 9704000000000018
   - **Tên chủ thẻ:** NGUYEN VAN A
   - **Ngày phát hành:** 07/15
   - **OTP:** 123456

### Bước 3: Kiểm tra kết quả
1. **Thành công (Mã 00):**
   - Redirect về trang đơn hàng
   - Hiển thị thông báo "Thanh toán VNPay thành công!"
   - Trạng thái đơn hàng: "Đang xử lý"
   - Trạng thái thanh toán: "Đã thanh toán"

2. **Thất bại (Các mã khác):**
   - Redirect về trang giỏ hàng
   - Hiển thị thông báo lỗi tương ứng

## 3. Kiểm tra trong Admin

### Xem giao dịch VNPay:
1. Truy cập: `/admin/vnpay-transactions`
2. Kiểm tra giao dịch vừa tạo
3. Xem chi tiết giao dịch

### Thống kê:
- Tổng số giao dịch
- Số giao dịch thành công
- Số giao dịch thất bại
- Số giao dịch đang xử lý

## 4. Test các trường hợp

### Test thành công:
- Sử dụng thông tin test chuẩn
- Kết quả mong đợi: Mã 00

### Test thất bại:
- Nhập sai OTP
- Kết quả mong đợi: Mã 13

### Test hủy giao dịch:
- Không hoàn tất thanh toán
- Kết quả mong đợi: Không có callback

## 5. Kiểm tra Log

Xem log Laravel để debug:
```bash
tail -f storage/logs/laravel.log
```

Các log quan trọng:
- VNPay request data
- VNPay response data
- Payment processing errors

## 6. Test thanh toán lại

1. Tạo đơn hàng với VNPay
2. Hủy giao dịch (không hoàn tất)
3. Vào trang chi tiết đơn hàng
4. Click "Thanh toán lại"
5. Chọn VNPay và thực hiện lại

## 7. Lưu ý quan trọng

⚠️ **Đây là môi trường TEST:**
- Không có giao dịch thật
- Không cần tiền thật
- Chỉ dùng để test tính năng

✅ **Các tính năng cần test:**
- Tạo giao dịch VNPay
- Redirect đến trang thanh toán
- Xử lý callback
- Cập nhật trạng thái đơn hàng
- Lưu thông tin giao dịch
- Thanh toán lại
- Quản lý trong admin

## 8. Troubleshooting

### Lỗi thường gặp:

1. **"Cấu hình VNPay chưa đầy đủ"**
   - Kiểm tra file .env
   - Chạy: `php artisan config:clear`

2. **Không redirect đến VNPay**
   - Kiểm tra URL trong config
   - Kiểm tra log lỗi

3. **Callback không hoạt động**
   - Kiểm tra route `/payment/vnpay-return`
   - Kiểm tra hash secret

4. **Giao dịch không được lưu**
   - Kiểm tra database connection
   - Kiểm tra quyền ghi database

