# 01 - Authentication

## Tính năng
- Đăng ký, đăng nhập, đăng xuất người dùng
- Bảo vệ route bằng middleware (auth)
- Sử dụng Laravel Sanctum hoặc session tùy thiết kế

## Yêu cầu Copilot
- Tạo controller riêng cho Auth (ví dụ: AuthController)
- Sử dụng Request Validation cho dữ liệu đầu vào
- Trả response JSON rõ ràng (nếu API)
- Áp dụng middleware auth cho route sau khi đăng nhập
- Đảm bảo bảo mật: hash mật khẩu, kiểm tra user tồn tại
