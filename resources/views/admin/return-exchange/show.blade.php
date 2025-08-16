@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu đổi hoàn hàng #' . $order->id)

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <i class="fa fa-exchange"></i> Chi tiết yêu cầu đổi hoàn hàng
                    </h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.return-exchange.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay lại
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

                    <div class="row">
                        <!-- Order Information -->
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-shopping-cart"></i> Thông tin đơn hàng</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Mã đơn hàng:</strong></td>
                                            <td>{{ $order->code_order ?? '#' . $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tổng tiền:</strong></td>
                                            <td>{{ number_format($order->total_amount) }}đ</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trạng thái đơn hàng:</strong></td>
                                            <td>
                                                @switch($order->status)
                                                    @case('completed')
                                                        <span class="label label-success">Hoàn thành</span>
                                                        @break
                                                    @default
                                                        <span class="label label-info">{{ $order->status }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày đặt hàng:</strong></td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Đã nhận hàng:</strong></td>
                                            <td>
                                                @if($order->is_received)
                                                    <span class="label label-success">Có</span>
                                                @else
                                                    <span class="label label-warning">Chưa</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-user"></i> Thông tin khách hàng</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Họ tên:</strong></td>
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $order->user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số điện thoại:</strong></td>
                                            <td>{{ $order->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Địa chỉ:</strong></td>
                                            <td>{{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Request Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-exchange"></i> Thông tin yêu cầu đổi hoàn hàng</h4>
                                </div>
                                <div class="panel-body">
                                    <!-- Media Evidence Section -->
                                    @if($order->return_video || $order->return_order_image || $order->return_product_image)
                                        <div class="media-evidence-section mb-30">
                                            <h5><i class="fa fa-camera text-primary"></i> Bằng chứng đổi hoàn hàng</h5>
                                            <div class="row">
                                                @if($order->return_video)
                                                    <div class="col-md-4">
                                                        <div class="media-item">
                                                            <h6><i class="fa fa-video-camera"></i> Video bóc hàng</h6>
                                                            <video controls style="width: 100%; max-height: 200px; border-radius: 8px;">
                                                                <source src="{{ asset('storage/' . $order->return_video) }}" type="video/mp4">
                                                                Trình duyệt không hỗ trợ video.
                                                            </video>
                                                            <div class="mt-10">
                                                                <a href="{{ asset('storage/' . $order->return_video) }}" target="_blank" class="btn btn-xs btn-info">
                                                                    <i class="fa fa-download"></i> Tải video
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($order->return_order_image)
                                                    <div class="col-md-4">
                                                        <div class="media-item">
                                                            <h6><i class="fa fa-image"></i> Ảnh đơn hàng</h6>
                                                            <img src="{{ asset('storage/' . $order->return_order_image) }}" 
                                                                 alt="Ảnh đơn hàng" 
                                                                 style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;">
                                                            <div class="mt-10">
                                                                <a href="{{ asset('storage/' . $order->return_order_image) }}" target="_blank" class="btn btn-xs btn-info">
                                                                    <i class="fa fa-download"></i> Tải ảnh
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($order->return_product_image)
                                                    <div class="col-md-4">
                                                        <div class="media-item">
                                                            <h6><i class="fa fa-cube"></i> Ảnh sản phẩm</h6>
                                                            <img src="{{ asset('storage/' . $order->return_product_image) }}" 
                                                                 alt="Ảnh sản phẩm" 
                                                                 style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;">
                                                            <div class="mt-10">
                                                                <a href="{{ asset('storage/' . $order->return_product_image) }}" target="_blank" class="btn btn-xs btn-info">
                                                                    <i class="fa fa-download"></i> Tải ảnh
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Trạng thái:</strong></td>
                                                    <td>
                                                        @switch($order->return_status)
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
                                                                <span class="label label-default">{{ $order->return_status }}</span>
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phương thức:</strong></td>
                                                                                                         <td>
                                                         @switch($order->return_method)
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
                                                </tr>
                                                <tr>
                                                    <td><strong>Lý do:</strong></td>
                                                    <td>{{ $order->return_reason }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Ngày yêu cầu:</strong></td>
                                                    <td>{{ $order->return_requested_at ? $order->return_requested_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                                </tr>
                                                @if($order->return_processed_at)
                                                <tr>
                                                    <td><strong>Ngày xử lý:</strong></td>
                                                    <td>{{ $order->return_processed_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                @endif
                                                                                                 @if($order->return_amount)
                                                 <tr>
                                                     <td><strong>
                                                         @if($order->return_method === 'points')
                                                             Số điểm hoàn:
                                                         @else
                                                             Số tiền hoàn:
                                                         @endif
                                                     </strong></td>
                                                     <td><strong class="text-success">
                                                         @if($order->return_method === 'points')
                                                             {{ number_format($order->return_amount) }} điểm
                                                         @else
                                                             {{ number_format($order->return_amount) }}đ
                                                         @endif
                                                     </strong></td>
                                                 </tr>
                                                 @endif
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Mô tả chi tiết:</strong></label>
                                                <div class="well">
                                                    {{ $order->return_description ?: 'Không có mô tả chi tiết' }}
                                                </div>
                                            </div>
                                            @if($order->admin_return_note)
                                            <div class="form-group">
                                                <label><strong>Ghi chú admin:</strong></label>
                                                <div class="well">
                                                    {{ $order->admin_return_note }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-list"></i> Chi tiết sản phẩm</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Biến thể</th>
                                                    <th>Giá</th>
                                                    <th>Số lượng</th>
                                                    <th>Tổng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderDetails as $detail)
                                                    <tr>
                                                        <td>
                                                            <div class="media">
                                                                <div class="media-left">
                                                                    @if($detail->product->image)
                                                                        <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                                             alt="{{ $detail->product->name }}" 
                                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                                    @else
                                                                        <div style="width: 50px; height: 50px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                                            <i class="fa fa-image text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="media-body">
                                                                    <h5 class="media-heading">{{ $detail->product->name }}</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($detail->variant)
                                                                <span class="label label-info">
                                                                    {{ $detail->variant->color->name ?? '' }}
                                                                    @if($detail->variant->storage)
                                                                        - {{ $detail->variant->storage->name ?? '' }}
                                                                    @endif
                                                                </span>
                                                            @else
                                                                <span class="text-muted">Không có</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($detail->price) }}đ</td>
                                                        <td>{{ $detail->quantity }}</td>
                                                        <td><strong>{{ number_format($detail->total) }}đ</strong></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($order->return_status === 'requested')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4><i class="fa fa-cogs"></i> Thao tác</h4>
                                    </div>
                                    <div class="panel-body text-center">
                                        <button type="button" class="btn btn-success btn-lg" onclick="approveReturn({{ $order->id }})">
                                            <i class="fa fa-check"></i> Chấp thuận
                                        </button>
                                        <button type="button" class="btn btn-danger btn-lg" onclick="rejectReturn({{ $order->id }})">
                                            <i class="fa fa-times"></i> Từ chối
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($order->return_status === 'approved')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4><i class="fa fa-cogs"></i> Thao tác</h4>
                                    </div>
                                    <div class="panel-body text-center">
                                        <button type="button" class="btn btn-primary btn-lg" onclick="completeReturn({{ $order->id }})">
                                            <i class="fa fa-flag-checkered"></i> Hoàn thành
                                        </button>
                                    </div>
                                </div>
                            </div>
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
                         <label for="return_amount">
                             @if($order->return_method === 'points')
                                 Số điểm hoàn (nếu có):
                             @else
                                 Số tiền hoàn (nếu có):
                             @endif
                         </label>
                         <input type="number" class="form-control" id="return_amount" name="return_amount" 
                                step="0.01" min="0" 
                                @if($order->return_method === 'points')
                                    max="{{ $order->total_amount * 10 }}" 
                                    placeholder="Nhập số điểm hoàn..."
                                @else
                                    max="{{ $order->total_amount }}" 
                                    placeholder="Nhập số tiền hoàn..."
                                @endif>
                         <small class="text-muted">
                             @if($order->return_method === 'points')
                                 Tối đa: {{ number_format($order->total_amount * 10) }} điểm (1đ = 10 điểm)
                             @else
                                 Tối đa: {{ number_format($order->total_amount) }}đ
                             @endif
                         </small>
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