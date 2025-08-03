@extends('layouts.admin')

@section('title', 'Lịch Sử Giao Dịch Điểm')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-history text-info"></i> Lịch Sử Giao Dịch Điểm</h5>
                    <div class="ibox-tools">
                        <form action="{{ route('admin.points.process-expired') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xử lý điểm hết hạn?')">
                                <i class="fa fa-clock-o"></i> Xử Lý Điểm Hết Hạn
                            </button>
                        </form>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Thời gian</th>
                                        <th>User</th>
                                        <th>Loại</th>
                                        <th>Điểm</th>
                                        <th>Mô tả</th>
                                        <th>Tham chiếu</th>
                                        <th>Hết hạn</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->id }}</td>
                                            <td>
                                                <small>{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.points.user-detail', $transaction->user) }}">
                                                    <strong>{{ $transaction->user->name }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                @switch($transaction->type)
                                                    @case('earn')
                                                        <span class="label label-success">Tích điểm</span>
                                                        @break
                                                    @case('use')
                                                        <span class="label label-warning">Sử dụng</span>
                                                        @break
                                                    @case('expire')
                                                        <span class="label label-danger">Hết hạn</span>
                                                        @break
                                                    @case('bonus')
                                                        <span class="label label-info">Thưởng</span>
                                                        @break
                                                    @default
                                                        <span class="label label-default">{{ $transaction->type }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($transaction->type === 'earn' || $transaction->type === 'bonus')
                                                    <span class="text-success">+{{ number_format($transaction->points) }}</span>
                                                @else
                                                    <span class="text-danger">-{{ number_format($transaction->points) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($transaction->description, 50) }}</small>
                                            </td>
                                            <td>
                                                @if($transaction->reference_type === 'order')
                                                    <a href="{{ route('admin.order.show', $transaction->reference_id) }}"
                                                       class="btn btn-xs btn-default">
                                                        <i class="fa fa-shopping-cart"></i> Đơn hàng #{{ $transaction->reference_id }}
                                                    </a>
                                                @elseif($transaction->reference_type === 'voucher')
                                                    <span class="label label-info">Voucher</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->expiry_date)
                                                    <small class="{{ $transaction->is_expired ? 'text-danger' : 'text-muted' }}">
                                                        {{ $transaction->expiry_date->format('d/m/Y') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->is_expired)
                                                    <span class="label label-danger">Hết hạn</span>
                                                @else
                                                    <span class="label label-success">Còn hiệu lực</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-history fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có giao dịch nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
