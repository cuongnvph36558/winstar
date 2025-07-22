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
                <input type="text" class="form-control" value="{{ $order->receiver_name }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Số điện thoại</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->phone }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Địa chỉ</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_address }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Xã/Phường</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_ward }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Quận/Huyện</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_district }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Tỉnh/Thành phố</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->billing_city }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Mô tả</label>
              <div class="col-sm-10">
                <textarea class="form-control" readonly>{{ $order->description }}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Mã giảm giá</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->coupon_id }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Trạng thái thanh toán</label>
              <div class="col-sm-10">
                <select name="payment_status" class="form-control" @if(strtolower($order->payment_method) == 'cod') disabled @endif>
                  <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                  <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                  <option value="processing" {{ $order->payment_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                  <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                  <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                  <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                  <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                @if(strtolower($order->payment_method) == 'cod')
                  <small class="text-muted">Không thể thay đổi trạng thái thanh toán với đơn COD</small>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Trạng thái đơn</label>
              <div class="col-sm-10">
                <select name="status" class="form-control">
                  @php
                    $statusFlow = [
                      'pending' => 1,
                      'processing' => 2,
                      'shipping' => 3,
                      'completed' => 4,
                      'cancelled' => 99
                    ];
                    $currentStatus = $order->status;
                  @endphp
                  @foreach(['pending' => 'Chờ xử lý', 'processing' => 'Đang chuẩn bị hàng', 'shipping' => 'Đang giao hàng', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'] as $value => $label)
                    @if(
                      ($value === 'cancelled' && in_array($currentStatus, ['shipping', 'completed']))
                        ? false
                        : ($value === 'cancelled' || $statusFlow[$value] >= $statusFlow[$currentStatus])
                    )
                      <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Phương thức thanh toán</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $order->payment_method }}" readonly>
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
