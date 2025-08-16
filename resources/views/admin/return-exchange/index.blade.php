@extends('layouts.admin')

@section('title', 'Quản lý đổi hoàn hàng')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <i class="fa fa-exchange"></i> Quản lý đổi hoàn hàng
                    </h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.return-exchange.statistics') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-bar-chart"></i> Thống kê
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                @forelse($returns as $return)
                                    <tr>
                                        <td>{{ $return->id }}</td>
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
                                                <br><small class="text-muted">{{ Str::limit($return->return_description, 50) }}</small>
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
                                @default
                                    <span class="label label-default">N/A</span>
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
                                            <div class="btn-group">
                                                <a href="{{ route('admin.return-exchange.show', $return->id) }}" 
                                                   class="btn btn-xs btn-info" title="Xem chi tiết">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                
                                                @if($return->return_status === 'requested')
                                                    <button type="button" class="btn btn-xs btn-success" 
                                                            onclick="approveReturn({{ $return->id }})" title="Chấp thuận">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-danger" 
                                                            onclick="rejectReturn({{ $return->id }})" title="Từ chối">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                @endif
                                                
                                                @if($return->return_status === 'approved')
                                                    <button type="button" class="btn btn-xs btn-primary" 
                                                            onclick="completeReturn({{ $return->id }})" title="Hoàn thành">
                                                        <i class="fa fa-flag-checkered"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fa fa-info-circle"></i> Chưa có yêu cầu đổi hoàn hàng nào.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($returns->hasPages())
                        <div class="text-center">
                            {{ $returns->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <i class="fa fa-check text-success"></i> Chấp thuận yêu cầu đổi hoàn hàng
                    </h4>
                </div>
                                 <div class="modal-body">
                     <div class="form-group">
                         <label for="return_amount">Số tiền/điểm hoàn (nếu có):</label>
                         <input type="number" class="form-control" id="return_amount" name="return_amount" 
                                step="0.01" min="0" placeholder="Nhập số tiền hoàn...">
                         <small class="text-muted">Hệ thống sẽ tự động hiển thị đúng đơn vị theo phương thức đã chọn</small>
                     </div>
                                          <div class="form-group">
                         <label for="admin_return_note">Ghi chú:</label>
                         <textarea class="form-control" id="admin_return_note" name="admin_return_note" 
                                   rows="3" placeholder="Nhập ghi chú cho khách hàng..."></textarea>
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Chấp thuận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <i class="fa fa-times text-danger"></i> Từ chối yêu cầu đổi hoàn hàng
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reject_note">Lý do từ chối: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_note" name="admin_return_note" 
                                  rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times"></i> Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="completeForm" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <i class="fa fa-flag-checkered text-primary"></i> Hoàn thành xử lý đổi hoàn hàng
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="complete_note">Ghi chú hoàn thành:</label>
                        <textarea class="form-control" id="complete_note" name="admin_return_note" 
                                  rows="3" placeholder="Nhập ghi chú hoàn thành..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-flag-checkered"></i> Hoàn thành
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveReturn(orderId) {
    $('#approveForm').attr('action', '{{ url("admin/return-exchange") }}/' + orderId + '/approve');
    $('#approveModal').modal('show');
}

function rejectReturn(orderId) {
    $('#rejectForm').attr('action', '{{ url("admin/return-exchange") }}/' + orderId + '/reject');
    $('#rejectModal').modal('show');
}

function completeReturn(orderId) {
    $('#completeForm').attr('action', '{{ url("admin/return-exchange") }}/' + orderId + '/complete');
    $('#completeModal').modal('show');
}
</script>
@endsection 