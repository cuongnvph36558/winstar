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

            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle" style="border-radius: 12px; overflow: hidden;">
                <thead class="thead-light" style="background: #f8f9fa;">
                  <tr>
                    <th class="text-center" style="width:40px">#</th>
                    <th style="max-width:140px;white-space:nowrap;">Khách hàng</th>
                    <th>Người nhận</th>
                    <th class="text-center">SĐT</th>
                    <th style="max-width:120px">Địa chỉ</th>
                    <th style="max-width:80px">Xã/Phường</th>
                    <th style="max-width:80px">Quận/Huyện</th>
                    <th style="max-width:100px">Tỉnh/TP</th>
                    <th style="max-width:120px">Mô tả</th>
                    <th style="max-width:80px">Mã giảm giá</th>
                    <th class="text-center">Trạng thái thanh toán</th>
                    <th class="text-center">Trạng thái đơn</th>
                    <th style="max-width:100px">Phương thức</th>
                    <th class="text-end">Tổng tiền</th>
                    <th class="text-center">Ngày đặt</th>
                    <th class="text-center">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                    <tr id="order-{{ $order->id }}">
                      <td class="text-center">{{ $order->id }}</td>
                      <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ isset($order->user) ? $order->user->name.' (ID: '.$order->user_id.')' : $order->user_id }}">
                        @if(isset($order->user) && $order->user)
                          <span>{{ \Illuminate\Support\Str::limit($order->user->name, 18) }} <span class="text-muted" style="font-size:11px">(ID: {{ $order->user_id }})</span></span>
                        @else
                          {{ $order->user_id }}
                        @endif
                      </td>
                      <td>{{ \Illuminate\Support\Str::limit($order->receiver_name, 18) }}</td>
                      <td class="text-center">{{ $order->phone }}</td>
                      <td title="{{ $order->billing_address }}">{{ \Illuminate\Support\Str::limit($order->billing_address, 18) }}</td>
                      <td>{{ $order->billing_ward }}</td>
                      <td>{{ $order->billing_district }}</td>
                      <td>{{ $order->billing_city }}</td>
                      <td title="{{ $order->description }}">{{ \Illuminate\Support\Str::limit($order->description, 18) }}</td>
                      <td title="{{ $order->coupon_id }}">{{ $order->coupon_id }}</td>
                      <td class="text-center">
                        <span class="badge @if($order->payment_status=='paid'||$order->payment_status=='completed') bg-success @elseif($order->payment_status=='pending') bg-warning text-dark @elseif($order->payment_status=='failed'||$order->payment_status=='cancelled') bg-danger @else bg-secondary @endif" style="font-size:13px;">
                          {{ ucfirst($order->payment_status) }}
                        </span>
                      </td>
                      <td class="text-center status-cell">
                        <span class="badge @if($order->status=='completed') bg-success @elseif($order->status=='pending') bg-warning text-dark @elseif($order->status=='cancelled') bg-danger @elseif($order->status=='shipping') bg-primary @else bg-info text-dark @endif" style="font-size:13px;">
                          {{ ucfirst($order->status) }}
                        </span>
                      </td>
                      <td title="{{ $order->payment_method }}">{{ \Illuminate\Support\Str::limit($order->payment_method, 14) }}</td>
                      <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                      <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                      <td class="text-center">
                        <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-xs btn-info" title="Xem"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-xs btn-warning" title="Sửa"><i class="fa fa-edit"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-3">
              {{ $orders->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
// Initialize Pusher for admin
try {
    window.pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
        wsHost: '{{ config("broadcasting.connections.pusher.options.host") }}',
        wsPort: {{ config("broadcasting.connections.pusher.options.port") }},
        forceTLS: {{ config("broadcasting.connections.pusher.options.useTLS") ? 'true' : 'false' }},
        disableStats: true,
        enabledTransports: ['ws', 'wss']
    });
    
    console.log('✅ Admin Pusher initialized for Laravel Websockets');
} catch (error) {
    console.error('❌ Failed to initialize Admin Pusher:', error);
}

// Listen for order status updates
if (window.pusher) {
    const ordersChannel = window.pusher.subscribe('orders');
    ordersChannel.bind('OrderStatusUpdated', function(e) {
        console.log('📡 Admin received OrderStatusUpdated event:', e);
        const row = document.querySelector(`#order-${e.order_id}`);
        if (row) {
            const statusCell = row.querySelector('.status-cell');
            if (statusCell) {
                const statusClass = e.new_status === 'completed' ? 'bg-success' : 
                                  e.new_status === 'pending' ? 'bg-warning text-dark' : 
                                  e.new_status === 'cancelled' ? 'bg-danger' : 
                                  e.new_status === 'shipping' ? 'bg-primary' : 'bg-info text-dark';
                statusCell.innerHTML = `<span class="badge ${statusClass}" style="font-size:13px;">${e.status_text}</span>`;
                console.log('✅ Order status updated in admin table');
            }
        }
    });
}
</script>
@endpush
