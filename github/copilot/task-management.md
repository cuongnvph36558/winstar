# 02 - Task Management

## Tính năng
- CRUD công việc: tạo, sửa, xóa, xem
- Gán người thực hiện
- Chọn trạng thái: chờ xử lý, đang làm, hoàn thành

## Yêu cầu Copilot
- Sử dụng Route Resource và Controller theo chuẩn Laravel
- Tách logic xử lý sang Service
- Sử dụng migration có quan hệ đến bảng users (foreign key)
- Trả về response phù hợp với JSON hoặc view
- Mỗi Task cần có các field: tiêu đề, mô tả, trạng thái, deadline, người được gán
