@extends('layouts.admin')

@section('title', 'Chỉnh sửa đơn hàng')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>Chỉnh sửa đơn hàng</h2>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
      <li class="active"><strong>Chỉnh sửa</strong></li>
    </ol>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Thông tin đơn hàng</h5>
        </div>
        <div class="ibox-content">
          <form method="POST" action="{{ route('admin.order.update', $order->id) }}" class="form-horizontal">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label class="col-sm-2 control-label">Tên người nhận</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->receiver_name }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Số điện thoại</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->phone }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Địa chỉ</label>
              <div class="col-sm-10">
                <textarea class="form-control" disabled>{{ $order->billing_address }}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Xã / Phường</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_ward }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Quận / Huyện</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_district }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Tỉnh / Thành phố</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_city }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Phương thức thanh toán</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ ucfirst($order->payment_method) }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Trạng thái</label>
              <div class="col-sm-10">
                <select name="status" class="form-control">
                  <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                  <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                  <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã giao</option>
                  <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                </select>
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <a class="btn btn-white" href="{{ route('admin.order.index') }}">Huỷ</a>
                <button class="btn btn-primary" type="submit">Cập nhật</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
