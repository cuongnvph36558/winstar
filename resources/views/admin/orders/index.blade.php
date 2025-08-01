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
            <div class="ibox-tools">
              <span class="badge bg-primary">{{ $orders->total() }} đơn hàng</span>
            </div>
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
                    <th class="text-center" style="width:60px">Mã ĐH</th>
                    <th style="width:150px">Khách hàng</th>
                    <th style="width:120px">Người nhận</th>
                    <th style="width:100px">SĐT</th>
                    <th style="width:200px">Địa chỉ</th>
                    <th class="text-center" style="width:100px">Trạng thái đơn</th>
                    <th class="text-end" style="width:120px">Tổng tiền</th>
                    <th class="text-center" style="width:100px">Ngày đặt</th>
                    <th class="text-center" style="width:100px">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                    <tr id="order-{{ $order->id }}">
                      <td class="text-center">
                        <strong>{{ $order->code_order ?? '#' . $order->id }}</strong>
                      </td>
                      <td>
                        @if(isset($order->user) && $order->user)
                          <div>{{ \Illuminate\Support\Str::limit($order->user->name, 20) }}</div>
                          <small class="text-muted">ID: {{ $order->user_id }}</small>
                        @else
                          <span class="text-muted">Khách vãng lai</span>
                        @endif
                      </td>
                      <td>{{ \Illuminate\Support\Str::limit($order->receiver_name, 15) }}</td>
                      <td>{{ $order->phone }}</td>
                      <td>
                        <div title="{{ $order->billing_address }}">
                          {{ \Illuminate\Support\Str::limit($order->billing_address, 25) }}
                        </div>
                        <small class="text-muted">
                          {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}
                        </small>
                      </td>
                      <td class="text-center">
                        <span class="badge @if($order->status=='completed') bg-success @elseif($order->status=='pending') bg-warning text-dark @elseif($order->status=='cancelled') bg-danger @elseif($order->status=='shipping') bg-primary @else bg-info text-dark @endif" style="font-size:12px;">
                          @if($order->status=='completed')
                            Hoàn thành
                          @elseif($order->status=='pending')
                            Chờ xử lý
                          @elseif($order->status=='shipping')
                            Đang giao
                          @elseif($order->status=='cancelled')
                            Đã hủy
                          @else
                            {{ ucfirst($order->status) }}
                          @endif
                        </span>
                      </td>
                      <td class="text-end">
                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                        @if($order->coupon_id)
                          <div><small class="text-success">Có mã giảm giá</small></div>
                        @endif
                      </td>
                      <td class="text-center">
                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                      </td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-xs btn-info" title="Xem chi tiết">
                            <i class="fa fa-eye"></i>
                          </a>
                          <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-xs btn-warning" title="Chuyển đổi trạng thái">
                            <i class="fa fa-exchange"></i>
                          </a>
                        </div>
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
        const row = document.querySelector('#order-' + e.order_id);
        if (row) {
            const statusCell = row.querySelector('td:nth-child(6)');
            if (statusCell) {
                let paymentStatusClass = 'bg-secondary';
                if (e.payment_status === 'paid' || e.payment_status === 'completed') {
                    paymentStatusClass = 'bg-success';
                } else if (e.payment_status === 'pending') {
                    paymentStatusClass = 'bg-warning text-dark';
                } else if (e.payment_status === 'failed' || e.payment_status === 'cancelled') {
                    paymentStatusClass = 'bg-danger';
                }
                
                let orderStatusClass = 'bg-info text-dark';
                if (e.new_status === 'completed') {
                    orderStatusClass = 'bg-success';
                } else if (e.new_status === 'pending') {
                    orderStatusClass = 'bg-warning text-dark';
                } else if (e.new_status === 'cancelled') {
                    orderStatusClass = 'bg-danger';
                } else if (e.new_status === 'shipping') {
                    orderStatusClass = 'bg-primary';
                }
                
                // Convert status to Vietnamese
                let orderStatusText = e.status_text;
                if (e.new_status === 'completed') {
                    orderStatusText = 'Hoàn thành';
                } else if (e.new_status === 'pending') {
                    orderStatusText = 'Chờ xử lý';
                } else if (e.new_status === 'shipping') {
                    orderStatusText = 'Đang giao';
                } else if (e.new_status === 'cancelled') {
                    orderStatusText = 'Đã hủy';
                }
                
                statusCell.innerHTML = '<span class="badge ' + orderStatusClass + '" style="font-size:12px;">' + orderStatusText + '</span>';
                console.log('✅ Order status updated in admin table');
            }
        }
    });
}
</script>
@endpush
