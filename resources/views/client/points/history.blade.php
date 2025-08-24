@extends('layouts.client')

@section('title', 'Lịch Sử Giao Dịch Điểm')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fa fa-history text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold">Lịch Sử Giao Dịch Điểm</h2>
                        <p class="text-muted mb-0">Xem lại tất cả giao dịch điểm của bạn</p>
                    </div>
                </div>
                <a href="{{ route('client.points.index') }}" class="btn btn-outline-primary">
                    <i class="fa fa-arrow-left me-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, {{ $pointStats['total_points'] < 0 ? '#dc3545' : '#667eea' }} 0%, {{ $pointStats['total_points'] < 0 ? '#c82333' : '#764ba2' }} 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-2">
                        @if($pointStats['total_points'] < 0)
                            <i class="fa fa-exclamation-triangle fa-2x opacity-75"></i>
                        @else
                            <i class="fa fa-coins fa-2x opacity-75"></i>
                        @endif
                    </div>
                    <h3 class="fw-bold mb-1">
                        @if($pointStats['total_points'] < 0)
                            -{{ number_format(abs($pointStats['total_points'])) }}
                        @else
                            {{ number_format($pointStats['total_points']) }}
                        @endif
                    </h3>
                    <p class="mb-0 opacity-90">
                        @if($pointStats['total_points'] < 0)
                            Điểm nợ
                        @else
                            Tổng điểm hiện có
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-2">
                        <i class="fa fa-arrow-up fa-2x opacity-75"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($pointStats['earned_points']) }}</h3>
                    <p class="mb-0 opacity-90">Điểm đã tích</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-2">
                        <i class="fa fa-arrow-down fa-2x opacity-75"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($pointStats['used_points']) }}</h3>
                    <p class="mb-0 opacity-90">Điểm đã dùng</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-2">
                        <i class="fa fa-crown fa-2x opacity-75"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $pointStats['vip_level'] }}</h3>
                    <p class="mb-0 opacity-90">Level VIP</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-list text-primary me-2" style="font-size: 1.2rem;"></i>
                            <h5 class="mb-0 fw-bold">Chi Tiết Giao Dịch</h5>
                        </div>
                        <div class="text-muted">
                            Tổng cộng: <strong>{{ count($pointHistory) }}</strong> giao dịch
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($pointHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Ngày</th>
                                        <th class="border-0">Loại</th>
                                        <th class="border-0">Điểm</th>
                                        <th class="border-0">Mô tả</th>
                                        <th class="border-0">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pointHistory as $transaction)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction['type'] === 'earn' ? 'success' : ($transaction['type'] === 'use' ? 'danger' : ($transaction['type'] === 'bonus' ? 'warning' : 'secondary')) }}">
                                                    @switch($transaction['type'])
                                                        @case('earn')
                                                            <i class="fa fa-plus me-1"></i> Tích điểm
                                                            @break
                                                        @case('use')
                                                            <i class="fa fa-minus me-1"></i> Sử dụng
                                                            @break
                                                        @case('bonus')
                                                            <i class="fa fa-star me-1"></i> Thưởng
                                                            @break
                                                        @default
                                                            <i class="fa fa-circle me-1"></i> {{ $transaction['type'] }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold {{ $transaction['points'] > 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction['points'] > 0 ? '+' : '' }}{{ number_format($transaction['points']) }}
                                                </span>
                                            </td>
                                            <td>{{ $transaction['description'] }}</td>
                                            <td>
                                                @if($transaction['type'] === 'earn')
                                                    @if($transaction['is_expired'])
                                                        <span class="badge bg-danger">
                                                            <i class="fa fa-clock me-1"></i> Hết hạn
                                                        </span>
                                                    @elseif($transaction['expiry_date'])
                                                        <span class="badge bg-info">
                                                            <i class="fa fa-calendar me-1"></i> Hết hạn: {{ $transaction['expiry_date'] }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="fa fa-check me-1"></i> Còn hiệu lực
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-history fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có giao dịch điểm nào</h5>
                            <p class="text-muted">Bạn chưa có giao dịch điểm nào</p>
                            <a href="{{ route('client.points.index') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left me-2"></i> Quay lại trang điểm
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-info me-2" style="font-size: 1.2rem;"></i>
                        <h5 class="mb-0 fw-bold">Thông Tin Bổ Sung</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">
                                <i class="fa fa-chart-bar text-primary me-2"></i>
                                Loại Giao Dịch
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="badge bg-success me-2">Tích điểm</span>
                                    <span>Điểm nhận được khi mua hàng</span>
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-danger me-2">Sử dụng</span>
                                    <span>Điểm đã dùng để đổi voucher</span>
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-warning me-2">Thưởng</span>
                                    <span>Điểm thưởng từ admin</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">
                                <i class="fa fa-clock text-warning me-2"></i>
                                Thời Hạn Điểm
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Điểm tích từ mua hàng: 12 tháng
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Điểm thưởng: 12 tháng
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Điểm hết hạn sẽ tự động bị trừ
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Có thể kiểm tra trạng thái trong bảng trên
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

.table th {
    font-weight: 600;
    border-top: none;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.4em 0.6em;
    }
}
</style>
@endsection 
