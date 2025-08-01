@extends('layouts.client')

@section('title', 'Điểm Tích Lũy')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fa fa-star text-warning"></i>
                Điểm Tích Lũy
            </h2>
        </div>
    </div>

    <!-- Thống kê điểm -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ number_format($pointStats['total_points']) }}</h3>
                    <p class="card-text">Tổng điểm hiện có</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ number_format($pointStats['earned_points']) }}</h3>
                    <p class="card-text">Điểm đã tích</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ number_format($pointStats['used_points']) }}</h3>
                    <p class="card-text">Điểm đã dùng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $pointStats['vip_level'] }}</h3>
                    <p class="card-text">Level VIP</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Voucher có thể đổi -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-gift text-success"></i>
                        Đổi Điểm Lấy Voucher
                    </h5>
                </div>
                <div class="card-body">
                    @if($availableVouchers->count() > 0)
                        <div class="row">
                            @foreach($availableVouchers as $voucher)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-{{ $pointStats['total_points'] >= $voucher->points_required ? 'success' : 'secondary' }}">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $voucher->name }}</h6>
                                            <p class="card-text small">{{ $voucher->description }}</p>

                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <strong class="text-primary">{{ number_format($voucher->points_required) }}</strong>
                                                    <br><small>Điểm cần</small>
                                                </div>
                                                <div class="col-6">
                                                    <strong class="text-success">
                                                        {{ $voucher->discount_type === 'percentage' ? $voucher->discount_value . '%' : number_format($voucher->discount_value) . ' VND' }}
                                                    </strong>
                                                    <br><small>Giảm giá</small>
                                                </div>
                                            </div>

                                            @if($voucher->min_order_value > 0)
                                                <div class="text-center mt-2">
                                                    <small class="text-muted">
                                                        Đơn hàng tối thiểu: {{ number_format($voucher->min_order_value) }} VND
                                                    </small>
                                                </div>
                                            @endif

                                            <div class="text-center mt-3">
                                                @if($pointStats['total_points'] >= $voucher->points_required)
                                                    <form action="{{ route('client.points.exchange') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fa fa-exchange"></i> Đổi Voucher
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fa fa-lock"></i> Thiếu {{ number_format($voucher->points_required - $pointStats['total_points']) }} điểm
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-gift fa-3x text-muted"></i>
                            <p class="text-muted mt-2">Hiện tại không có voucher nào để đổi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lịch sử giao dịch -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-history text-info"></i>
                        Lịch Sử Giao Dịch
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($pointHistory) > 0)
                        <div class="timeline">
                            @foreach($pointHistory as $transaction)
                                <div class="timeline-item">
                                    <div class="timeline-marker {{ $transaction['type'] === 'earn' ? 'bg-success' : 'bg-danger' }}"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">
                                            {{ $transaction['points'] > 0 ? '+' : '' }}{{ number_format($transaction['points']) }} điểm
                                        </h6>
                                        <p class="timeline-text small">{{ $transaction['description'] }}</p>
                                        <small class="text-muted">{{ $transaction['created_at'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('client.points.history') }}" class="btn btn-outline-primary btn-sm">
                                Xem tất cả
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fa fa-history fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Chưa có giao dịch nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin thêm -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-info-circle text-info"></i>
                        Thông Tin Hệ Thống Điểm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>🏆 Level VIP</h6>
                            <ul class="list-unstyled">
                                <li><strong>Bronze:</strong> Tỷ lệ tích điểm 5%</li>
                                <li><strong>Silver:</strong> Tỷ lệ tích điểm 7%</li>
                                <li><strong>Gold:</strong> Tỷ lệ tích điểm 10%</li>
                                <li><strong>Platinum:</strong> Tỷ lệ tích điểm 15%</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>⏰ Quy Định</h6>
                            <ul class="list-unstyled">
                                <li>• Điểm có hiệu lực: 12 tháng</li>
                                <li>• Tích điểm khi đơn hàng hoàn thành</li>
                                <li>• Có thể đổi điểm lấy voucher</li>
                                <li>• Voucher có thể sử dụng khi thanh toán</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin: 0;
    font-size: 14px;
    font-weight: bold;
}

.timeline-text {
    margin: 5px 0;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}
</style>
@endsection
