  @extends('layouts.admin')

  @section('title', 'Đơn hàng đã xoá')
  @section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Đơn hàng đã xoá</h2>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
        <li class="active"><strong>Đã xoá</strong></li>
      </ol>
    </div>
  </div>

  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h5>Danh sách đơn hàng đã xoá</h5>
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
                  <th>Tổng tiền</th>
                </tr>
              </thead>
              <tbody>
                @forelse($orders as $order)
                  <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->receiver_name }}</td>
                    <td>{{ $order->receiver_phone }}</td>
                    <td>{{ $order->address }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($order->total_amount) }} VNĐ</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">Không có đơn hàng nào đã xoá.</td>
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
