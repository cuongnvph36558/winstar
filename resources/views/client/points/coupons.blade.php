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
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Mã</th>
                                                <th>Tên</th>
                                                <th>Giá trị</th>
                                                <th>Trạng thái</th>
                                                <th>Hết hạn</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userCoupons as $userCoupon)
                                                <tr>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded">{{ $userCoupon->coupon->code }}</code>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $userCoupon->coupon->name }}</strong>
                                                        @if($userCoupon->coupon->description)
                                                            <br><small class="text-muted">{{ Str::limit($userCoupon->coupon->description, 50) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($userCoupon->coupon->discount_type === 'percentage')
                                                            <span class="badge bg-info">{{ number_format($userCoupon->coupon->discount_value, 0) }}%</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ number_format($userCoupon->coupon->discount_value) }}đ</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($userCoupon->used_at)
                                                            <span class="badge bg-secondary">Đã sử dụng</span>
                                                        @else
                                                            <span class="badge bg-success">Có thể sử dụng</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($userCoupon->coupon->end_date)
                                                            {{ $userCoupon->coupon->end_date->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">Không giới hạn</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$userCoupon->used_at)
                                                            <button class="btn btn-sm btn-outline-primary" 
                                                                    onclick="copyToClipboard('{{ $userCoupon->coupon->code }}')">
                                                                <i class="fa fa-copy"></i> Copy
                                                            </button>
                                                        @else
                                                            <span class="text-muted">Đã sử dụng</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($userCoupons->hasPages())
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $userCoupons->links() }}
                                    </div>
                                @endif
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

<script>
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