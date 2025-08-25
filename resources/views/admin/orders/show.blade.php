@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <!-- Page Header -->
      <div class="page-header-section">
        <div class="header-content">
          <div class="header-left">
            <h2><i class="fa fa-file-text text-primary"></i> Chi tiết đơn hàng</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
              </ol>
            </nav>
          </div>
          <div class="header-actions">
            <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-lg">
              <i class="fa fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-primary btn-lg">
              <i class="fa fa-exchange"></i> Chuyển đổi trạng thái
            </a>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <!-- Order Summary Card -->
        <div class="col-lg-4 col-md-6">
          <div class="info-card order-summary-card">
            <div class="card-header">
              <i class="fa fa-info-circle"></i>
              <h6>Thông tin đơn hàng</h6>
            </div>
            <div class="card-body">
              <div class="order-id-section">
                <div class="order-id-badge">
                  <i class="fa fa-hashtag"></i>
                  <span>{{ $order->code_order ?? '#' . $order->id }}</span>
                </div>
              </div>
              
              <div class="info-item">
                <i class="fa fa-user text-primary"></i>
                <span><strong>Khách hàng:</strong> {{ $order->user->name ?? 'ID: '.$order->user_id }}</span>
              </div>
              
              <div class="info-item">
                <i class="fa fa-user-circle text-success"></i>
                <span><strong>Người nhận:</strong> {{ $order->receiver_name }}</span>
              </div>
              
              <div class="info-item">
                <i class="fa fa-phone text-info"></i>
                <span><strong>Số điện thoại:</strong> {{ $order->phone }}</span>
              </div>
              
              <div class="info-item">
                <i class="fa fa-map-marker text-danger"></i>
                <span><strong>Địa chỉ:</strong> {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</span>
              </div>
              
              @if($order->description)
              <div class="info-item">
                <i class="fa fa-comment text-warning"></i>
                <span><strong>Ghi chú:</strong> {{ $order->description }}</span>
              </div>
              @endif
              
              <div class="info-item">
                <i class="fa fa-calendar text-secondary"></i>
                <span><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment & Status Card -->
        <div class="col-lg-4 col-md-6">
          <div class="info-card payment-status-card">
            <div class="card-header">
              <i class="fa fa-credit-card"></i>
              <h6>Thanh toán & Trạng thái</h6>
            </div>
            <div class="card-body">
              <div class="info-item">
                <i class="fa fa-money text-success"></i>
                <span><strong>Tổng tiền:</strong> <span class="amount">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span></span>
              </div>
              
              <div class="info-item">
                <i class="fa fa-credit-card text-info"></i>
                <span><strong>Phương thức:</strong> 
                  @switch($order->payment_method)
                    @case('cod')
                      <span class="payment-badge cod">Thanh toán khi nhận hàng</span>
                      @break
                    
                    @case('vnpay')
                      <span class="payment-badge vnpay">VNPay</span>
                      @break
                    @default
                      <span class="payment-badge default">{{ $order->payment_method }}</span>
                  @endswitch
                </span>
              </div>
              
              <div class="info-item">
                <i class="fa fa-tag text-warning"></i>
                <span><strong>Mã giảm giá:</strong> 
                  @if($order->coupon)
                    <span class="coupon-badge">{{ $order->coupon->code ?? $order->coupon_id }}</span>
                  @else
                    <span class="text-muted">Không có</span>
                  @endif
                </span>
              </div>
              
              <div class="status-section">
                <div class="status-item">
                  <label>Trạng thái thanh toán:</label>
                  @php
                    $paymentStatusVN = [
                      'pending' => ['label' => 'Chờ thanh toán', 'color' => '#ffc107', 'icon' => 'clock-o'],
                      'paid' => ['label' => 'Đã thanh toán', 'color' => '#28a745', 'icon' => 'check-circle'],
                      'processing' => ['label' => 'Đang chuẩn bị hàng', 'color' => '#17a2b8', 'icon' => 'cogs'],
                      'completed' => ['label' => 'Hoàn thành', 'color' => '#28a745', 'icon' => 'check-circle'],
                      'failed' => ['label' => 'Thất bại', 'color' => '#dc3545', 'icon' => 'times-circle'],
                      'refunded' => ['label' => 'Hoàn tiền', 'color' => '#6f42c1', 'icon' => 'undo'],
                      'cancelled' => ['label' => 'Đã hủy', 'color' => '#dc3545', 'icon' => 'times-circle'],
                    ];
                    $paymentStatus = $paymentStatusVN[$order->payment_status] ?? ['label' => ucfirst($order->payment_status), 'color' => '#6c757d', 'icon' => 'info-circle'];
                  @endphp
                  <div class="status-badge" style="background: {{ $paymentStatus['color'] }}20; color: {{ $paymentStatus['color'] }}; border-color: {{ $paymentStatus['color'] }};">
                    <i class="fa fa-{{ $paymentStatus['icon'] }}"></i>
                    {{ $paymentStatus['label'] }}
                  </div>
                </div>
                
                <div class="status-item">
                  <label>Trạng thái đơn hàng:</label>
                  @php
                    $orderStatusVN = [
                      'pending' => ['label' => 'Chờ xử lý', 'color' => '#ffc107', 'icon' => 'clock-o'],
                      'processing' => ['label' => 'Đang chuẩn bị hàng', 'color' => '#17a2b8', 'icon' => 'cogs'],
                      'shipping' => ['label' => 'Đang giao hàng', 'color' => '#007bff', 'icon' => 'truck'],
                      'delivered' => ['label' => 'Đã giao hàng', 'color' => '#fd7e14', 'icon' => 'check-square-o'],
                      'received' => ['label' => 'Đã nhận hàng', 'color' => '#6f42c1', 'icon' => 'handshake-o'],
                      'completed' => ['label' => 'Hoàn thành', 'color' => '#28a745', 'icon' => 'check-circle'],
                      'cancelled' => ['label' => 'Đã hủy', 'color' => '#dc3545', 'icon' => 'times-circle'],
                    ];
                    $orderStatus = $orderStatusVN[$order->status] ?? ['label' => ucfirst($order->status), 'color' => '#6c757d', 'icon' => 'info-circle'];
                  @endphp
                  <div class="status-badge" style="background: {{ $orderStatus['color'] }}20; color: {{ $orderStatus['color'] }}; border-color: {{ $orderStatus['color'] }};">
                    <i class="fa fa-{{ $orderStatus['icon'] }}"></i>
                    {{ $orderStatus['label'] }}
                  </div>
                  
                  @if($order->status === 'cancelled' && $order->cancellation_reason)
                    <div class="cancellation-details mt-3">
                      <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-triangle"></i> Lý do hủy đơn hàng:</h6>
                        <p class="mb-2">{{ $order->cancellation_reason }}</p>
                        <small class="text-muted">
                          <i class="fa fa-clock-o"></i> 
                          Hủy lúc: {{ $order->cancelled_at ? \Carbon\Carbon::parse($order->cancelled_at)->format('d/m/Y H:i:s') : 'N/A' }}
                        </small>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Progress Card -->
        <div class="col-lg-4 col-md-12">
          <div class="info-card progress-card">
            <div class="card-header">
              <i class="fa fa-chart-line"></i>
              <h6>Tiến trình đơn hàng</h6>
            </div>
            <div class="card-body">
              @php
                $statusFlow = [
                  'pending' => ['label' => 'Chờ xử lý', 'color' => '#ffc107', 'icon' => 'clock-o'],
                  'processing' => ['label' => 'Đang chuẩn bị hàng', 'color' => '#17a2b8', 'icon' => 'cogs'],
                  'shipping' => ['label' => 'Đang giao hàng', 'color' => '#007bff', 'icon' => 'truck'],
                  'delivered' => ['label' => 'Đã giao hàng', 'color' => '#fd7e14', 'icon' => 'check-square-o'],
                  'received' => ['label' => 'Đã nhận hàng', 'color' => '#6f42c1', 'icon' => 'handshake-o'],
                  'completed' => ['label' => 'Hoàn thành', 'color' => '#28a745', 'icon' => 'check-circle'],
                  'cancelled' => ['label' => 'Đã hủy', 'color' => '#dc3545', 'icon' => 'times-circle']
                ];
                $currentStatus = $order->status;
                $currentStep = array_search($currentStatus, array_keys($statusFlow)) + 1;
                $progressPercent = ($currentStep / count($statusFlow)) * 100;
                if ($currentStatus === 'cancelled') $progressPercent = 100;
              @endphp
              
              <div class="progress-container">
                <div class="progress-bar-bg">
                  <div class="progress-bar-fill" style="width: {{ $progressPercent }}%; background: {{ $orderStatus['color'] }};"></div>
                </div>
                <div class="progress-text">
                  <span>Bước {{ $currentStep }} / {{ count($statusFlow) }}</span>
                  <span class="progress-percent">{{ round($progressPercent) }}%</span>
                </div>
              </div>
              
              <div class="status-flow-mini">
                @foreach($statusFlow as $status => $info)
                  @php
                    $statusIndex = array_search($status, array_keys($statusFlow)) + 1;
                    $isCurrent = ($currentStatus === $status);
                    $isCompleted = array_search($currentStatus, array_keys($statusFlow)) >= array_search($status, array_keys($statusFlow));
                    $isCancelled = ($currentStatus === 'cancelled');
                  @endphp
                  <div class="status-step-mini {{ $isCurrent ? 'current' : ($isCompleted ? 'completed' : 'pending') }} {{ $isCancelled && $status === 'cancelled' ? 'cancelled' : '' }}">
                    <div class="step-icon-mini" style="color: {{ $info['color'] }};">
                      <i class="fa fa-{{ $info['icon'] }}"></i>
                    </div>
                    <div class="step-label-mini">
                      <small>{{ $info['label'] }}</small>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cancellation Details Card (only show if order is cancelled) -->
      @if($order->status === 'cancelled' && $order->cancellation_reason)
        <div class="cancellation-detail-card">
          <div class="card-header bg-danger text-white">
            <i class="fa fa-exclamation-triangle"></i>
            <h6>Thông tin hủy đơn hàng</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="cancellation-reason">
                  <h6><i class="fa fa-comment text-danger"></i> Lý do hủy đơn hàng:</h6>
                  <div class="reason-content">
                    <p>{{ $order->cancellation_reason }}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="cancellation-info">
                  <div class="info-item">
                    <i class="fa fa-clock-o text-danger"></i>
                    <span><strong>Thời gian hủy:</strong></span>
                    <div>{{ $order->cancelled_at ? \Carbon\Carbon::parse($order->cancelled_at)->format('d/m/Y H:i:s') : 'N/A' }}</div>
                  </div>
                  <div class="info-item">
                    <i class="fa fa-user text-danger"></i>
                    <span><strong>Người hủy:</strong></span>
                    <div>{{ $order->user->name ?? 'Khách hàng' }}</div>
                  </div>
                  <div class="info-item">
                    <i class="fa fa-envelope text-danger"></i>
                    <span><strong>Email:</strong></span>
                    <div>{{ $order->user->email ?? 'N/A' }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Return/Exchange Status Info (Read-only) -->
      @if($order->return_status !== 'none')
        <div class="return-status-info-card">
          <div class="card-header">
            <i class="fa fa-exchange text-warning"></i>
            <h6>Thông tin đổi hoàn hàng</h6>
            <a href="{{ route('admin.return-exchange.show', $order->id) }}" class="btn btn-primary btn-sm">
              <i class="fa fa-external-link"></i> Xem chi tiết
            </a>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="return-info">
                  <div class="info-item">
                    <i class="fa fa-exclamation-triangle text-warning"></i>
                    <span><strong>Lý do:</strong> {{ $order->return_reason }}</span>
                  </div>
                  @if($order->return_description)
                    <div class="info-item">
                      <i class="fa fa-comment text-info"></i>
                      <span><strong>Mô tả:</strong> {{ Str::limit($order->return_description, 100) }}</span>
                    </div>
                  @endif
                  <div class="info-item">
                    <i class="fa fa-cog text-primary"></i>
                    <span><strong>Phương thức:</strong> 
                                              @switch($order->return_method)
                            @case('points')
                                <span class="badge badge-primary">Đổi điểm</span>
                                @break
                            @case('exchange')
                                <span class="badge badge-warning">Đổi hàng</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ $order->return_method }}</span>
                        @endswitch
                    </span>
                  </div>
                  <div class="info-item">
                    <i class="fa fa-calendar text-success"></i>
                    <span><strong>Ngày yêu cầu:</strong> {{ $order->return_requested_at ? $order->return_requested_at->format('d/m/Y H:i') : 'N/A' }}</span>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="return-status-section">
                  <div class="status-item">
                    <label>Trạng thái:</label>
                    @php
                      $returnStatusVN = [
                        'requested' => ['label' => 'Chờ xử lý', 'color' => '#ffc107', 'icon' => 'clock-o'],
                        'approved' => ['label' => 'Đã chấp thuận', 'color' => '#28a745', 'icon' => 'check-circle'],
                        'rejected' => ['label' => 'Đã từ chối', 'color' => '#dc3545', 'icon' => 'times-circle'],
                        'completed' => ['label' => 'Hoàn thành', 'color' => '#007bff', 'icon' => 'flag-checkered'],
                      ];
                      $returnStatus = $returnStatusVN[$order->return_status] ?? ['label' => ucfirst($order->return_status), 'color' => '#6c757d', 'icon' => 'info-circle'];
                    @endphp
                    <div class="status-badge" style="background: {{ $returnStatus['color'] }}20; color: {{ $returnStatus['color'] }}; border-color: {{ $returnStatus['color'] }};">
                      <i class="fa fa-{{ $returnStatus['icon'] }}"></i>
                      {{ $returnStatus['label'] }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Products Detail Card -->
      <div class="products-detail-card">
        <div class="card-header">
          <i class="fa fa-list-ul"></i>
          <h6>Chi tiết sản phẩm</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table products-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ảnh</th>
                  <th>Sản phẩm</th>
                  <th>Biến thể</th>
                  <th>Dung lượng</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-end">Đơn giá</th>
                  <th class="text-end">Phí ship</th>
                  <th class="text-center">Mã giảm giá</th>
                  <th class="text-end">Thành tiền</th>
                  <th class="text-center">Hoàn hàng</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->details as $i => $detail)
                <tr class="product-row {{ $detail->is_returned ? 'bg-orange-50' : '' }}">
                  <td class="product-index">{{ $i+1 }}</td>
                  <td class="product-image">
                    @if($detail->product && $detail->product->image)
                      <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product_name }}" class="product-thumbnail">
                    @else
                      <div class="no-image">
                        <i class="fa fa-image"></i>
                      </div>
                    @endif
                  </td>
                  <td class="product-name">
                    <strong>{{ $detail->product->name ?? 'SP#'.$detail->product_id }}</strong>
                    @if($detail->product && $detail->product->category)
                      <br><small class="text-muted">{{ $detail->product->category->name }}</small>
                    @endif
                  </td>
                  <td class="product-variant">
                    @if($detail->original_variant_name)
                      <span class="variant-badge">{{ $detail->original_variant_name }}</span>
                    @elseif($detail->variant)
                      <span class="variant-badge">{{ $detail->variant->variant_name }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-storage">
                    @if($detail->original_storage_capacity)
                      <span class="storage-badge">{{ \App\Helpers\StorageHelper::formatCapacity($detail->original_storage_capacity) }}</span>
                    @elseif($detail->original_storage_name)
                      <span class="storage-badge">{{ $detail->original_storage_name }}</span>
                    @elseif($detail->variant && $detail->variant->storage && isset($detail->variant->storage->capacity))
                      <span class="storage-badge">{{ \App\Helpers\StorageHelper::formatCapacity($detail->variant->storage->capacity) }}</span>
                    @elseif($detail->variant && $detail->variant->capacity)
                      <span class="storage-badge">{{ \App\Helpers\StorageHelper::formatCapacity($detail->variant->capacity) }}</span>
                    @elseif($detail->variant && $detail->variant->variant_name && strpos(strtolower($detail->variant->variant_name), 'gb') !== false)
                      @php
                        preg_match('/(\d+)\s*gb/i', $detail->variant->variant_name, $matches);
                        $capacity = $matches[1] ?? '';
                      @endphp
                      @if($capacity)
                        <span class="storage-badge">{{ \App\Helpers\StorageHelper::formatCapacity($capacity) }}</span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    @elseif($detail->variant && $detail->variant->storage_id)
                      @php
                        // Try to get storage capacity from database
                        $storage = \App\Models\Storage::find($detail->variant->storage_id);
                      @endphp
                      @if($storage && isset($storage->capacity))
                        <span class="storage-badge">{{ \App\Helpers\StorageHelper::formatCapacity($storage->capacity) }}</span>
                      @elseif($storage && $storage->name)
                        <span class="storage-badge">{{ $storage->name }}</span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-quantity text-center">
                    <span class="quantity-badge">{{ $detail->quantity ?? 0 }}</span>
                  </td>
                  <td class="product-unit-price text-end">
                    @php
                      $basePrice = null;
                      if ($detail->price) {
                        $basePrice = $detail->price;
                      } elseif ($detail->variant && $detail->variant->price) {
                        $basePrice = $detail->variant->price;
                      } elseif ($detail->product && $detail->product->price) {
                        $basePrice = $detail->product->price;
                      }
                    @endphp
                    @if($basePrice)
                      <span class="unit-price">{{ number_format($basePrice, 0, ',', '.') }}₫</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-shipping text-end">
                    @if($loop->first)
                      <span class="shipping-fee">30.000₫</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-coupon text-center">
                    @php
                      $discountPerItem = 0;
                      if ($order->discount_amount > 0 && $order->orderDetails->count() > 0) {
                        $discountPerItem = $order->discount_amount / $order->orderDetails->count();
                      }
                    @endphp
                    @if($discountPerItem > 0)
                      <span class="discount-amount">-{{ number_format($discountPerItem, 0, ',', '.') }}₫</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-total text-end">
                    @php
                      $basePrice = null;
                      if ($detail->price) {
                        $basePrice = $detail->price;
                      } elseif ($detail->variant && $detail->variant->price) {
                        $basePrice = $detail->variant->price;
                      } elseif ($detail->product && $detail->product->price) {
                        $basePrice = $detail->product->price;
                      }
                      
                      $discountPerItem = 0;
                      
                      // Tính toán giảm giá cho từng sản phẩm
                      if ($order->discount_amount > 0 && $order->orderDetails->count() > 0) {
                        $discountPerItem = $order->discount_amount / $order->orderDetails->count();
                      }
                      
                      // Thành tiền = (đơn giá × số lượng) - giảm giá
                      $totalPricePerItem = ($basePrice * $detail->quantity) - $discountPerItem;
                    @endphp
                    @if($basePrice)
                      <span class="total">{{ number_format($totalPricePerItem, 0, ',', '.') }}₫</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-return text-center">
                    @if($detail->is_returned)
                      <div class="return-info">
                        <span class="badge badge-warning">
                          <i class="fa fa-undo"></i> Hoàn hàng
                        </span>
                        <br>
                        <small class="text-muted">
                          {{ $detail->return_quantity }}/{{ $detail->quantity }}
                        </small>
                        <br>
                        <small class="text-success">
                          {{ number_format($detail->return_amount) }}đ
                        </small>
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Overall Layout */
.wrapper-content {
  padding: 30px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  min-height: 100vh;
}

/* Page Header */
.page-header-section {
  background: white;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  margin-bottom: 30px;
  overflow: hidden;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 25px 30px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.header-left h2 {
  margin: 0 0 10px 0;
  font-weight: 600;
  font-size: 28px;
}

.header-left h2 i {
  margin-right: 15px;
}

.breadcrumb {
  background: transparent;
  padding: 0;
  margin: 0;
}

.breadcrumb-item a {
  color: rgba(255,255,255,0.8);
  text-decoration: none;
}

.breadcrumb-item.active {
  color: white;
}

.header-actions {
  display: flex;
  gap: 15px;
}

/* Info Cards */
.info-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  margin-bottom: 25px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 20px 25px;
  border-bottom: 2px solid #dee2e6;
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-header i {
  font-size: 20px;
  color: #007bff;
}

.card-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 16px;
}

.card-body {
  padding: 25px;
  background: white;
}

/* Order ID Section */
.order-id-section {
  text-align: center;
  margin-bottom: 20px;
  padding: 20px;
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border-radius: 12px;
  color: white;
}

.order-id-badge {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-size: 20px;
  font-weight: 700;
}

.order-id-badge i {
  font-size: 24px;
}

/* Info Items */
.info-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 15px;
  padding: 12px 15px;
  border-radius: 8px;
  background: #f8f9fa;
  transition: background 0.3s ease;
}

.info-item:hover {
  background: #e9ecef;
}

.info-item i {
  font-size: 16px;
  width: 20px;
  text-align: center;
  margin-top: 2px;
}

.info-item span {
  flex: 1;
  color: #495057;
  line-height: 1.4;
}

.amount {
  font-size: 20px;
  font-weight: bold;
  color: #28a745;
}

/* Payment Badges */
.payment-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  color: white;
}

.payment-badge.cod {
  background: #17a2b8;
}



.payment-badge.vnpay {
  background: #007bff;
}

.payment-badge.default {
  background: #6c757d;
}

.coupon-badge {
  background: #28a745;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

/* Status Section */
.status-section {
  margin-top: 20px;
}

.status-item {
  margin-bottom: 15px;
}

.status-item label {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 8px;
  font-size: 14px;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
  border-radius: 20px;
  border: 2px solid;
  font-size: 13px;
  font-weight: 600;
}

/* Progress Section */
.progress-container {
  margin-bottom: 20px;
}

.progress-bar-bg {
  height: 10px;
  background: #e9ecef;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 10px;
}

.progress-bar-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 0.8s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.progress-text {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
  color: #6c757d;
}

.progress-percent {
  font-weight: 600;
  color: #007bff;
}

/* Status Flow Mini */
.status-flow-mini {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.status-step-mini {
  flex: 1;
  text-align: center;
  padding: 10px 5px;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.status-step-mini.current {
  background: rgba(0,123,255,0.1);
  border: 2px solid #007bff;
}

.status-step-mini.completed {
  background: rgba(40,167,69,0.1);
  border: 2px solid #28a745;
}

.status-step-mini.pending {
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  opacity: 0.6;
}

.status-step-mini.cancelled {
  background: rgba(220,53,69,0.1);
  border: 2px solid #dc3545;
}

.step-icon-mini {
  margin-bottom: 5px;
}

.step-icon-mini i {
  font-size: 16px;
}

.step-label-mini small {
  font-size: 10px;
  line-height: 1.2;
}

/* Products Detail Card */
.products-detail-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-top: 30px;
}

.products-table {
  margin: 0;
}

.products-table thead th {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: none;
  padding: 15px 12px;
  font-weight: 600;
  color: #495057;
  font-size: 14px;
}

.products-table tbody tr {
  transition: background 0.3s ease;
}

.products-table tbody tr:hover {
  background: #f8f9fa;
}

.products-table td {
  padding: 15px 12px;
  border: none;
  border-bottom: 1px solid #f1f3f4;
  vertical-align: middle;
}

.product-index {
  font-weight: 600;
  color: #007bff;
  text-align: center;
}

.product-thumbnail {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.no-image {
  width: 50px;
  height: 50px;
  background: #f8f9fa;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #adb5bd;
  font-size: 20px;
}

.product-name strong {
  color: #495057;
  font-size: 14px;
}

.variant-badge, .storage-badge {
  background: #e3f2fd;
  color: #1976d2;
  padding: 6px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
  min-width: 60px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.storage-badge {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeaa7;
}

.variant-badge {
  background: #e3f2fd;
  color: #1976d2;
  border: 1px solid #bbdefb;
}

.quantity-badge {
  background: #fff3cd;
  color: #856404;
  padding: 6px 12px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 14px;
}

.price, .total {
  font-weight: 600;
  color: #495057;
}

/* Price Breakdown Styling */
.price-breakdown {
  display: flex;
  flex-direction: column;
  gap: 4px;
  font-size: 12px;
  position: relative;
  z-index: 1;
  background: #f8f9fa;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #e9ecef;
  min-width: 180px;
}

.price-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 8px;
}

.price-label {
  color: #6c757d;
  font-weight: 500;
  font-size: 11px;
  min-width: 60px;
}

.base-price {
  color: #495057;
  font-weight: 600;
  font-size: 13px;
}

.shipping-fee {
  color: #17a2b8;
  font-weight: 600;
  font-size: 13px;
}

.item-discount {
  color: #dc3545;
  font-weight: 600;
  font-size: 13px;
}

.total-row {
  border-top: 1px solid #dee2e6;
  padding-top: 4px;
  margin-top: 2px;
}



.total-price {
  color: #28a745;
  font-weight: 700;
  font-size: 14px;
}

/* Unit Price Styling */
.unit-price {
  color: #495057;
  font-weight: 600;
  font-size: 14px;
}

/* Shipping Fee Styling */
.shipping-fee {
  color: #17a2b8;
  font-weight: 600;
  font-size: 14px;
}

/* Ensure table cell positioning */
.product-price {
  position: relative;
  overflow: visible;
}

/* Prevent price breakdown from jumping */
.price-breakdown {
  max-width: 120px;
  word-wrap: break-word;
  white-space: nowrap;
}

.total {
  color: #28a745;
  font-size: 16px;
}

/* Coupon Styling */
.coupon-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.coupon-code {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 4px rgba(40,167,69,0.3);
}

.discount-amount {
  color: #dc3545;
  font-weight: 600;
  font-size: 12px;
}

/* Coupon badge in payment status card */
.coupon-badge {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 4px;
  box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.product-coupon {
  min-width: 120px;
}

/* Buttons */
.btn {
  border-radius: 8px;
  font-weight: 600;
  padding: 12px 20px;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.btn-outline-secondary {
  background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
  color: white;
  border: none;
}

/* Cancellation Details Card Styling */
.cancellation-detail-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 8px 32px rgba(220, 53, 69, 0.15);
  margin-bottom: 30px;
  border: 2px solid #dc3545;
  overflow: hidden;
}

.cancellation-detail-card .card-header {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  padding: 20px 25px;
  border: none;
  display: flex;
  align-items: center;
  gap: 12px;
}

.cancellation-detail-card .card-header h6 {
  margin: 0;
  font-weight: 600;
  font-size: 16px;
}

.cancellation-detail-card .card-body {
  padding: 25px;
  background: #fff5f5;
}

.cancellation-reason h6 {
  color: #dc3545;
  font-weight: 600;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.reason-content {
  background: white;
  border: 1px solid #ffebee;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
}

.reason-content p {
  margin: 0;
  color: #333;
  line-height: 1.6;
  font-size: 14px;
}

.cancellation-info {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
}

.cancellation-info .info-item {
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid #ffebee;
}

.cancellation-info .info-item:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.cancellation-info .info-item span {
  display: block;
  color: #dc3545;
  font-weight: 600;
  font-size: 12px;
  margin-bottom: 5px;
}

.cancellation-info .info-item div {
  color: #333;
  font-size: 14px;
  font-weight: 500;
}

/* Return/Exchange Status Info Card Styling */
.return-status-info-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  margin-bottom: 30px;
  border: 2px solid #ffc107;
  overflow: hidden;
}

.return-status-info-card .card-header {
  background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
  color: white;
  padding: 20px 25px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.return-status-info-card .card-header h6 {
  margin: 0;
  font-weight: 600;
  font-size: 16px;
  flex: 1;
}

.return-status-info-card .card-body {
  padding: 25px;
  background: #f8f9fa;
}

.return-info .info-item {
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: flex-start;
  gap: 10px;
}

.return-info .info-item:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.return-info .info-item i {
  margin-top: 2px;
  font-size: 14px;
  min-width: 16px;
}

.return-info .info-item span {
  flex: 1;
  line-height: 1.5;
}

.return-status-section {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.return-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.return-actions .btn {
  flex: 1;
  min-width: 120px;
}

.admin-note .alert {
  margin: 0;
  border-radius: 8px;
}

.admin-note h6 {
  margin: 0 0 10px 0;
  font-size: 14px;
  font-weight: 600;
}

.admin-note p {
  margin: 0;
  font-size: 13px;
  line-height: 1.4;
}

/* Responsive Design */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 20px;
    text-align: center;
  }
  
  .header-actions {
    width: 100%;
    justify-content: center;
  }
  
  .btn {
    width: 100%;
    margin-bottom: 10px;
  }
  
  .status-flow-mini {
    flex-direction: column;
    gap: 10px;
  }
  
  .products-table {
    font-size: 12px;
  }
  
  .product-thumbnail, .no-image {
    width: 40px;
    height: 40px;
  }
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.wrapper-content > * {
  animation: fadeInUp 0.6s ease forwards;
}

.wrapper-content > *:nth-child(1) { animation-delay: 0.1s; }
.wrapper-content > *:nth-child(2) { animation-delay: 0.2s; }
.wrapper-content > *:nth-child(3) { animation-delay: 0.3s; }
.wrapper-content > *:nth-child(4) { animation-delay: 0.4s; }
</style>

@push('scripts')
{{-- Realtime is handled by layout script --}}
@endpush
@endsection
