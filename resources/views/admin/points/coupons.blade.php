@extends('layouts.admin')

@section('title', 'Quản Lý Mã Giảm Giá')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-ticket text-success"></i> Quản Lý Mã Giảm Giá</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.points.create-coupon') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Tạo Mã Giảm Giá Mới
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã</th>
                                    <th>Tên</th>
                                    <th>Loại giảm giá</th>
                                    <th>Giá trị</th>
                                    <th>Giá trị tối thiểu</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->id }}</td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $coupon->code }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $coupon->name }}</strong>
                                            @if($coupon->description)
                                                <br><small class="text-muted">{{ Str::limit($coupon->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="label label-info">Phần trăm</span>
                                            @else
                                                <span class="label label-warning">Số tiền</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                {{ number_format($coupon->discount_value, 0) }}%
                                            @else
                                                {{ number_format($coupon->discount_value) }}đ
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->min_order_value > 0)
                                                {{ number_format($coupon->min_order_value) }}đ
                                            @else
                                                <span class="text-muted">Không giới hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="label label-success">Hoạt động</span>
                                            @else
                                                <span class="label label-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">Từ: {{ $coupon->start_date->format('d/m/Y') }}</small><br>
                                                <small class="text-muted">Đến: {{ $coupon->end_date->format('d/m/Y') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.points.edit-coupon', $coupon) }}" 
                                                   class="btn btn-xs btn-primary" title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.points.destroy-coupon', $coupon) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="text-muted">
                                                <i class="fa fa-ticket fa-3x"></i>
                                                <p>Chưa có mã giảm giá nào</p>
                                                <a href="{{ route('admin.points.create-coupon') }}" class="btn btn-primary">
                                                    Tạo mã giảm giá đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($coupons->hasPages())
                        <div class="text-center">
                            {{ $coupons->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 