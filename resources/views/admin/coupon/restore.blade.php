@extends('layouts.admin')

@section('title', 'Khôi phục mã giảm giá')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Khôi phục Mã Giảm Giá</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.coupon.index') }}">Danh sách Mã Giảm Giá</a>
                </li>
                <li class="active">
                    <strong>Khôi phục</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.coupon.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Quay Lại
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

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                            <thead>
                                <tr>
                                    <th data-toggle="true">Mã Giảm Giá</th>
                                    <th>Loại</th>
                                    <th>Giá Trị</th>
                                    <th>Ngày Kết Thúc</th>
                                    <th class="text-right">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount_type }}</td>
                                    <td>{{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : ' ₫' }}</td>
                                    <td>{{ $coupon->end_date }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <form action="{{ route('admin.coupon.restore', $coupon->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-xs" onclick="return confirm('Bạn có chắc muốn khôi phục mã này?')">
                                                    <i class="fa fa-recycle"></i> Khôi phục
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.coupon.force-delete', $coupon->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-xs" onclick="return confirm('Xóa vĩnh viễn mã này?')">
                                                    <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        {{ $coupons->links('pagination::bootstrap-4') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection