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
    <div class="col-lg-2">
      <div class="title-action">
        <span class="badge bg-success" id="new-order-badge" style="display: none;">
          <i class="fa fa-bell"></i> Đơn hàng mới!
        </span>
      </div>
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
<script>
$(document).ready(function() {
    // Override layout realtime handlers for better UX
    if (window.pusher) {
        // Remove default reload handlers
        const ordersChannel = window.pusher.channel('orders');
        const adminOrdersChannel = window.pusher.channel('admin.orders');
        
        if (ordersChannel) {
            ordersChannel.unbind('NewOrderPlaced');
            ordersChannel.bind('NewOrderPlaced', function(data) {
                console.log('🎉 New order received:', data);
                showNewOrderNotification(data);
                addNewOrderToTable(data);
            });
        }
        
        if (adminOrdersChannel) {
            adminOrdersChannel.unbind('NewOrderPlaced');
            adminOrdersChannel.bind('NewOrderPlaced', function(data) {
                console.log('🎉 New order received on admin channel:', data);
                showNewOrderNotification(data);
                addNewOrderToTable(data);
            });
        }
    }
    
    function showNewOrderNotification(data) {
        // Show notification badge
        const badge = $('#new-order-badge');
        badge.show().addClass('pulse');
        
        // Show toast notification
        toastr.success(
            `Đơn hàng mới! #${data.order_code} từ ${data.user_name} - ${new Intl.NumberFormat('vi-VN').format(data.total_amount)}₫`,
            'Đơn hàng mới',
            {
                timeOut: 5000,
                extendedTimeOut: 2000,
                closeButton: true,
                progressBar: true
            }
        );
        
        // Remove pulse effect after 3 seconds
        setTimeout(function() {
            badge.removeClass('pulse');
        }, 3000);
    }
    
    function addNewOrderToTable(data) {
        // Create new row HTML
        const newRow = `
            <tr id="order-${data.order_id}" class="new-order-row" style="background-color: #d4edda;">
                <td class="text-center">
                    <strong>${data.order_code}</strong>
                </td>
                <td>
                    <div>${data.user_name}</div>
                    <small class="text-muted">ID: ${data.user_id}</small>
                </td>
                <td>${data.receiver_name}</td>
                <td>${data.user_phone}</td>
                <td>
                    <div title="${data.billing_address}">
                        ${data.billing_address.length > 25 ? data.billing_address.substring(0, 25) + '...' : data.billing_address}
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-warning text-dark" style="font-size:12px;">
                        ${data.status_text}
                    </span>
                </td>
                <td class="text-end">
                    <strong>${new Intl.NumberFormat('vi-VN').format(data.total_amount)}₫</strong>
                </td>
                <td class="text-center">
                    <div>${new Date(data.created_at).toLocaleDateString('vi-VN')}</div>
                    <small class="text-muted">${new Date(data.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}</small>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <a href="/admin/orders/${data.order_id}" class="btn btn-xs btn-info" title="Xem chi tiết">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="/admin/orders/${data.order_id}/edit" class="btn btn-xs btn-warning" title="Chuyển đổi trạng thái">
                            <i class="fa fa-exchange"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
        
        // Add to top of table
        $('tbody').prepend(newRow);
        
        // Remove highlight after 5 seconds
        setTimeout(function() {
            $(`#order-${data.order_id}`).removeClass('new-order-row').css('background-color', '');
        }, 5000);
        
        // Update order count
        const currentCount = parseInt($('.ibox-tools .badge').text().match(/\d+/)[0]);
        $('.ibox-tools .badge').text(`${currentCount + 1} đơn hàng`);
    }
});
</script>

<style>
.pulse {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.new-order-row {
    transition: background-color 0.5s ease;
}

#new-order-badge {
    font-size: 12px;
    padding: 5px 10px;
}
</style>
@endpush
