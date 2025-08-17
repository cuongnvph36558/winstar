@extends('layouts.admin')

@section('title', 'Quản lý giao dịch VNPay')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Quản lý giao dịch VNPay</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.vnpay-transactions.export') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-download"></i> Xuất CSV
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="widget style1 bg-primary">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <i class="fa fa-credit-card fa-2x"></i>
                                    </div>
                                    <div class="col-8 text-right">
                                        <span>Tổng giao dịch</span>
                                        <h2 class="font-bold">{{ $stats['total'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget style1 bg-success">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <i class="fa fa-check-circle fa-2x"></i>
                                    </div>
                                    <div class="col-8 text-right">
                                        <span>Thành công</span>
                                        <h2 class="font-bold">{{ $stats['success'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget style1 bg-warning">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <i class="fa fa-clock-o fa-2x"></i>
                                    </div>
                                    <div class="col-8 text-right">
                                        <span>Đang xử lý</span>
                                        <h2 class="font-bold">{{ $stats['pending'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget style1 bg-danger">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <i class="fa fa-times-circle fa-2x"></i>
                                    </div>
                                    <div class="col-8 text-right">
                                        <span>Thất bại</span>
                                        <h2 class="font-bold">{{ $stats['failed'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.vnpay-transactions.index') }}" class="form-inline">
                                <div class="form-group mr-3">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                                </div>
                                <div class="form-group mr-3">
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Thành công</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                                    </select>
                                </div>
                                <div class="form-group mr-3">
                                    <input type="date" name="date_from" class="form-control" placeholder="Từ ngày" value="{{ request('date_from') }}">
                                </div>
                                <div class="form-group mr-3">
                                    <input type="date" name="date_to" class="form-control" placeholder="Đến ngày" value="{{ request('date_to') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('admin.vnpay-transactions.index') }}" class="btn btn-secondary ml-2">Làm mới</a>
                            </form>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã đơn hàng</th>
                                    <th>Mã giao dịch VNPay</th>
                                    <th>Số tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Mã phản hồi</th>
                                    <th>Ngân hàng</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>
                                        @if($transaction->order)
                                            <a href="{{ route('admin.order.show', $transaction->order->id) }}" class="text-primary">
                                                {{ $transaction->order->code_order }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $transaction->vnp_TxnRef }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($transaction->vnp_Amount / 100, 0, ',', '.') }} VNĐ</strong>
                                    </td>
                                    <td>
                                        @switch($transaction->status)
                                            @case('success')
                                                <span class="badge badge-success">Thành công</span>
                                                @break
                                            @case('failed')
                                                <span class="badge badge-danger">Thất bại</span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-warning">Đang xử lý</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $transaction->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($transaction->vnp_ResponseCode)
                                            <code>{{ $transaction->vnp_ResponseCode }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->vnp_BankCode)
                                            <span class="badge badge-info">{{ $transaction->vnp_BankCode }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.vnpay-transactions.show', $transaction->id) }}" class="btn btn-xs btn-info">
                                            <i class="fa fa-eye"></i> Chi tiết
                                        </a>
                                        <form method="POST" action="{{ route('admin.vnpay-transactions.destroy', $transaction->id) }}" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa giao dịch này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có giao dịch nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when filters change
    $('select[name="status"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endsection

