@extends('layouts.client')

@section('title', 'Đổi điểm lấy voucher')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Đổi điểm lấy voucher</h2>
        </div>
    </div>

    <!-- Thông tin điểm -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5 class="alert-heading">Điểm hiện tại: {{ number_format($pointStats['total_points']) }}</h5>
                <p class="mb-0">Level VIP: {{ $pointStats['vip_level'] }} - Tỷ lệ tích điểm: {{ ($pointStats['point_rate'] * 100) }}%</p>
            </div>
        </div>
    </div>

    <!-- Voucher có thể đổi -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Voucher có thể đổi</h4>
            @if(count($availableVouchers) > 0)
                <div class="row">
                    @foreach($availableVouchers as $voucher)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $voucher->name }}</h5>
                                    <p class="card-text">{{ $voucher->description }}</p>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Điểm cần:</small>
                                            <div class="fw-bold text-primary">{{ number_format($voucher->points_required) }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Giảm giá:</small>
                                            <div class="fw-bold text-success">
                                                @if($voucher->discount_type === 'percentage')
                                                    {{ $voucher->discount_value }}%
                                                @else
                                                    {{ number_format($voucher->discount_value) }}đ
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($voucher->min_order_value > 0)
                                        <small class="text-muted">Áp dụng cho đơn hàng từ {{ number_format($voucher->min_order_value) }}đ</small>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <form action="{{ route('client.points.exchange-voucher') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn đổi {{ number_format($voucher->points_required) }} điểm lấy voucher này?')">
                                            Đổi voucher
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <p class="mb-0">Bạn chưa đủ điểm để đổi voucher nào. Hãy mua sắm thêm để tích điểm nhé!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Voucher đã đổi -->
    <div class="row">
        <div class="col-md-12">
            <h4>Voucher của tôi</h4>
            @if(count($userVouchers) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã voucher</th>
                                <th>Tên voucher</th>
                                <th>Giảm giá</th>
                                <th>Trạng thái</th>
                                <th>Hết hạn</th>
                                <th>Ngày đổi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userVouchers as $userVoucher)
                                <tr>
                                    <td>
                                        <code>{{ $userVoucher->voucher_code }}</code>
                                        @if($userVoucher->status === 'active')
                                            <button class="btn btn-sm btn-outline-secondary ms-2"
                                                    onclick="copyToClipboard('{{ $userVoucher->voucher_code }}')">
                                                Copy
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ $userVoucher->pointVoucher->name }}</td>
                                    <td>
                                        @if($userVoucher->pointVoucher->discount_type === 'percentage')
                                            {{ $userVoucher->pointVoucher->discount_value }}%
                                        @else
                                            {{ number_format($userVoucher->pointVoucher->discount_value) }}đ
                                        @endif
                                    </td>
                                    <td>
                                        @switch($userVoucher->status)
                                            @case('active')
                                                <span class="badge bg-success">Có thể sử dụng</span>
                                                @break
                                            @case('used')
                                                <span class="badge bg-secondary">Đã sử dụng</span>
                                                @break
                                            @case('expired')
                                                <span class="badge bg-danger">Đã hết hạn</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $userVoucher->expiry_date->format('d/m/Y') }}</td>
                                    <td>{{ $userVoucher->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <p class="mb-0">Bạn chưa có voucher nào. Hãy đổi điểm lấy voucher ở trên nhé!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Hướng dẫn sử dụng -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hướng dẫn sử dụng voucher</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Đổi điểm lấy voucher từ danh sách trên</li>
                        <li>Copy mã voucher khi thanh toán</li>
                        <li>Nhập mã voucher vào ô "Mã giảm giá" khi checkout</li>
                        <li>Voucher sẽ được áp dụng tự động nếu đơn hàng đủ điều kiện</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã copy mã voucher: ' + text);
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection
