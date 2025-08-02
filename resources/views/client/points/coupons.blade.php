@extends('layouts.client')

@section('title', 'Mã Giảm Giá Của Tôi')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.points.index') }}">Điểm tích lũy</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mã giảm giá</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-1 fw-bold">Mã Giảm Giá Của Tôi</h2>
                            <p class="text-muted mb-0">Quản lý và sử dụng mã giảm giá đã nhận</p>
                        </div>
                        <div class="card-body">
                            @if($userCoupons->count() > 0)
                                <!-- Tab Navigation -->
                                <div class="btn-group w-100 mb-3" role="group">
                                    <button type="button" class="btn btn-outline-success active" id="available-tab" onclick="showTab('available')">
                                        <i class="fa fa-check-circle"></i>
                                        Có thể sử dụng 
                                        <span class="badge bg-success ms-1">{{ $userCoupons->where('used_at', null)->count() }}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="used-tab" onclick="showTab('used')">
                                        <i class="fa fa-times-circle"></i>
                                        Đã sử dụng
                                        <span class="badge bg-secondary ms-1">{{ $userCoupons->where('used_at', '!=', null)->count() }}</span>
                                    </button>
                                </div>

                                <!-- Tab Content -->
                                <div id="available-content" class="tab-content active">
                                    @php
                                        $availableCoupons = $userCoupons->where('used_at', null);
                                    @endphp
                                    
                                    @if($availableCoupons->count() > 0)
                                        <div class="row">
                                            @foreach($availableCoupons as $userCoupon)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card border-success h-100">
                                                        <div class="card-header bg-success text-white">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0">
                                                                    <i class="fa fa-ticket"></i>
                                                                    {{ $userCoupon->coupon->name ?? 'Mã giảm giá' }}
                                                                </h6>
                                                                <span class="badge bg-light text-success">
                                                                    {{ $userCoupon->coupon->code }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            @if($userCoupon->coupon->description)
                                                                <p class="card-text small text-muted mb-2">
                                                                    {{ $userCoupon->coupon->description }}
                                                                </p>
                                                            @endif
                                                            
                                                            <div class="row text-center mb-3">
                                                                <div class="col-6">
                                                                    <div class="border-end">
                                                                        <h5 class="text-success mb-0">
                                                                            @if($userCoupon->coupon->discount_type === 'percentage')
                                                                                {{ number_format($userCoupon->coupon->discount_value, 0) }}%
                                                                            @else
                                                                                {{ number_format($userCoupon->coupon->discount_value) }}đ
                                                                            @endif
                                                                        </h5>
                                                                        <small class="text-muted">Giảm giá</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <h6 class="text-muted mb-0">
                                                                        @if($userCoupon->coupon->min_order_value)
                                                                            {{ number_format($userCoupon->coupon->min_order_value) }}đ
                                                                        @else
                                                                            Không giới hạn
                                                                        @endif
                                                                    </h6>
                                                                    <small class="text-muted">Tối thiểu</small>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="fa fa-calendar"></i>
                                                                    Hết hạn: {{ $userCoupon->coupon->end_date->format('d/m/Y') }}
                                                                </small>
                                                                <button class="btn btn-sm btn-outline-success" 
                                                                        onclick="copyToClipboard('{{ $userCoupon->coupon->code }}')">
                                                                    <i class="fa fa-copy"></i> Copy
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fa fa-ticket fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Không có mã giảm giá nào có thể sử dụng</h5>
                                            <p class="text-muted">Hãy nhận thêm mã giảm giá từ trang điểm tích lũy!</p>
                                        </div>
                                    @endif
                                </div>

                                <div id="used-content" class="tab-content" style="display: none;">
                                    @php
                                        $usedCoupons = $userCoupons->where('used_at', '!=', null);
                                    @endphp
                                    
                                    @if($usedCoupons->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Mã</th>
                                                        <th>Tên</th>
                                                        <th>Giá trị</th>
                                                        <th>Ngày sử dụng</th>
                                                        <th>Đơn hàng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($usedCoupons as $userCoupon)
                                                        <tr>
                                                            <td>
                                                                <code class="bg-light px-2 py-1 rounded">{{ $userCoupon->coupon->code }}</code>
                                                            </td>
                                                            <td>
                                                                <strong>{{ $userCoupon->coupon->name ?? 'Mã giảm giá' }}</strong>
                                                            </td>
                                                            <td>
                                                                @if($userCoupon->coupon->discount_type === 'percentage')
                                                                    <span class="badge bg-info">{{ number_format($userCoupon->coupon->discount_value, 0) }}%</span>
                                                                @else
                                                                    <span class="badge bg-warning">{{ number_format($userCoupon->coupon->discount_value) }}đ</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">
                                                                    {{ $userCoupon->used_at->format('d/m/Y H:i') }}
                                                                </small>
                                                            </td>
                                                            <td>
                                                                @if($userCoupon->order_id)
                                                                    <a href="{{ route('client.order.show', $userCoupon->order_id) }}" class="btn btn-sm btn-outline-primary">
                                                                        Xem đơn hàng
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fa fa-check-circle fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Chưa có mã giảm giá nào được sử dụng</h5>
                                            <p class="text-muted">Hãy sử dụng mã giảm giá để tiết kiệm chi phí!</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa fa-ticket fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Bạn chưa có mã giảm giá nào</h5>
                                    <p class="text-muted">Hãy nhận mã giảm giá từ trang điểm tích lũy nhé!</p>
                                    <a href="{{ route('client.points.index') }}" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i> Quay lại điểm tích lũy
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Hướng dẫn sử dụng</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6><i class="fa fa-info-circle text-info"></i> Cách sử dụng mã giảm giá</h6>
                                <ol class="small">
                                    <li>Copy mã giảm giá từ danh sách</li>
                                    <li>Vào trang thanh toán đơn hàng</li>
                                    <li>Nhập mã vào ô "Mã giảm giá"</li>
                                    <li>Nhấn "Áp dụng" để sử dụng</li>
                                </ol>
                            </div>

                            <div class="mb-3">
                                <h6><i class="fa fa-exclamation-triangle text-warning"></i> Lưu ý</h6>
                                <ul class="small">
                                    <li>Mỗi mã chỉ sử dụng được 1 lần</li>
                                    <li>Mã có thời hạn sử dụng</li>
                                    <li>Áp dụng cho đơn hàng đủ điều kiện</li>
                                </ul>
                            </div>

                            <div class="alert alert-info">
                                <i class="fa fa-lightbulb-o"></i>
                                <strong>Tip:</strong> Bạn có thể nhận thêm mã giảm giá từ trang điểm tích lũy!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.btn-group .btn.active {
    background-color: #198754;
    color: white;
    border-color: #198754;
}
</style>

<script>
function showTab(tabName) {
    // Ẩn tất cả tab content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active');
    });
    
    // Bỏ active tất cả tab buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Hiển thị tab được chọn
    document.getElementById(tabName + '-content').style.display = 'block';
    document.getElementById(tabName + '-content').classList.add('active');
    
    // Active button được chọn
    document.getElementById(tabName + '-tab').classList.add('active');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Hiển thị thông báo thành công
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <i class="fa fa-check"></i>
            Đã copy mã giảm giá: <strong>${text}</strong>
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