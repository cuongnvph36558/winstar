@extends('layouts.client')

@section('title', 'Mã Giảm Giá Miễn Phí')

@section('content')
<div class="points-container">
    <!-- Header -->
    <div class="points-header">
        <div class="container">
            <div class="header-content">
                <div class="breadcrumb-simple">
                    <a href="{{ route('client.home') }}">Trang chủ</a>
                    <span>/</span>
                    <a href="{{ route('client.points.index') }}">Điểm tích lũy</a>
                    <span>/</span>
                    <span>Mã giảm giá miễn phí</span>
                </div>
                <h1 class="page-title">
                    <i class="fa fa-gift"></i>
                    Mã Giảm Giá Miễn Phí
                </h1>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Coupons Section -->
        <div class="coupons-section">
            <div class="section-title">
                <h2>Mã Giảm Giá Miễn Phí</h2>
                <p>Những mã giảm giá bạn có thể copy và sử dụng ngay mà không cần điểm tích lũy</p>
            </div>
            
            @if($availableCoupons->count() > 0)
                <div class="coupons-grid">
                    @foreach($availableCoupons as $coupon)
                        <div class="coupon-card">
                            <div class="coupon-header">
                                <div class="coupon-icon">
                                    <i class="fa fa-gift"></i>
                                </div>
                                <div class="coupon-code">
                                    <span class="code-text">{{ $coupon->code }}</span>
                                </div>
                            </div>
                            
                            <div class="coupon-body">
                                <div class="coupon-title">
                                    <h3>{{ $coupon->name ?? 'Mã giảm giá' }}</h3>
                                </div>
                                
                                @if($coupon->description)
                                    <div class="coupon-description">
                                        <p>{{ $coupon->description }}</p>
                                    </div>
                                @endif
                                
                                <div class="coupon-details">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fa fa-percent"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-value">
                                                @if($coupon->discount_type === 'percentage')
                                                    {{ number_format($coupon->discount_value, 0) }}%
                                                @else
                                                    {{ number_format($coupon->discount_value) }}đ
                                                @endif
                                            </div>
                                            <div class="detail-label">Giảm giá</div>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-value">
                                                @if($coupon->min_order_value)
                                                    {{ number_format($coupon->min_order_value) }}đ
                                                @else
                                                    Không giới hạn
                                                @endif
                                            </div>
                                            <div class="detail-label">Đơn tối thiểu</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="coupon-footer">
                                    <div class="expiry-info">
                                        <i class="fa fa-calendar"></i>
                                        <span>Hết hạn: {{ $coupon->end_date->format('d/m/Y') }}</span>
                                    </div>
                                    <button class="btn-copy-code" onclick="copyToClipboard('{{ $coupon->code }}')">
                                        <i class="fa fa-copy"></i>
                                        Copy mã
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa fa-gift"></i>
                    </div>
                    <div class="empty-content">
                        <h3>Hiện tại không có mã giảm giá miễn phí</h3>
                        <p>Hãy quay lại sau để nhận mã giảm giá mới!</p>
                        <a href="{{ route('client.points.index') }}" class="btn-back">
                            <i class="fa fa-arrow-left"></i>
                            Quay lại điểm tích lũy
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="help-section">
            <div class="section-title">
                <h2>Hướng Dẫn Sử Dụng</h2>
                <p>Thông tin hữu ích về cách sử dụng mã giảm giá</p>
            </div>
            
            <div class="help-grid">
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="help-content">
                        <h4>Mã giảm giá miễn phí</h4>
                        <p>Những mã giảm giá này hoàn toàn miễn phí, bạn có thể copy và sử dụng ngay mà không cần điểm tích lũy!</p>
                    </div>
                </div>
                
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="help-content">
                        <h4>Cách sử dụng</h4>
                        <ol>
                            <li>Copy mã giảm giá từ danh sách</li>
                            <li>Vào trang thanh toán đơn hàng</li>
                            <li>Nhập mã vào ô "Mã giảm giá"</li>
                            <li>Nhấn "Áp dụng" để sử dụng</li>
                        </ol>
                    </div>
                </div>
                
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-bell"></i>
                    </div>
                    <div class="help-content">
                        <h4>Lưu ý quan trọng</h4>
                        <ul>
                            <li>Mỗi mã chỉ sử dụng được 1 lần</li>
                            <li>Mã có thời hạn sử dụng</li>
                            <li>Áp dụng cho đơn hàng đủ điều kiện</li>
                            <li>Mã miễn phí không cần điểm để nhận</li>
                        </ul>
                    </div>
                </div>
                
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-heart"></i>
                    </div>
                    <div class="help-content">
                        <h4>Mẹo hữu ích</h4>
                        <p>Hãy thường xuyên kiểm tra để nhận mã giảm giá miễn phí mới! Các mã giảm giá thường được cập nhật theo mùa và sự kiện đặc biệt.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Coupons Page Styles */
.coupons-section {
    margin-bottom: 60px;
}

.coupons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.coupon-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid #e9ecef;
}

.coupon-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.coupon-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.coupon-icon {
    font-size: 24px;
}

.coupon-code {
    background: rgba(255,255,255,0.2);
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 14px;
}

.coupon-body {
    padding: 25px;
}

.coupon-title h3 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 18px;
    font-weight: 600;
}

.coupon-description {
    margin-bottom: 20px;
}

.coupon-description p {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

.coupon-details {
    display: grid;
    gap: 20px;
    margin-bottom: 25px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 16px;
}

.detail-content {
    flex: 1;
}

.detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
}

.detail-label {
    font-size: 12px;
    color: #666;
}

.coupon-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.expiry-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 14px;
}

.btn-copy-code {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-copy-code:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 80px;
    color: #ddd;
    margin-bottom: 30px;
}

.empty-content h3 {
    color: #666;
    margin-bottom: 15px;
    font-size: 24px;
}

.empty-content p {
    color: #999;
    margin-bottom: 30px;
    font-size: 16px;
}

.btn-back {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.help-section {
    margin-bottom: 60px;
}

.help-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.help-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.help-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-bottom: 20px;
}

.help-content h4 {
    color: #333;
    margin-bottom: 15px;
    font-size: 18px;
    font-weight: 600;
}

.help-content p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0;
}

.help-content ol,
.help-content ul {
    color: #666;
    line-height: 1.6;
    padding-left: 20px;
}

.help-content li {
    margin-bottom: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .coupons-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .coupon-details {
        gap: 15px;
    }
    
    .coupon-footer {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .help-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .help-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Hiển thị thông báo thành công
        const toast = document.createElement('div');
        toast.className = 'toast-notification success';
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fa fa-check"></i>
                <span>Đã copy mã giảm giá: <strong>${text}</strong></span>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(function(err) {
        // Hiển thị thông báo lỗi
        alert('Không thể copy mã giảm giá. Vui lòng thử lại!');
    });
}
</script>
@endsection 