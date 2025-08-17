@extends('layouts.admin')

@section('title', 'Chi tiết giao dịch VNPay')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Chi tiết giao dịch VNPay #{{ $transaction->id }}</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.vnpay-transactions.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <!-- Transaction Details -->
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>Thông tin giao dịch</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td>{{ $transaction->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mã giao dịch VNPay:</strong></td>
                                            <td><code>{{ $transaction->vnp_TxnRef }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số tiền:</strong></td>
                                            <td><strong class="text-success">{{ number_format($transaction->vnp_Amount / 100, 0, ',', '.') }} VNĐ</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trạng thái:</strong></td>
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
                                        </tr>
                                        <tr>
                                            <td><strong>Mã phản hồi:</strong></td>
                                            <td>
                                                @if($transaction->vnp_ResponseCode)
                                                    <code>{{ $transaction->vnp_ResponseCode }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mã giao dịch ngân hàng:</strong></td>
                                            <td>
                                                @if($transaction->vnp_TransactionNo)
                                                    <code>{{ $transaction->vnp_TransactionNo }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày thanh toán:</strong></td>
                                            <td>
                                                @if($transaction->vnp_PayDate)
                                                    {{ \Carbon\Carbon::createFromFormat('YmdHis', $transaction->vnp_PayDate)->format('d/m/Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngân hàng:</strong></td>
                                            <td>
                                                @if($transaction->vnp_BankCode)
                                                    <span class="badge badge-info">{{ $transaction->vnp_BankCode }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loại thẻ:</strong></td>
                                            <td>
                                                @if($transaction->vnp_CardType)
                                                    {{ $transaction->vnp_CardType }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Thông báo:</strong></td>
                                            <td>{{ $transaction->message ?: 'Không có' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày tạo:</strong></td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cập nhật lần cuối:</strong></td>
                                            <td>{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>Thông tin đơn hàng</h4>
                                </div>
                                <div class="panel-body">
                                    @if($transaction->order)
                                        <table class="table table-striped">
                                            <tr>
                                                <td><strong>Mã đơn hàng:</strong></td>
                                                <td>
                                                    <a href="{{ route('admin.order.show', $transaction->order->id) }}" class="text-primary">
                                                        {{ $transaction->order->code_order }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Khách hàng:</strong></td>
                                                <td>{{ $transaction->order->user->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tổng tiền:</strong></td>
                                                <td><strong>{{ number_format($transaction->order->total_amount, 0, ',', '.') }} VNĐ</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trạng thái đơn hàng:</strong></td>
                                                <td>
                                                    @switch($transaction->order->status)
                                                        @case('pending')
                                                            <span class="badge badge-warning">Chờ xử lý</span>
                                                            @break
                                                        @case('processing')
                                                            <span class="badge badge-info">Đang xử lý</span>
                                                            @break
                                                        @case('shipping')
                                                            <span class="badge badge-primary">Đang giao</span>
                                                            @break
                                                        @case('delivered')
                                                            <span class="badge badge-success">Đã giao</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="badge badge-success">Hoàn thành</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge badge-danger">Đã hủy</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary">{{ $transaction->order->status }}</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trạng thái thanh toán:</strong></td>
                                                <td>
                                                    @switch($transaction->order->payment_status)
                                                        @case('pending')
                                                            <span class="badge badge-warning">Chờ thanh toán</span>
                                                            @break
                                                        @case('paid')
                                                            <span class="badge badge-success">Đã thanh toán</span>
                                                            @break
                                                        @case('failed')
                                                            <span class="badge badge-danger">Thanh toán thất bại</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary">{{ $transaction->order->payment_status }}</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phương thức thanh toán:</strong></td>
                                                <td>
                                                    @switch($transaction->order->payment_method)
                                                        @case('vnpay')
                                                            <span class="badge badge-primary">VNPay</span>
                                                            @break

                                                        @case('cod')
                                                            <span class="badge badge-info">Thanh toán khi nhận hàng</span>
                                                            @break
                                                        @default
                                                            {{ $transaction->order->payment_method }}
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Ngày đặt hàng:</strong></td>
                                                <td>{{ $transaction->order->created_at->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            Không tìm thấy thông tin đơn hàng liên quan.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Raw Data -->
                    @if($transaction->raw_data)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>Dữ liệu thô từ VNPay</h4>
                                </div>
                                <div class="panel-body">
                                    <pre class="bg-light p-3" style="max-height: 300px; overflow-y: auto;">{{ json_encode($transaction->raw_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>Thao tác</h4>
                                </div>
                                <div class="panel-body">
                                    <a href="{{ route('admin.vnpay-transactions.index') }}" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Quay lại danh sách
                                    </a>
                                    @if($transaction->order)
                                        <a href="{{ route('admin.order.show', $transaction->order->id) }}" class="btn btn-info">
                                            <i class="fa fa-eye"></i> Xem đơn hàng
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.vnpay-transactions.destroy', $transaction->id) }}" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa giao dịch này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Xóa giao dịch
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
