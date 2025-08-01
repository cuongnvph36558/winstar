# Banner Background Options

## Tổng quan
Đã tạo 3 phiên bản nền banner cho website:

### 1. Banner Background với hiệu ứng phức tạp (`banner-background.css`)
- **Gradient động**: Nền gradient 5 màu với animation chuyển động
- **Particle effects**: 9 hạt nổi với animation bay lên
- **Glass morphism**: Hiệu ứng kính mờ cho nội dung
- **Mouse interaction**: Các hạt phản ứng với chuột
- **Typing effect**: Hiệu ứng đánh chữ cho text
- **Ripple effect**: Hiệu ứng gợn sóng khi click

### 2. Banner Background đơn giản (`simple-banner-background.css`)
- **Gradient tĩnh**: Nền gradient 3 màu đơn giản
- **Pattern overlay**: Họa tiết nhẹ nhàng
- **Glass morphism**: Hiệu ứng kính mờ nhẹ
- **Responsive**: Tối ưu cho mobile

### 3. Banner Background tối màu (`dark-banner-background.css`) ⭐ **ĐANG SỬ DỤNG**
- **Nền tối**: Gradient từ đen đến xám đậm
- **Không có nút điều hướng**: Ẩn hoàn toàn các nút slider
- **Glass morphism tối**: Hiệu ứng kính mờ với nền tối
- **Text trắng**: Chữ màu trắng với shadow đậm
- **Animation nhẹ**: Gradient shift chậm và tinh tế

## Cách sử dụng

### Hiện tại đang sử dụng:
- File: `dark-banner-background.css` (nền tối, không nút điều hướng)
- JavaScript: `banner-effects.js` (tương tác)

### Để chuyển sang nền khác:
1. Thay đổi trong `resources/views/layouts/client.blade.php`:

**Chuyển sang nền đơn giản:**
```html
<link href="{{ asset('client/assets/css/simple-banner-background.css') }}" rel="stylesheet">
```

**Chuyển sang nền phức tạp (có particles):**
```html
<link href="{{ asset('client/assets/css/banner-background.css') }}" rel="stylesheet">
```

**Chuyển về nền tối (hiện tại):**
```html
<link href="{{ asset('client/assets/css/dark-banner-background.css') }}" rel="stylesheet">
```

### Để tắt JavaScript effects:
1. Comment hoặc xóa dòng trong `resources/views/layouts/client.blade.php`:
```html
<!-- Comment dòng này -->
<!-- <script src="{{ asset('client/assets/js/banner-effects.js') }}"></script> -->
```

## Tính năng

### CSS Effects:
- ✅ Gradient backgrounds
- ✅ Glass morphism
- ✅ Particle animations
- ✅ Responsive design
- ✅ Hover effects
- ✅ Text shadows

### JavaScript Effects:
- ✅ Parallax scrolling
- ✅ Mouse interaction
- ✅ Typing animation
- ✅ Ripple effects
- ✅ Floating animation
- ✅ Glow effects

## Tùy chỉnh

### Thay đổi màu gradient:
Chỉnh sửa trong file CSS:
```css
background: linear-gradient(135deg, 
    #667eea 0%,    /* Màu 1 */
    #764ba2 25%,   /* Màu 2 */
    #f093fb 50%,   /* Màu 3 */
    #f5576c 75%,   /* Màu 4 */
    #4facfe 100%); /* Màu 5 */
```

### Thay đổi tốc độ animation:
```css
animation: gradientShift 15s ease infinite; /* 15s = tốc độ */
```

### Thêm/bớt particles:
Chỉnh sửa trong `home.blade.php`:
```html
<div class="banner-particles">
    <div class="particle"></div>
    <!-- Thêm hoặc bớt các div particle -->
</div>
```

## Performance
- **Desktop**: Tất cả effects hoạt động mượt mà
- **Mobile**: Particles bị ẩn, chỉ giữ lại gradient và glass morphism
- **Browser support**: Modern browsers (Chrome, Firefox, Safari, Edge)

## Troubleshooting
1. **Effects không hoạt động**: Kiểm tra console để xem lỗi JavaScript
2. **CSS không load**: Kiểm tra đường dẫn file CSS
3. **Performance chậm**: Chuyển sang `simple-banner-background.css`
4. **Mobile issues**: Particles tự động ẩn trên mobile

## Files đã tạo:
- `public/client/assets/css/banner-background.css`
- `public/client/assets/css/simple-banner-background.css`
- `public/client/assets/css/dark-banner-background.css` ⭐ **ĐANG SỬ DỤNG**
- `public/client/assets/js/banner-effects.js`
- `BANNER_BACKGROUND_README.md` 