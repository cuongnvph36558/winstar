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
                <p class="section-subtitle">Đổi điểm tích lũy để nhận mã giảm giá độc quyền theo level VIP của bạn</p>
                
                @if($availableCoupons->count() > 0)
                    <div class="codes-grid">
                        @foreach($availableCoupons as $coupon)
                            <div class="code-card available">
                                <div class="code-header">
                                    <div class="header-left">
                                        <h4>{{ $coupon->name }}</h4>
                                        <div class="vip-requirement">
                                            <i class="fa fa-crown"></i>
                                            <span>Level {{ $coupon->vip_level ?? 'Tất cả' }}</span>
                                        </div>
                                    </div>
                                    <div class="header-right">
                                        <span class="status-tag available">
                                            <i class="fa fa-check-circle"></i>
                                            Có thể nhận
                                        </span>
                                        <div class="points-required">
                                            <i class="fa fa-star"></i>
                                            <span>{{ number_format($coupon->exchange_points ?? 0) }} điểm</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="code-details">
                                    <div class="detail-row">
                                        <div class="detail-item">
                                            <i class="fa fa-percent text-success"></i>
                                            <span class="detail-label">Giá trị giảm:</span>
                                            <span class="detail-value discount">
                                                @if($coupon->discount_type === 'percentage')
                                                    {{ number_format($coupon->discount_value, 0) }}%
                                                    @if($coupon->max_discount)
                                                        <br><small>(Tối đa {{ number_format($coupon->max_discount) }}đ)</small>
                                                    @endif
                                                @else
                                                    {{ number_format($coupon->discount_value) }}đ
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fa fa-shopping-cart text-info"></i>
                                            <span class="detail-label">Đơn tối thiểu:</span>
                                            <span class="detail-value">
                                                @if($coupon->min_order_value > 0)
                                                    {{ number_format($coupon->min_order_value) }}đ
                                                @else
                                                    Không giới hạn
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-item">
                                            <i class="fa fa-calendar text-warning"></i>
                                            <span class="detail-label">Hiệu lực:</span>
                                            <span class="detail-value">
                                                @if($coupon->start_date && $coupon->end_date)
                                                    {{ $coupon->start_date->format('d/m/Y') }} - {{ $coupon->end_date->format('d/m/Y') }}
                                                @else
                                                    Vĩnh viễn
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fa fa-clock-o text-primary"></i>
                                            <span class="detail-label">Thời hạn sử dụng:</span>
                                            <span class="detail-value">
                                                @if($coupon->validity_days)
                                                    {{ $coupon->validity_days }} ngày
                                                @else
                                                    30 ngày
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="coupon-notice">
                                        <i class="fa fa-eye-slash text-muted"></i>
                                        <span>Mã giảm giá sẽ được hiển thị trong phần "Mã Giảm Giá Của Tôi" sau khi đổi thành công</span>
                                    </div>
                                    @if($coupon->description)
                                        <div class="coupon-description">
                                            <i class="fa fa-info-circle text-info"></i>
                                            <span>{{ $coupon->description }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="code-action">
                                    <div class="action-info">
                                        <div class="current-points">
                                            <i class="fa fa-star text-warning"></i>
                                            <span>Điểm hiện tại: {{ number_format($pointStats['total_points'] ?? 0) }}</span>
                                        </div>
                                        @if(($coupon->exchange_points ?? 0) > ($pointStats['total_points'] ?? 0))
                                            <div class="points-needed">
                                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                                <span>Cần thêm {{ number_format(($coupon->exchange_points ?? 0) - ($pointStats['total_points'] ?? 0)) }} điểm</span>
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('client.points.exchange-coupon') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                        <button type="submit" class="btn-exchange {{ ($coupon->exchange_points ?? 0) > ($pointStats['total_points'] ?? 0) ? 'disabled' : '' }}" 
                                                {{ ($coupon->exchange_points ?? 0) > ($pointStats['total_points'] ?? 0) ? 'disabled' : '' }}
                                                onclick="return confirm('Đổi {{ number_format($coupon->exchange_points ?? 0) }} điểm để nhận mã giảm giá này?')">
                                            @if(($coupon->exchange_points ?? 0) > ($pointStats['total_points'] ?? 0))
                                                <i class="fa fa-lock"></i>
                                                Không đủ điểm
                                            @else
                                                <i class="fa fa-exchange"></i>
                                                Đổi {{ number_format($coupon->exchange_points ?? 0) }} điểm
                                            @endif
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
                    <!-- Tab Navigation -->
                    <div class="codes-tabs">
                        <button class="tab-btn active" onclick="showCouponTab('available')">
                            <i class="fa fa-check-circle"></i>
                            Có thể sử dụng 
                            <span class="tab-count">{{ $userCoupons->where('used_at', null)->count() }}</span>
                        </button>
                        <button class="tab-btn" onclick="showCouponTab('used')">
                            <i class="fa fa-times-circle"></i>
                            Đã sử dụng
                            <span class="tab-count">{{ $userCoupons->where('used_at', '!=', null)->count() }}</span>
                        </button>
                    </div>

                    <!-- Available Coupons Tab -->
                    <div id="available-coupons" class="codes-tab-content active">
                        @php
                            $availableCoupons = $userCoupons->where('used_at', null);
                        @endphp
                        
                        @if($availableCoupons->count() > 0)
                            <div class="codes-list">
                                @foreach($availableCoupons as $userCoupon)
                                    <div class="my-code-item active">
                                        <div class="code-info">
                                            <div class="code-main">
                                                <div class="code-name">
                                                    @if($userCoupon->coupon && $userCoupon->coupon->name)
                                                        {{ $userCoupon->coupon->name }}
                                                    @else
                                                        <span class="text-muted">Mã giảm giá</span>
                                                    @endif
                                                </div>
                                                <div class="code-value">
                                                    <code>{{ $userCoupon->coupon->code }}</code>
                                                    <button class="btn-copy" onclick="copyToClipboard('{{ $userCoupon->coupon->code }}')">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="code-details">
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <i class="fa fa-percent text-success"></i>
                                                        <span class="detail-label">Giá trị giảm:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->discount_type === 'percentage')
                                                                {{ number_format($userCoupon->coupon->discount_value, 0) }}%
                                                            @elseif($userCoupon->coupon)
                                                                {{ number_format($userCoupon->coupon->discount_value) }}đ
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <i class="fa fa-shopping-cart text-info"></i>
                                                        <span class="detail-label">Đơn tối thiểu:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->min_order_value)
                                                                {{ number_format($userCoupon->coupon->min_order_value) }}đ
                                                            @else
                                                                Không giới hạn
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <i class="fa fa-calendar-plus text-primary"></i>
                                                        <span class="detail-label">Ngày bắt đầu:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->start_date)
                                                                {{ $userCoupon->coupon->start_date->format('d/m/Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <i class="fa fa-calendar-times text-danger"></i>
                                                        <span class="detail-label">Ngày kết thúc:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->end_date)
                                                                {{ $userCoupon->coupon->end_date->format('d/m/Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="code-meta">
                                                <span class="code-status active">
                                                    <i class="fa fa-check-circle"></i> Có thể sử dụng
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-message">
                                <i class="fa fa-ticket"></i>
                                <p>Không có mã giảm giá nào có thể sử dụng</p>
                            </div>
                        @endif
                    </div>

                    <!-- Used Coupons Tab -->
                    <div id="used-coupons" class="codes-tab-content">
                        @php
                            $usedCoupons = $userCoupons->where('used_at', '!=', null);
                        @endphp
                        
                        @if($usedCoupons->count() > 0)
                            <div class="codes-list">
                                @foreach($usedCoupons as $userCoupon)
                                    <div class="my-code-item used">
                                        <div class="code-info">
                                            <div class="code-main">
                                                <div class="code-name">
                                                    @if($userCoupon->coupon && $userCoupon->coupon->name)
                                                        {{ $userCoupon->coupon->name }}
                                                    @else
                                                        <span class="text-muted">Mã giảm giá</span>
                                                    @endif
                                                </div>
                                                <div class="code-value">
                                                    <code>{{ $userCoupon->coupon->code }}</code>
                                                </div>
                                            </div>
                                            <div class="code-details">
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <i class="fa fa-percent text-success"></i>
                                                        <span class="detail-label">Giá trị giảm:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->discount_type === 'percentage')
                                                                {{ number_format($userCoupon->coupon->discount_value, 0) }}%
                                                            @elseif($userCoupon->coupon)
                                                                {{ number_format($userCoupon->coupon->discount_value) }}đ
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <i class="fa fa-shopping-cart text-info"></i>
                                                        <span class="detail-label">Đơn tối thiểu:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->min_order_value)
                                                                {{ number_format($userCoupon->coupon->min_order_value) }}đ
                                                            @else
                                                                Không giới hạn
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <i class="fa fa-calendar-plus text-primary"></i>
                                                        <span class="detail-label">Ngày bắt đầu:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->start_date)
                                                                {{ $userCoupon->coupon->start_date->format('d/m/Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <i class="fa fa-calendar-times text-danger"></i>
                                                        <span class="detail-label">Ngày kết thúc:</span>
                                                        <span class="detail-value">
                                                            @if($userCoupon->coupon && $userCoupon->coupon->end_date)
                                                                {{ $userCoupon->coupon->end_date->format('d/m/Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="detail-row">
                                                    <div class="detail-item">
                                                        <i class="fa fa-clock-o text-warning"></i>
                                                        <span class="detail-label">Ngày sử dụng:</span>
                                                        <span class="detail-value">
                                                            {{ $userCoupon->used_at->format('d/m/Y H:i') }}
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        @if($userCoupon->order_id)
                                                            <i class="fa fa-shopping-bag text-info"></i>
                                                            <span class="detail-label">Đơn hàng:</span>
                                                            <span class="detail-value">
                                                                <a href="{{ route('client.order.show', $userCoupon->order_id) }}" class="text-primary">
                                                                    #{{ $userCoupon->order_id }}
                                                                </a>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="code-meta">
                                                <span class="code-status used">
                                                    <i class="fa fa-times-circle"></i> Đã sử dụng
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-message">
                                <i class="fa fa-check-circle"></i>
                                <p>Chưa có mã giảm giá nào được sử dụng</p>
                            </div>
                        @endif
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
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f8f9fa;
}

.section-subtitle {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 25px;
}

/* Codes Grid */
.codes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    align-items: stretch;
}

.code-card {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 25px;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.code-card.available {
    border-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
}

.code-card.unavailable {
    border-color: #e9ecef;
    background: #f8f9fa;
    opacity: 0.7;
}

.code-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.code-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.header-left h4 {
    margin: 0 0 8px 0;
    color: #212529;
    font-size: 1.3rem;
    font-weight: 700;
}

.vip-requirement {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #ffc107;
    font-weight: 600;
    font-size: 0.9rem;
}

.vip-requirement i {
    color: #ffc107;
}

.header-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.status-tag {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-tag.available {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.status-tag.unavailable {
    background: #f8f9fa;
    color: #6c757d;
}

.points-required {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #ffc107;
    font-weight: 700;
    font-size: 0.9rem;
}

.points-required i {
    color: #ffc107;
}

.coupon-description {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 15px;
    background: #e3f2fd;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 0.9rem;
    color: #1976d2;
    border-left: 4px solid #2196f3;
    min-height: 50px;
}

.coupon-description i {
    margin-top: 2px;
    flex-shrink: 0;
}

.coupon-notice {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 15px;
    background: #fff3cd;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 0.9rem;
    color: #856404;
    border-left: 4px solid #ffc107;
    min-height: 50px;
}

.coupon-notice i {
    flex-shrink: 0;
}

.code-details {
    margin-bottom: 20px;
    flex: 1;
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
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #e9ecef;
    margin-top: auto;
}

.action-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.current-points {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #495057;
    font-weight: 600;
    font-size: 0.9rem;
}

.points-needed {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #dc3545;
    font-weight: 600;
    font-size: 0.9rem;
}

.btn-exchange {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.btn-exchange:hover:not(.disabled) {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn-exchange.disabled {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    cursor: not-allowed;
    opacity: 0.7;
}

/* My Codes List */
.codes-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.my-code-item {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 25px;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}

.my-code-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.my-code-item.active {
    border-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.15);
}

.my-code-item.active:hover {
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
}

.my-code-item.used {
    border-color: #6c757d;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    opacity: 0.9;
}

.my-code-item.used:hover {
    opacity: 1;
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.2);
}

.my-code-item.expired {
    border-color: #dc3545;
    background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
    opacity: 0.8;
}

.code-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.code-main {
    flex: 1;
    padding: 15px 20px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    margin-bottom: 15px;
}

.code-name {
    font-weight: 700;
    color: #212529;
    font-size: 1.2rem;
    margin-bottom: 12px;
}

.code-name .text-muted {
    color: #6c757d !important;
    font-weight: 500;
}

.code-value {
    display: flex;
    align-items: center;
    gap: 12px;
}

.code-value code {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 1.1rem;
    border: none;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    letter-spacing: 1px;
}

.btn-copy {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.btn-copy:hover {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
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

/* Tab Styles for Coupons */
.codes-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

/* Code Details Styles */
.code-details {
    margin: 20px 0;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #007bff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 15px;
    align-items: start;
}

.detail-row:last-child {
    margin-bottom: 0;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 15px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    min-height: 60px;
}

.detail-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.detail-item i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    font-size: 1rem;
    white-space: nowrap;
    min-width: 100px;
}

.detail-value {
    color: #212529;
    font-weight: 700;
    font-size: 1rem;
    text-align: right;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    min-height: 100%;
}

.detail-value a {
    text-decoration: none;
    color: #007bff;
    font-weight: 700;
}

.detail-value a:hover {
    text-decoration: underline;
    color: #0056b3;
}

.detail-value small {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 400;
    margin-top: 2px;
    line-height: 1.2;
}

/* Responsive for detail rows */
@media (max-width: 768px) {
    .codes-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .detail-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .detail-item {
        padding: 15px;
        min-height: auto;
    }
    
    .detail-label {
        min-width: 80px;
        font-size: 0.95rem;
    }
    
    .detail-value {
        font-size: 0.95rem;
        text-align: left;
        align-items: flex-start;
    }
    
    .coupon-notice,
    .coupon-description {
        min-height: auto;
    }
}

.tab-btn {
    background: none;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-btn:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.tab-btn.active {
    background-color: #007bff;
    color: white;
}

.tab-count {
    background-color: rgba(255, 255, 255, 0.2);
    color: inherit;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: bold;
}

.tab-btn.active .tab-count {
    background-color: rgba(255, 255, 255, 0.3);
}

.codes-tab-content {
    display: none;
}

.codes-tab-content.active {
    display: block;
}

@media (max-width: 768px) {
    .codes-tabs {
        flex-direction: column;
        gap: 5px;
    }
    
    .tab-btn {
        justify-content: center;
    }
}
</style>

<script>
function showCouponTab(tabName) {
    // Ẩn tất cả tab content
    document.querySelectorAll('.codes-tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Bỏ active tất cả tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Hiển thị tab được chọn
    document.getElementById(tabName + '-coupons').classList.add('active');
    
    // Active button được chọn
    event.target.closest('.tab-btn').classList.add('active');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Hiển thị thông báo thành công
        showToast('Đã copy mã giảm giá: ' + text, 'success');
    }).catch(function(err) {
        // Hiển thị thông báo lỗi
        showToast('Không thể copy mã giảm giá. Vui lòng thử lại!', 'error');
    });
}

function showToast(message, type = 'success') {
    // Tạo toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fa fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Thêm vào body
    document.body.appendChild(toast);
    
    // Hiển thị toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection
