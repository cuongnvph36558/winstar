# Hướng Dẫn Import/Export Sản Phẩm & Biến Thể

## Tổng Quan

Hệ thống hỗ trợ import/export sản phẩm và biến thể sản phẩm thông qua file Excel để tăng hiệu quả quản lý dữ liệu hàng loạt.

## Tính Năng

### 1. Import Sản Phẩm
- Import danh sách sản phẩm từ file Excel
- Tự động tạo sản phẩm với thông tin cơ bản
- Validation dữ liệu trước khi import
- Bỏ qua các dòng có lỗi và tiếp tục import

### 2. Import Biến Thể Sản Phẩm
- Import biến thể cho các sản phẩm đã tồn tại
- Hỗ trợ màu sắc và dung lượng
- Tự động tính toán giá khuyến mãi
- Validation logic nghiêm ngặt

### 3. Export Dữ Liệu
- Xuất danh sách sản phẩm hiện tại
- Xuất danh sách biến thể sản phẩm
- Định dạng Excel với styling đẹp mắt

### 4. Template Mẫu
- Cung cấp template Excel mẫu
- Dữ liệu tham khảo để người dùng hiểu cấu trúc
- Hướng dẫn chi tiết cho từng cột

## Cách Sử Dụng

### Bước 1: Truy Cập Trang Import
1. Vào Admin Panel → Sản Phẩm
2. Click nút "Import/Export"
3. Chọn loại import cần thực hiện

### Bước 2: Tải Template Mẫu
1. Click "Tải Template" để download file mẫu
2. Mở file Excel và xem cấu trúc dữ liệu
3. Điền dữ liệu theo mẫu

### Bước 3: Import Dữ Liệu
1. Chọn file Excel đã chuẩn bị
2. Click "Import" để bắt đầu
3. Kiểm tra kết quả import

## Cấu Trúc File Excel

### File Sản Phẩm (template_products.xlsx)

| Cột | Tên | Bắt Buộc | Mô Tả |
|-----|-----|----------|-------|
| A | name | ✅ | Tên sản phẩm |
| B | category_name | ✅ | Tên danh mục (phải tồn tại) |
| C | description | ❌ | Mô tả sản phẩm |
| D | status | ❌ | Trạng thái (active/inactive) |

**Ví dụ:**
```
name,category_name,description,status
iPhone 15 Pro,Điện thoại,iPhone 15 Pro mới nhất từ Apple,active
Samsung Galaxy S24,Điện thoại,Samsung Galaxy S24 Ultra,active
```

### File Biến Thể (template_variants.xlsx)

| Cột | Tên | Bắt Buộc | Mô Tả |
|-----|-----|----------|-------|
| A | product_name | ✅ | Tên sản phẩm (phải tồn tại) |
| B | price | ✅ | Giá sản phẩm (số) |
| C | promotion_price | ❌ | Giá khuyến mãi (phải < price) |
| D | stock_quantity | ✅ | Số lượng tồn kho (số nguyên) |
| E | color_name | ❌ | Tên màu (phải tồn tại) |
| F | storage_capacity | ❌ | Dung lượng (phải tồn tại) |

**Ví dụ:**
```
product_name,price,promotion_price,stock_quantity,color_name,storage_capacity
iPhone 15 Pro,25000000,23000000,50,Titan tự nhiên,256GB
iPhone 15 Pro,28000000,26000000,30,Titan tự nhiên,512GB
```

## Quy Tắc Validation

### Sản Phẩm
- Tên sản phẩm không được trống và tối đa 255 ký tự
- Danh mục phải tồn tại trong hệ thống
- Trạng thái phải là: active, inactive, 1, 0, true, false, yes, no

### Biến Thể
- Tên sản phẩm phải tồn tại trong hệ thống
- Giá phải là số dương
- Giá khuyến mãi phải nhỏ hơn giá gốc
- Số lượng phải là số nguyên dương
- Màu sắc và dung lượng phải tồn tại (nếu có)

## Xử Lý Lỗi

### Các Loại Lỗi Thường Gặp

1. **Lỗi Validation**
   - Dữ liệu không đúng định dạng
   - Thiếu thông tin bắt buộc
   - Giá trị không hợp lệ

2. **Lỗi Tham Chiếu**
   - Danh mục không tồn tại
   - Sản phẩm không tồn tại (cho biến thể)
   - Màu sắc/dung lượng không tồn tại

3. **Lỗi Logic**
   - Giá khuyến mãi >= giá gốc
   - Số lượng âm

### Cách Xử Lý

- Các dòng có lỗi sẽ được bỏ qua
- Import tiếp tục với các dòng hợp lệ
- Kiểm tra log để xem chi tiết lỗi
- Sửa lỗi và import lại

## Lưu Ý Quan Trọng

### Trước Khi Import
1. **Backup dữ liệu** hiện tại
2. **Kiểm tra template** và dữ liệu tham khảo
3. **Test với file nhỏ** trước khi import lớn
4. **Đảm bảo định dạng** file Excel đúng

### Trong Quá Trình Import
1. **Không refresh trang** trong khi import
2. **Chờ hoàn thành** trước khi thực hiện thao tác khác
3. **Kiểm tra kết quả** sau khi import

### Sau Khi Import
1. **Kiểm tra dữ liệu** đã import
2. **Upload ảnh** cho sản phẩm/biến thể nếu cần
3. **Cập nhật thông tin** bổ sung nếu cần

## Troubleshooting

### Import Không Thành Công
1. Kiểm tra định dạng file Excel
2. Đảm bảo header row đúng
3. Kiểm tra encoding (UTF-8)
4. Xem log lỗi chi tiết

### Dữ Liệu Không Đúng
1. Kiểm tra tên danh mục/màu sắc/dung lượng
2. Đảm bảo định dạng số đúng
3. Kiểm tra logic validation

### Hiệu Suất Chậm
1. Chia nhỏ file import
2. Import từng phần
3. Kiểm tra server resources

## API Endpoints

### Import
- `POST /admin/product/import/products` - Import sản phẩm
- `POST /admin/product/import/variants` - Import biến thể

### Export
- `GET /admin/product/export/products` - Xuất sản phẩm
- `GET /admin/product/export/variants` - Xuất biến thể

### Template
- `GET /admin/product/template/products` - Template sản phẩm
- `GET /admin/product/template/variants` - Template biến thể

## Hỗ Trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra log hệ thống
2. Xem hướng dẫn này
3. Liên hệ admin để được hỗ trợ
