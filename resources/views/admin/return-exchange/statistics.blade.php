@extends('layouts.admin')

@section('title', 'Thống kê đổi hoàn hàng')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <i class="fa fa-bar-chart"></i> Thống kê đổi hoàn hàng
                    </h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.return-exchange.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-primary">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-exchange fa-3x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Tổng yêu cầu </span>
                                        <h2 class="font-bold">{{ $stats['total_requests'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-warning">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-clock-o fa-3x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Chờ xử lý </span>
                                        <h2 class="font-bold">{{ $stats['pending_requests'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-success">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-check fa-3x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Đã chấp thuận </span>
                                        <h2 class="font-bold">{{ $stats['approved_requests'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-danger">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-times fa-3x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Đã từ chối </span>
                                        <h2 class="font-bold">{{ $stats['rejected_requests'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Recent Returns -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-history"></i> Yêu cầu gần đây</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Khách hàng</th>
                                                    <th>Mã đơn hàng</th>
                                                    <th>Lý do</th>
                                                    <th>Phương thức</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ngày yêu cầu</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentReturns as $return)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $return->user->name ?? 'N/A' }}</strong><br>
                                                            <small class="text-muted">{{ $return->user->email ?? 'N/A' }}</small>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $return->code_order ?? '#' . $return->id }}</strong><br>
                                                            <small class="text-muted">{{ number_format($return->total_amount) }}đ</small>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $return->return_reason }}</strong>
                                                            @if($return->return_description)
                                                                <br><small class="text-muted">{{ Str::limit($return->return_description, 30) }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @switch($return->return_method)
                                                                @case('points')
                                                                    <span class="label label-primary">Đổi điểm</span>
                                                                    @break
                                                                @case('exchange')
                                                                    <span class="label label-warning">Đổi hàng</span>
                                                                    @break
                                                                @case('refund')
                                                                    <span class="label label-info">Hoàn tiền</span>
                                                                    @break
                                                                @case('credit')
                                                                    <span class="label label-success">Tín dụng</span>
                                                                    @break
                                                                @case('return')
                                                                    <span class="label label-danger">Trả hàng</span>
                                                                    @break
                                                                @default
                                                                    <span class="label label-default">{{ $return->return_method ?? 'N/A' }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            @switch($return->return_status)
                                                                @case('requested')
                                                                    <span class="label label-warning">Chờ xử lý</span>
                                                                    @break
                                                                @case('approved')
                                                                    <span class="label label-success">Đã chấp thuận</span>
                                                                    @break
                                                                @case('rejected')
                                                                    <span class="label label-danger">Đã từ chối</span>
                                                                    @break
                                                                @case('completed')
                                                                    <span class="label label-primary">Hoàn thành</span>
                                                                    @break
                                                                @default
                                                                    <span class="label label-default">{{ $return->return_status }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            {{ $return->return_requested_at ? $return->return_requested_at->format('d/m/Y H:i') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.return-exchange.show', $return->id) }}" 
                                                               class="btn btn-xs btn-info" title="Xem chi tiết">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            <div class="alert alert-info">
                                                                <i class="fa fa-info-circle"></i> Chưa có yêu cầu đổi hoàn hàng nào.
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Information -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-info-circle"></i> Thông tin tổng quan</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Tỷ lệ xử lý:</h5>
                                            <div class="progress">
                                                @php
                                                    $totalProcessed = $stats['approved_requests'] + $stats['rejected_requests'] + $stats['completed_requests'];
                                                    $processRate = $stats['total_requests'] > 0 ? ($totalProcessed / $stats['total_requests']) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar progress-bar-success" style="width: {{ $processRate }}%">
                                                    {{ number_format($processRate, 1) }}%
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $totalProcessed }} / {{ $stats['total_requests'] }} yêu cầu đã được xử lý
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Tỷ lệ chấp thuận:</h5>
                                            <div class="progress">
                                                @php
                                                    $approvalRate = $totalProcessed > 0 ? ($stats['approved_requests'] / $totalProcessed) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar progress-bar-info" style="width: {{ $approvalRate }}%">
                                                    {{ number_format($approvalRate, 1) }}%
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $stats['approved_requests'] }} / {{ $totalProcessed }} yêu cầu được chấp thuận
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.widget.style1 {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    color: white;
}

.widget.style1 .row {
    display: flex;
    align-items: center;
}

.widget.style1 i {
    opacity: 0.8;
}

.widget.style1 span {
    font-size: 14px;
    opacity: 0.9;
}

.widget.style1 h2 {
    margin: 0;
    font-weight: bold;
}

 .bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
 .bg-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
 .bg-success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
 .bg-danger { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

.progress {
    height: 25px;
    margin-bottom: 10px;
}

.progress-bar {
    line-height: 25px;
    font-weight: bold;
}
</style>
@endsection 
