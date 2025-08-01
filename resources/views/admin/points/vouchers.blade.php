@extends('layouts.admin')

@section('title', 'Quản Lý Voucher Điểm')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-gift text-success"></i> Quản Lý Voucher Điểm</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.points.create-voucher') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Tạo Voucher Mới
                        </a>
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

                    @if($vouchers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Voucher</th>
                                        <th>Mô tả</th>
                                        <th>Điểm cần</th>
                                        <th>Giảm giá</th>
                                        <th>Đơn hàng tối thiểu</th>
                                        <th>Thời gian</th>
                                        <th>Trạng thái</th>
                                        <th>Sử dụng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vouchers as $voucher)
                                        <tr>
                                            <td>{{ $voucher->id }}</td>
                                            <td>
                                                <strong>{{ $voucher->name }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($voucher->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="label label-info">{{ number_format($voucher->points_required) }}</span>
                                            </td>
                                            <td>
                                                @if($voucher->discount_type === 'percentage')
                                                    <span class="text-success">{{ $voucher->discount_value }}%</span>
                                                @else
                                                    <span class="text-success">{{ number_format($voucher->discount_value) }} VND</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($voucher->min_order_value > 0)
                                                    <span class="text-muted">{{ number_format($voucher->min_order_value) }} VND</span>
                                                @else
                                                    <span class="text-muted">Không giới hạn</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>
                                                    <div>Từ: {{ $voucher->start_date->format('d/m/Y') }}</div>
                                                    <div>Đến: {{ $voucher->end_date->format('d/m/Y') }}</div>
                                                </small>
                                            </td>
                                            <td>
                                                @if($voucher->isActive())
                                                    <span class="label label-success">Hoạt động</span>
                                                @else
                                                    <span class="label label-danger">Không hoạt động</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge">{{ $voucher->user_vouchers_count ?? 0 }}</span>
                                                @if($voucher->max_usage)
                                                    <small class="text-muted">/ {{ $voucher->max_usage }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                                                                        <a href="{{ route('admin.points.edit-voucher', $voucher) }}"
                                                        class="btn btn-xs btn-info" title="Sửa">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-xs btn-danger"
                                                            onclick="deleteVoucher({{ $voucher->id }})" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            {{ $vouchers->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-gift fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có voucher nào</p>
                            <a href="{{ route('admin.points.create-voucher') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tạo Voucher Đầu Tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form xóa voucher -->
<form id="delete-voucher-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteVoucher(voucherId) {
    if (confirm('Bạn có chắc chắn muốn xóa voucher này?')) {
        const form = document.getElementById('delete-voucher-form');
        form.action = `/admin/points/vouchers/${voucherId}`;
        form.submit();
    }
}
</script>
@endsection
