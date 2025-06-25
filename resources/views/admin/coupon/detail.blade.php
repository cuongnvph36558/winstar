@extends('layouts.admin')
@section('title', 'Chi tiết mã giảm giá')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Coupon Detail</h2>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="{{ route('admin.coupon.index') }}">Coupons</a></li>
            <li class="active"><strong>Detail</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="font-bold m-b-xs">{{ $coupon->code }}</h2>
                            <hr>
                            <dl class="dl-horizontal m-t-md">
                                <dt>Loại giảm:</dt>
                                <dd>{{ $coupon->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</dd>

                                <dt>Giá trị giảm:</dt>
                                <dd>{{ $coupon->discount_value }} {{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</dd>

                                <dt>Thời gian áp dụng:</dt>
                                <dd>{{ $coupon->start_date }} → {{ $coupon->end_date }}</dd>

                                <dt>Giá trị đơn hàng tối thiểu:</dt>
                                <dd>{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</dd>

                                <dt>Giảm tối đa:</dt>
                                <dd>{{ number_format($coupon->max_discount_value, 0, ',', '.') }}₫</dd>

                                <dt>Số lần sử dụng toàn hệ thống:</dt>
                                <dd>{{ $coupon->usage_limit }}</dd>

                                <dt>Số lần sử dụng mỗi người:</dt>
                                <dd>{{ $coupon->usage_limit_per_user }}</dd>

                                <dt>Trạng thái:</dt>
                                <dd>{!! $coupon->status ? '<span class="label label-primary">Đang bật</span>' : '<span class="label label-default">Tắt</span>' !!}</dd>
                            </dl>

                            <div class="m-t-lg">
                                <div class="btn-group">
                                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-white btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa mã này?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                          @extends('layouts.admin')
@section('title', 'Chi tiết mã giảm giá')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Coupon Detail</h2>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="{{ route('admin.coupon.index') }}">Coupons</a></li>
            <li class="active"><strong>Detail</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="font-bold m-b-xs">{{ $coupon->code }}</h2>
                            <hr>
                            <dl class="dl-horizontal m-t-md">
                                <dt>Loại giảm:</dt>
                                <dd>{{ $coupon->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</dd>

                                <dt>Giá trị giảm:</dt>
                                <dd>{{ $coupon->discount_value }} {{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</dd>

                                <dt>Thời gian áp dụng:</dt>
                                <dd>{{ $coupon->start_date }} → {{ $coupon->end_date }}</dd>

                                <dt>Giá trị đơn hàng tối thiểu:</dt>
                                <dd>{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</dd>

                                <dt>Giảm tối đa:</dt>
                                <dd>{{ number_format($coupon->max_discount_value, 0, ',', '.') }}₫</dd>

                                <dt>Số lần sử dụng toàn hệ thống:</dt>
                                <dd>{{ $coupon->usage_limit }}</dd>

                                <dt>Số lần sử dụng mỗi người:</dt>
                                <dd>{{ $coupon->usage_limit_per_user }}</dd>

                                <dt>Trạng thái:</dt>
                                <dd>{!! $coupon->status ? '<span class="label label-primary">Đang bật</span>' : '<span class="label label-default">Tắt</span>' !!}</dd>
                            </dl>

                            <div class="m-t-lg">
                                <div class="btn-group">
                                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-white btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa mã này?')">
                                            <i class="fa fa-trash"></i> Delete
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
@extends('layouts.admin')
@section('title', 'Chi tiết mã giảm giá')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Coupon Detail</h2>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="{{ route('admin.coupon.index') }}">Coupons</a></li>
            <li class="active"><strong>Detail</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="font-bold m-b-xs">{{ $coupon->code }}</h2>
                            <hr>
                            <dl class="dl-horizontal m-t-md">
                                <dt>Loại giảm:</dt>
                                <dd>{{ $coupon->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</dd>

                                <dt>Giá trị giảm:</dt>
                                <dd>{{ $coupon->discount_value }} {{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</dd>

                                <dt>Thời gian áp dụng:</dt>
                                <dd>{{ $coupon->start_date }} → {{ $coupon->end_date }}</dd>

                                <dt>Giá trị đơn hàng tối thiểu:</dt>
                                <dd>{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</dd>

                                <dt>Giảm tối đa:</dt>
                                <dd>{{ number_format($coupon->max_discount_value, 0, ',', '.') }}₫</dd>

                                <dt>Số lần sử dụng toàn hệ thống:</dt>
                                <dd>{{ $coupon->usage_limit }}</dd>

                                <dt>Số lần sử dụng mỗi người:</dt>
                                <dd>{{ $coupon->usage_limit_per_user }}</dd>

                                <dt>Trạng thái:</dt>
                                <dd>{!! $coupon->status ? '<span class="label label-primary">Đang bật</span>' : '<span class="label label-default">Tắt</span>' !!}</dd>
                            </dl>

                            <div class="m-t-lg">
                                <div class="btn-group">
                                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-white btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa mã này?')">
                                            <i class="fa fa-trash"></i> Delete
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
