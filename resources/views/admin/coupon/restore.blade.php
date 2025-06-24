@extends('layouts.admin')

@section('title', 'Khôi phục mã giảm giá')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Restore Coupons</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.coupon.index') }}">Coupons</a>
                </li>
                <li class="active">
                    <strong>Restore</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.coupon.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back
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
                                    <th data-toggle="true">Coupon Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>End Date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount_type }}</td>
                                    <td>{{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</td>
                                    <td>{{ $coupon->end_date }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.coupon.restore', $coupon->id) }}" class="btn btn-warning btn-xs" onclick="return confirm('Restore this coupon?')">
                                                <i class="fa fa-recycle"></i> Restore
                                            </a>
                                            <form action="{{ route('admin.coupon.force-delete', $coupon->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-xs" onclick="return confirm('Permanently delete this coupon?')">
                                                    <i class="fa fa-trash"></i> Delete Permanently
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
                                        <ul class="pagination pull-right"></ul>
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
