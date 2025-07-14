@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
    <h2>Chi tiết đơn hàng</h2>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
      <li class="active"><strong>Chi tiết</strong></li>
    </ol>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Thông tin người nhận</h5>
        </div>
        <div class="ibox-content">
          <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
          <p><strong>SĐT:</strong> {{ $order->receiver_phone }}</p>
          <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
          <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
          <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
          <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
          <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
      </div>

      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Chi tiết sản phẩm</h5>
        </div>
        <div class="ibox-content">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderDetails as $index => $detail)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $detail->variant->variant_name ?? 'N/A' }}</td>
                  <td>{{ $detail->quantity }}</td>
                  <td>{{ number_format($detail->price) }} VNĐ</td>
                  <td>{{ number_format($detail->total) }} VNĐ</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
                <td><strong>{{ number_format($order->total_amount) }} VNĐ</strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Thông tin người nhận</h5>
      </div>
      <div class="ibox-content">
        <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
        <p><strong>SĐT:</strong> {{ $order->phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->billing_address }}</p>
        <p><strong>Địa chỉ chi tiết:</strong> {{ $order->billing_ward }}, {{ $order->billing_district }},
        {{ $order->billing_city }}</p>

        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
        <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
      </div>
      </div>

      <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Chi tiết sản phẩm</h5>
      </div>
      <div class="ibox-content">
        <table class="table table-bordered">
        <thead>
          <tr>
          <th>#</th>
          <th>Sản phẩm</th>
          <th>Số lượng</th>
          <th>Giá</th>
          <th>Thành tiền</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderDetails as $index => $detail)
        <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $detail->variant->variant_name ?? 'N/A' }}</td>
        <td>{{ $detail->quantity }}</td>
        <td>{{ number_format($detail->price) }} VNĐ</td>
        <td>{{ number_format($detail->price * $detail->quantity) }} VNĐ</td>
        </tr>
      @endforeach
        </tbody>
        <tfoot>
          <tr>
          <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
          <td><strong>{{ number_format($order->total_amount) }} VNĐ</strong></td>
          </tr>
        </tfoot>
        </table>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection