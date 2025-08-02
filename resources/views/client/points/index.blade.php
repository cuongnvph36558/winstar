@extends('layouts.client')

@section('title', 'Điểm Tích Lũy')

@section('content')
<div class="points-container">
    <!-- Header -->
    <div class="points-header">
        <div class="container">
            <div class="header-content">
                <div class="breadcrumb-simple">
                    <a href="{{ route('client.home') }}">Trang chủ</a>
                    <span>/</span>
                    <span>Điểm tích lũy</span>
                </div>
                <h1 class="page-title">
                    <i class="fa fa-star"></i>
                    Điểm Tích Lũy
                </h1>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Points Summary -->
        <div class="points-summary">
            <div class="summary-card">
                <div class="summary-item">
                    <div class="item-icon">
                        <i class="fa fa-coins"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-number">{{ number_format($pointStats['total_points']) }}</div>
                        <div class="item-label">Điểm hiện có</div>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="item-icon">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-number">{{ number_format($pointStats['earned_points']) }}</div>
                        <div class="item-label">Điểm đã tích</div>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="item-icon">
                        <i class="fa fa-minus"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-number">{{ number_format($pointStats['used_points']) }}</div>
                        <div class="item-label">Điểm đã dùng</div>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="item-icon">
                        <i class="fa fa-crown"></i>
                    </div>
                    <div class="item-content">
                        <div class="item-number">{{ $pointStats['vip_level'] }}</div>
                        <div class="item-label">Level VIP</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Codes Section -->
        <div class="discount-section">
            <div class="section-title">
                <h2>Mã Giảm Giá</h2>
                <p>Đổi điểm lấy mã giảm giá hoặc sử dụng mã đã có</p>
            </div>

            <!-- Available Discount Codes -->
            <div class="available-codes">
                <h3>Mã Giảm Giá Có Sẵn</h3>
                @if($availableCoupons->count() > 0)
                    <div class="codes-grid">
                        @foreach($availableCoupons as $coupon)
                            <div class="code-card available">
                                <div class="code-header">
                                    <h4>{{ $coupon->name }}</h4>
                                    <span class="status-tag available">
                                        Có thể nhận
                                    </span>
                                </div>
                                
                                <div class="code-details">
                                    <div class="detail-line">
                                        <span class="label">Mã:</span>
                                        <span class="value">{{ $coupon->code }}</span>
                                    </div>
                                    <div class="detail-line">
                                        <span class="label">Giảm giá:</span>
                                        <span class="value discount">
                                            @if($coupon->discount_type === 'percentage')
                                                {{ number_format($coupon->discount_value, 0) }}%
                                            @else
                                                {{ number_format($coupon->discount_value) }}đ
                                            @endif
                                        </span>
                                    </div>
                                    @if($coupon->min_order_value > 0)
                                        <div class="detail-line">
                                            <span class="label">Đơn tối thiểu:</span>
                                            <span class="value">{{ number_format($coupon->min_order_value) }}đ</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="code-action">
                                    <form action="{{ route('client.points.exchange-coupon') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                        <button type="submit" class="btn-exchange" 
                                                onclick="return confirm('Nhận mã giảm giá này?')">
                                            Nhận Mã
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-message">
                        <i class="fa fa-ticket"></i>
                        <p>Hiện tại không có mã giảm giá nào</p>
                    </div>
                @endif
            </div>

            <!-- My Discount Codes -->
            <div class="my-codes">
                <h3>Mã Giảm Giá Của Tôi</h3>
                @if($userCoupons->count() > 0)
                    <div class="codes-list">
                        @foreach($userCoupons as $userCoupon)
                            <div class="my-code-item {{ $userCoupon->used_at ? 'used' : 'active' }}">
                                <div class="code-info">
                                    <div class="code-main">
                                        <div class="code-name">{{ $userCoupon->coupon->name ?? 'N/A' }}</div>
                                        <div class="code-value">
                                            <code>{{ $userCoupon->coupon->code }}</code>
                                            @if(!$userCoupon->used_at)
                                                <button class="btn-copy" onclick="copyToClipboard('{{ $userCoupon->coupon->code }}')">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="code-meta">
                                        <span class="discount-value">
                                            @if($userCoupon->coupon && $userCoupon->coupon->discount_type === 'percentage')
                                                Giảm {{ number_format($userCoupon->coupon->discount_value, 0) }}%
                                            @elseif($userCoupon->coupon)
                                                Giảm {{ number_format($userCoupon->coupon->discount_value) }}đ
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                        <span class="code-status {{ $userCoupon->used_at ? 'used' : 'active' }}">
                                            @if($userCoupon->used_at)
                                                <i class="fa fa-times-circle"></i> Đã sử dụng
                                            @else
                                                <i class="fa fa-check-circle"></i> Có thể sử dụng
                                            @endif
                                        </span>
                                        @if($userCoupon->coupon && $userCoupon->coupon->end_date)
                                            <span class="expiry-date">
                                                Hết hạn: {{ $userCoupon->coupon->end_date->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-message">
                        <i class="fa fa-ticket"></i>
                        <p>Bạn chưa có mã giảm giá nào</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Transaction History -->
        <div class="history-section">
            <div class="section-title">
                <h2>Lịch Sử Điểm</h2>
            </div>
            
            @if(count($pointHistory) > 0)
                <div class="history-list">
                    @foreach($pointHistory as $transaction)
                        <div class="history-item">
                            <div class="history-icon {{ $transaction['type'] }}">
                                @switch($transaction['type'])
                                    @case('earn')
                                        <i class="fa fa-plus"></i>
                                        @break
                                    @case('use')
                                        <i class="fa fa-minus"></i>
                                        @break
                                    @case('bonus')
                                        <i class="fa fa-star"></i>
                                        @break
                                    @default
                                        <i class="fa fa-circle"></i>
                                @endswitch
                            </div>
                            <div class="history-content">
                                <div class="history-main">
                                    <div class="history-desc">{{ $transaction['description'] }}</div>
                                    <div class="history-points {{ $transaction['points'] > 0 ? 'positive' : 'negative' }}">
                                        {{ $transaction['points'] > 0 ? '+' : '' }}{{ number_format($transaction['points']) }}
                                    </div>
                                </div>
                                <div class="history-date">
                                    {{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-message">
                    <i class="fa fa-history"></i>
                    <p>Chưa có giao dịch điểm nào</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('Đã copy mã: ' + text);
    }, function(err) {
        showToast('Không thể copy mã. Vui lòng thử lại!', 'error');
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fa fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}
</script>

<style>
/* Points Page Styles */
.points-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding-bottom: 50px;
}

/* Header */
.points-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 0;
    margin-bottom: 30px;
}

.header-content {
    text-align: center;
}

.breadcrumb-simple {
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.breadcrumb-simple a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-simple a:hover {
    color: white;
}

.breadcrumb-simple span {
    margin: 0 10px;
    color: rgba(255, 255, 255, 0.6);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.page-title i {
    color: #ffd700;
}

/* Points Summary */
.points-summary {
    margin-bottom: 40px;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: transform 0.2s ease;
}

.summary-item:hover {
    transform: translateY(-2px);
}

.item-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.summary-item:nth-child(1) .item-icon { background: #28a745; }
.summary-item:nth-child(2) .item-icon { background: #17a2b8; }
.summary-item:nth-child(3) .item-icon { background: #dc3545; }
.summary-item:nth-child(4) .item-icon { background: #ffc107; }

.item-number {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.item-label {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Discount Section */
.discount-section {
    margin-bottom: 40px;
}

.section-title {
    text-align: center;
    margin-bottom: 30px;
}

.section-title h2 {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.section-title p {
    color: #6c757d;
    font-size: 1.1rem;
    margin: 0;
}

.available-codes,
.my-codes {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.available-codes h3,
.my-codes h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f8f9fa;
}

/* Codes Grid */
.codes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.code-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.code-card.available {
    border-color: #28a745;
    background: #f8fff9;
}

.code-card.unavailable {
    border-color: #e9ecef;
    background: #f8f9fa;
    opacity: 0.7;
}

.code-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.code-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.code-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.status-tag {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-tag.available {
    background: #d4edda;
    color: #155724;
}

.status-tag.unavailable {
    background: #f8f9fa;
    color: #6c757d;
}

.code-details {
    margin-bottom: 20px;
}

.detail-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.detail-line:last-child {
    border-bottom: none;
}

.detail-line .label {
    color: #6c757d;
    font-size: 0.9rem;
}

.detail-line .value {
    font-weight: 600;
    color: #333;
}

.detail-line .value.discount {
    color: #28a745;
}

.code-action {
    text-align: center;
}

.btn-exchange {
    background: #28a745;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-exchange:hover:not(.disabled) {
    background: #218838;
    transform: translateY(-2px);
}

.btn-exchange.disabled {
    background: #6c757d;
    cursor: not-allowed;
}

/* My Codes List */
.codes-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.my-code-item {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s ease;
}

.my-code-item.active {
    border-color: #28a745;
    background: #f8fff9;
}

.my-code-item.used {
    border-color: #6c757d;
    background: #f8f9fa;
    opacity: 0.7;
}

.my-code-item.expired {
    border-color: #dc3545;
    background: #fff5f5;
    opacity: 0.7;
}

.code-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.code-main {
    flex: 1;
}

.code-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.code-value {
    display: flex;
    align-items: center;
    gap: 10px;
}

.code-value code {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #333;
}

.btn-copy {
    background: #007bff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-copy:hover {
    background: #0056b3;
}

.code-meta {
    display: flex;
    flex-direction: column;
    gap: 5px;
    align-items: flex-end;
    text-align: right;
}

.discount-value {
    font-weight: 600;
    color: #28a745;
    font-size: 0.9rem;
}

.code-status {
    font-size: 0.8rem;
    font-weight: 500;
}

.code-status.active {
    color: #28a745;
}

.code-status.used {
    color: #6c757d;
}

.code-status.expired {
    color: #dc3545;
}

.expiry-date {
    font-size: 0.8rem;
    color: #6c757d;
}

/* History Section */
.history-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.history-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.history-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: background 0.3s ease;
}

.history-item:hover {
    background: #e9ecef;
}

.history-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.history-icon.earn {
    background: #28a745;
}

.history-icon.use {
    background: #dc3545;
}

.history-icon.bonus {
    background: #ffc107;
}

.history-content {
    flex: 1;
}

.history-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.history-desc {
    font-weight: 500;
    color: #333;
}

.history-points {
    font-weight: 600;
    font-size: 1.1rem;
}

.history-points.positive {
    color: #28a745;
}

.history-points.negative {
    color: #dc3545;
}

.history-date {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Empty Messages */
.empty-message {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-message i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-message p {
    margin: 0;
    font-size: 1.1rem;
}

/* Toast Notifications */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 8px;
    padding: 15px 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 9999;
    max-width: 300px;
}

.toast.show {
    transform: translateX(0);
}

.toast.success {
    border-left: 4px solid #28a745;
}

.toast.error {
    border-left: 4px solid #dc3545;
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toast-content i {
    font-size: 1.1rem;
}

.toast.success .toast-content i {
    color: #28a745;
}

.toast.error .toast-content i {
    color: #dc3545;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .summary-card {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        padding: 20px;
    }
    
    .summary-item {
        padding: 15px;
    }
    
    .item-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .item-number {
        font-size: 1.2rem;
    }
    
    .codes-grid {
        grid-template-columns: 1fr;
    }
    
    .code-info {
        flex-direction: column;
        gap: 15px;
    }
    
    .code-meta {
        align-items: flex-start;
        text-align: left;
    }
    
    .section-title h2 {
        font-size: 1.5rem;
    }
    
    .available-codes,
    .my-codes,
    .history-section {
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .points-header {
        padding: 30px 0;
    }
    
    .summary-card {
        grid-template-columns: 1fr;
    }
    
    .code-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .history-main {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
@endsection
