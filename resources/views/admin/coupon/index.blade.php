@extends('layouts.admin')

@section('title', 'Danh sách mã giảm giá')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh sách mã giảm giá</h2>
            <ol class="breadcrumb">
                <li><a href="#">Trang chủ</a></li>
                <li class="active"><strong>Mã giảm giá</strong></li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.coupon.trash') }}" class="btn btn-warning">
                    <i class="fa fa-recycle"></i> Thùng rác
                </a>
            </div>
            <div class="text-right" style="margin-top: 10px;">
                <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Thêm mã
                </a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        @if(session('success'))
            <div class="alert alert-success alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="ibox-content m-b-sm border-bottom">
            <form action="{{ route('admin.coupon.index') }}" method="get" class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="code">Mã giảm giá</label>
                        <input type="text" id="code" name="code" value="{{ request('code') }}" placeholder="Nhập mã" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="vip_level">VIP Level</label>
                        <select name="vip_level" id="vip_level" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="Bronze" {{ request('vip_level') === 'Bronze' ? 'selected' : '' }}>Bronze</option>
                            <option value="Silver" {{ request('vip_level') === 'Silver' ? 'selected' : '' }}>Silver</option>
                            <option value="Gold" {{ request('vip_level') === 'Gold' ? 'selected' : '' }}>Gold</option>
                            <option value="Platinum" {{ request('vip_level') === 'Platinum' ? 'selected' : '' }}>Platinum</option>
                            <option value="Diamond" {{ request('vip_level') === 'Diamond' ? 'selected' : '' }}>Diamond</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Kích hoạt</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm dỡng</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 text-right">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                            <thead>
                                <tr>
                                    <th data-toggle="true">Mã & Mô tả</th>
                                    <th>VIP & Loại</th>
                                    <th>Giá trị & Điều kiện</th>
                                    <th>Thời gian</th>
                                    <th>Sử dụng</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong class="text-primary">{{ $coupon->code }}</strong>
                                                @if($coupon->name)
                                                    <br><small>{{ $coupon->name }}</small>
                                                @endif
                                                @if($coupon->description)
                                                    <br><small class="text-muted">{{ Str::limit($coupon->description, 40) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if($coupon->vip_level)
                                                    <span class="label label-{{ $coupon->vip_level == 'Diamond' ? 'danger' : ($coupon->vip_level == 'Platinum' ? 'warning' : ($coupon->vip_level == 'Gold' ? 'success' : ($coupon->vip_level == 'Silver' ? 'info' : 'default'))) }}">
                                                        {{ $coupon->vip_level }}
                                                    </span>
                                                @endif
                                                <br>
                                                <small>{{ $coupon->discount_type == 'percentage' ? 'Phần trăm' : 'Số tiền' }}</small>
                                                @if($coupon->exchange_points)
                                                    <br><small class="text-info">Đổi: {{ number_format($coupon->exchange_points) }} điểm</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ number_format($coupon->discount_value) }}{{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</strong>
                                                @if($coupon->min_order_value)
                                                    <br><small>Tối thiểu: {{ number_format($coupon->min_order_value) }}₫</small>
                                                @endif
                                                @if($coupon->max_discount_value || $coupon->max_discount)
                                                    <br><small>Tối đa: {{ number_format($coupon->max_discount_value ?? $coupon->max_discount) }}₫</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-success">{{ $coupon->start_date->format('d/m/Y') }}</small>
                                                <br>
                                                <small class="text-danger">{{ $coupon->end_date->format('d/m/Y') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $usageCount = $coupon->coupon_users_count;
                                                $usageLimit = $coupon->usage_limit;
                                                $isExhausted = $usageLimit && $usageCount >= $usageLimit;
                                            @endphp
                                            <div>
                                                <span class="badge {{ $isExhausted ? 'badge-danger' : 'badge-info' }}">{{ $usageCount }}</span>
                                                @if($usageLimit)
                                                    <small class="text-muted">/ {{ $usageLimit }}</small>
                                                @endif
                                                @if($coupon->usage_limit)
                                                    @php
                                                        $percentage = round(($usageCount / $coupon->usage_limit) * 100);
                                                        $progressClass = $percentage >= 80 ? 'progress-bar-danger' : ($percentage >= 60 ? 'progress-bar-warning' : 'progress-bar-success');
                                                    @endphp
                                                    <div class="progress" style="height: 15px; margin-top: 5px;">
                                                        <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                                             style="width: {{ $percentage }}%;" 
                                                             aria-valuenow="{{ $percentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="label {{ $coupon->status ? 'label-primary' : 'label-default' }}">
                                                {{ $coupon->status ? 'Kích hoạt' : 'Tạm dạng' }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.coupon.show', $coupon->id) }}" class="btn btn-info btn-xs" title="Xem chi tiết"><i class="fa fa-eye"></i></a>
                                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-xs" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Xác nhận xóa mềm?')" class="btn btn-danger btn-xs" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="text-center">
                            {{ $coupons->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
