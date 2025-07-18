# 03 - User Roles & Permissions

## Tính năng
- Có hai vai trò: admin và nhân viên
- Admin có toàn quyền
- Nhân viên chỉ thấy công việc của mình

## Yêu cầu Copilot
- Tạo enum hoặc constants cho role
- Middleware kiểm tra quyền truy cập
- Kiểm soát route hoặc controller theo role
- Truy vấn dữ liệu có điều kiện theo user_id nếu là nhân viên
