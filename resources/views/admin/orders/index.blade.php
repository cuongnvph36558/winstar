@extends('layouts.admin')

@section('title', 'Danh sách đơn hàng')
@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
    <h2>Đơn hàng</h2>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li class="active"><strong>Đơn hàng</strong></li>
    </ol>
    </div>
  </div>

  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Danh sách đơn hàng</h5>
      </div>
      <div class="ibox-content">
        @if(session('success'))
      <div class="alert alert-success">
      {{ session('success') }}
      </div>
      @endif

        <table class="table table-bordered">
        <thead>
          <tr>
          <th>#</th>
          <th>Người nhận</th>
          <th>SĐT</th>
          <th>Địa chỉ</th>
          <th>Ngày đặt</th>
          <th>Trạng thái</th>
          <th>Tổng tiền</th>
          <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
        <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->receiver_name }}</td>
        <td>{{ $order->phone }}</td>
        <td>
        {{ $order->billing_address }}<br>
        {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}
        </td>
        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        <td>{{ ucfirst($order->status) }}</td>
        <td>{{ number_format($order->total_amount) }} VNĐ</td>
        <td>
        <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-info btn-xs">Xem</a>
        <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-warning btn-xs">Sửa</a>
        <form action="{{ route('admin.order.delete', $order->id) }}" method="POST"
        style="display:inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-xs"
          onclick="return confirm('Bạn có chắc chắn muốn xoá?')">Xoá</button>
        </form>
        </td>
        </tr>
      @empty
        <tr>
        <td colspan="8" class="text-center">Không có đơn hàng nào.</td>
        </tr>
      @endforelse
        </tbody>

        </table>

        {{ $orders->links() }}
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection