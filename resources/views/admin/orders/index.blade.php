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

            <!-- Filter Section -->
            <div class="row mb-3">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h5 class="panel-title">
                      <i class="fa fa-filter"></i> Bộ lọc đơn hàng
                      <button class="btn btn-xs btn-default pull-right" type="button" data-toggle="collapse" data-target="#filterCollapse">
                        <i class="fa fa-chevron-down"></i>
                      </button>
                    </h5>
                  </div>
                  <div class="panel-body collapse in" id="filterCollapse">
                    <form method="GET" action="{{ route('admin.order.index') }}" id="filterForm">
                      <div class="row">
                        <!-- Search -->
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tìm kiếm</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                   placeholder="Mã đơn, tên KH, SĐT, địa chỉ...">
                          </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Trạng thái đơn</label>
                            <select class="form-control" name="status">
                              <option value="all">Tất cả trạng thái</option>
                              @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                  @if($status=='completed')
                                    Hoàn thành
                                  @elseif($status=='pending')
                                    Chờ xử lý
                                  @elseif($status=='processing')
                                    Đang chuẩn bị hàng
                                  @elseif($status=='shipping')
                                    Đang giao hàng
                                  @elseif($status=='received')
                                    Đã nhận hàng
                                  @elseif($status=='cancelled')
                                    Đã hủy
                                  @else
                                    {{ ucfirst($status) }}
                                  @endif
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <!-- Payment Method Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Phương thức thanh toán</label>
                            <select class="form-control" name="payment_method">
                              <option value="all">Tất cả phương thức</option>
                              @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                                  {{ ucfirst($method) }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <!-- Payment Status Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Trạng thái thanh toán</label>
                            <select class="form-control" name="payment_status">
                              <option value="all">Tất cả trạng thái</option>
                              @foreach($paymentStatuses as $status)
                                <option value="{{ $status }}" {{ request('payment_status') == $status ? 'selected' : '' }}>
                                  @if($status=='pending')
                                    Chờ thanh toán
                                  @elseif($status=='paid')
                                    Đã thanh toán
                                  @elseif($status=='processing')
                                    Đang xử lý
                                  @elseif($status=='completed')
                                    Hoàn thành
                                  @elseif($status=='failed')
                                    Thất bại
                                  @elseif($status=='refunded')
                                    Hoàn tiền
                                  @elseif($status=='cancelled')
                                    Đã hủy
                                  @else
                                    {{ ucfirst($status) }}
                                  @endif
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <!-- Return Status Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Trạng thái đổi/trả</label>
                            <select class="form-control" name="return_status">
                              <option value="all">Tất cả</option>
                              @foreach($returnStatuses as $status)
                                <option value="{{ $status }}" {{ request('return_status') == $status ? 'selected' : '' }}>
                                  @if($status=='none')
                                    Không có
                                  @elseif($status=='requested')
                                    Chờ xử lý
                                  @elseif($status=='approved')
                                    Đã chấp thuận
                                  @elseif($status=='rejected')
                                    Đã từ chối
                                  @elseif($status=='completed')
                                    Hoàn thành
                                  @else
                                    {{ ucfirst($status) }}
                                  @endif
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <!-- City Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Tỉnh/Thành phố</label>
                            <select class="form-control" name="city">
                              <option value="all">Tất cả tỉnh/thành</option>
                              @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                  {{ $city }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <!-- District Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Quận/Huyện</label>
                            <select class="form-control" name="district">
                              <option value="all">Tất cả quận/huyện</option>
                              @foreach($districts as $district)
                                <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>
                                  {{ $district }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Từ ngày</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Đến ngày</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                          </div>
                        </div>

                        <!-- Amount Range -->
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Từ số tiền (VNĐ)</label>
                            <input type="number" class="form-control" name="amount_min" value="{{ request('amount_min') }}" 
                                   placeholder="0">
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Đến số tiền (VNĐ)</label>
                            <input type="number" class="form-control" name="amount_max" value="{{ request('amount_max') }}" 
                                   placeholder="1000000">
                          </div>
                        </div>

                        <!-- Has Coupon Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Có mã giảm giá</label>
                            <select class="form-control" name="has_coupon">
                              <option value="">Tất cả</option>
                              <option value="yes" {{ request('has_coupon') == 'yes' ? 'selected' : '' }}>Có</option>
                              <option value="no" {{ request('has_coupon') == 'no' ? 'selected' : '' }}>Không</option>
                            </select>
                          </div>
                        </div>

                        <!-- Points Used Filter -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Sử dụng điểm</label>
                            <select class="form-control" name="points_used">
                              <option value="">Tất cả</option>
                              <option value="yes" {{ request('points_used') == 'yes' ? 'selected' : '' }}>Có</option>
                              <option value="no" {{ request('points_used') == 'no' ? 'selected' : '' }}>Không</option>
                            </select>
                          </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Sắp xếp theo</label>
                            <select class="form-control" name="sort_by">
                              <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                              <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Tổng tiền</option>
                              <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Trạng thái</option>
                              <option value="payment_method" {{ request('sort_by') == 'payment_method' ? 'selected' : '' }}>Phương thức TT</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Thứ tự</label>
                            <select class="form-control" name="sort_order">
                              <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                              <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                          </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="col-md-12">
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                              <i class="fa fa-search"></i> Lọc
                            </button>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-default">
                              <i class="fa fa-refresh"></i> Làm mới
                            </a>
                            <button type="button" class="btn btn-info" id="exportBtn">
                              <i class="fa fa-download"></i> Xuất Excel
                            </button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filter Summary -->
            @if(request()->hasAny(['search', 'status', 'payment_method', 'payment_status', 'return_status', 'city', 'district', 'date_from', 'date_to', 'amount_min', 'amount_max', 'has_coupon', 'points_used']))
              <div class="alert alert-info">
                <strong>Bộ lọc đang áp dụng:</strong>
                <ul class="list-inline mb-0">
                  @if(request('search'))
                    <li><strong>Tìm kiếm:</strong> "{{ request('search') }}"</li>
                  @endif
                  @if(request('status') && request('status') !== 'all')
                    <li><strong>Trạng thái:</strong> {{ request('status') }}</li>
                  @endif
                  @if(request('payment_method') && request('payment_method') !== 'all')
                    <li><strong>Phương thức TT:</strong> {{ request('payment_method') }}</li>
                  @endif
                  @if(request('payment_status') && request('payment_status') !== 'all')
                    <li><strong>Trạng thái TT:</strong> {{ request('payment_status') }}</li>
                  @endif
                  @if(request('return_status') && request('return_status') !== 'all')
                    <li><strong>Đổi/trả:</strong> {{ request('return_status') }}</li>
                  @endif
                  @if(request('city') && request('city') !== 'all')
                    <li><strong>Tỉnh/TP:</strong> {{ request('city') }}</li>
                  @endif
                  @if(request('district') && request('district') !== 'all')
                    <li><strong>Quận/Huyện:</strong> {{ request('district') }}</li>
                  @endif
                  @if(request('date_from') || request('date_to'))
                    <li><strong>Ngày:</strong> 
                      {{ request('date_from') ? request('date_from') : 'Từ đầu' }} - 
                      {{ request('date_to') ? request('date_to') : 'Đến nay' }}
                    </li>
                  @endif
                  @if(request('amount_min') || request('amount_max'))
                    <li><strong>Số tiền:</strong> 
                      {{ request('amount_min') ? number_format(request('amount_min')) : '0' }} - 
                      {{ request('amount_max') ? number_format(request('amount_max')) : 'Không giới hạn' }} VNĐ
                    </li>
                  @endif
                  @if(request('has_coupon'))
                    <li><strong>Mã giảm giá:</strong> {{ request('has_coupon') == 'yes' ? 'Có' : 'Không' }}</li>
                  @endif
                  @if(request('points_used'))
                    <li><strong>Sử dụng điểm:</strong> {{ request('points_used') == 'yes' ? 'Có' : 'Không' }}</li>
                  @endif
                </ul>
              </div>
            @endif

            <div class="table-responsive">
              <table id="ordersTable" class="table table-bordered table-hover align-middle" style="border-radius: 12px; overflow: hidden;">
                <thead class="thead-light" style="background: #f8f9fa;">
                  <tr>
                    <th class="text-center" style="width:60px">Mã ĐH</th>
                    <th style="width:150px">Khách hàng</th>
                    <th style="width:120px">Người nhận</th>
                    <th style="width:100px">SĐT</th>
                    <th style="width:200px">Địa chỉ</th>
                    <th class="text-center" style="width:100px">Trạng thái đơn</th>
                    <th class="text-center" style="width:100px">Trạng thái TT</th>
                    <th class="text-end" style="width:120px">Tổng tiền</th>
                    <th class="text-center" style="width:100px">Ngày đặt</th>
                    <th class="text-center" style="width:100px">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                    <tr id="order-{{ $order->id }}" data-order-id="{{ $order->id }}">
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
                        <span class="status-badge status-{{ $order->status }} order-detail-status">
                          @if($order->status=='completed')
                            <i class="fa fa-check-circle mr-10"></i>Hoàn thành
                          @elseif($order->status=='pending')
                            <i class="fa fa-clock-o mr-10"></i>Chờ xử lý
                          @elseif($order->status=='processing')
                            <i class="fa fa-cogs mr-10"></i>Đang chuẩn bị hàng
                          @elseif($order->status=='shipping')
                            <i class="fa fa-truck mr-10"></i>Đang giao hàng
                          @elseif($order->status=='received')
                            <i class="fa fa-handshake-o mr-10"></i>Đã nhận hàng
                          @elseif($order->status=='cancelled')
                            <i class="fa fa-times-circle mr-10"></i>Đã hủy
                            @if($order->cancellation_reason)
                              <div class="cancellation-tooltip" title="Lý do: {{ $order->cancellation_reason }}">
                                <i class="fa fa-info-circle text-danger"></i>
                              </div>
                            @endif
                          @else
                            <i class="fa fa-question-circle mr-10"></i>{{ ucfirst($order->status) }}
                          @endif
                        </span>
                      </td>
                      <td class="text-center">
                        <span class="payment-status-badge payment-status-{{ $order->payment_status ?? 'pending' }}">
                          @if($order->payment_status=='pending')
                            <i class="fa fa-clock-o mr-10"></i>Chờ TT
                          @elseif($order->payment_status=='paid')
                            <i class="fa fa-check-circle mr-10"></i>Đã TT
                          @elseif($order->payment_status=='processing')
                            <i class="fa fa-cogs mr-10"></i>Đang xử lý
                          @elseif($order->payment_status=='completed')
                            <i class="fa fa-check-circle mr-10"></i>Hoàn thành
                          @elseif($order->payment_status=='failed')
                            <i class="fa fa-times-circle mr-10"></i>Thất bại
                          @elseif($order->payment_status=='refunded')
                            <i class="fa fa-undo mr-10"></i>Hoàn tiền
                          @elseif($order->payment_status=='cancelled')
                            <i class="fa fa-ban mr-10"></i>Đã hủy
                          @else
                            <i class="fa fa-question-circle mr-10"></i>{{ ucfirst($order->payment_status ?? 'pending') }}
                          @endif
                        </span>
                      </td>
                      <td class="text-end">
                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                        @if($order->coupon_id)
                          <div><small class="text-success">Có mã giảm giá</small></div>
                        @endif
                        @if($order->points_used > 0)
                          <div><small class="text-info">Điểm: -{{ number_format($order->points_used) }}</small></div>
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
    // Remove duplicate realtime handlers - they are handled in realtime-notifications.js
    if (window.pusher) {
        const ordersChannel = window.pusher.channel('orders');
        const adminOrdersChannel = window.pusher.channel('admin.orders');
        
        if (ordersChannel) {
            ordersChannel.unbind('NewOrderPlaced');
        }
        
        if (adminOrdersChannel) {
            adminOrdersChannel.unbind('NewOrderPlaced');
        }
    }

    // Auto-submit form when select values change
    $('select[name="status"], select[name="payment_method"], select[name="payment_status"], select[name="return_status"], select[name="city"], select[name="district"], select[name="has_coupon"], select[name="points_used"], select[name="sort_by"], select[name="sort_order"]').change(function() {
        $('#filterForm').submit();
    });

    // Export functionality
    $('#exportBtn').click(function() {
        var currentUrl = window.location.href;
        var exportUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'export=excel';
        window.open(exportUrl, '_blank');
    });
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

/* Cancellation tooltip styling */
.cancellation-tooltip {
    display: inline-block;
    margin-left: 5px;
    cursor: help;
}

.cancellation-tooltip i {
    font-size: 12px;
    animation: pulse 2s infinite;
}

.cancellation-tooltip:hover {
    transform: scale(1.2);
    transition: transform 0.2s ease;
}

/* Payment status badges */
.payment-status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-align: center;
    min-width: 80px;
}

.payment-status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.payment-status-paid {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.payment-status-processing {
    background-color: #cce5ff;
    color: #004085;
    border: 1px solid #b3d7ff;
}

.payment-status-completed {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.payment-status-failed {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.payment-status-refunded {
    background-color: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8db;
}

.payment-status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Filter panel styling */
.panel-default {
    border-color: #ddd;
}

.panel-heading {
    background-color: #f5f5f5;
    border-color: #ddd;
    padding: 10px 15px;
}

.panel-title {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.panel-body {
    padding: 15px;
    background-color: #fafafa;
}

/* Filter summary styling */
.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-info ul {
    margin-bottom: 0;
}

.alert-info li {
    margin-right: 15px;
    margin-bottom: 5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .panel-body .row > div {
        margin-bottom: 10px;
    }
    
    .alert-info li {
        display: block;
        margin-bottom: 5px;
    }
}
</style>
@endpush
