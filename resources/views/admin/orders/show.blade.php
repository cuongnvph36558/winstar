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
                    @case('momo')
                      <span class="payment-badge momo">Ví MoMo</span>
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
                      'processing' => ['label' => 'Đang xử lý', 'color' => '#17a2b8', 'icon' => 'cogs'],
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
                      'completed' => ['label' => 'Hoàn thành', 'color' => '#28a745', 'icon' => 'check-circle'],
                      'cancelled' => ['label' => 'Đã hủy', 'color' => '#dc3545', 'icon' => 'times-circle'],
                    ];
                    $orderStatus = $orderStatusVN[$order->status] ?? ['label' => ucfirst($order->status), 'color' => '#6c757d', 'icon' => 'info-circle'];
                  @endphp
                  <div class="status-badge" style="background: {{ $orderStatus['color'] }}20; color: {{ $orderStatus['color'] }}; border-color: {{ $orderStatus['color'] }};">
                    <i class="fa fa-{{ $orderStatus['icon'] }}"></i>
                    {{ $orderStatus['label'] }}
                  </div>
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
                </tr>
              </thead>
              <tbody>
                @foreach($order->details as $i => $detail)
                <tr class="product-row">
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
                    <strong>{{ $detail->product_name ?? ($detail->product->name ?? 'SP#'.$detail->product_id) }}</strong>
                    @if($detail->product && $detail->product->category)
                      <br><small class="text-muted">{{ $detail->product->category->name }}</small>
                    @endif
                  </td>
                  <td class="product-variant">
                    @if($detail->variant)
                      <span class="variant-badge">{{ $detail->variant->variant_name }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="product-storage">
                    @if($detail->variant && $detail->variant->storage && $detail->variant->storage->name)
                      <span class="storage-badge">{{ $detail->variant->storage->name }}</span>
                    @elseif($detail->variant && $detail->variant->capacity)
                      <span class="storage-badge">{{ $detail->variant->capacity }}</span>
                    @elseif($detail->variant && $detail->variant->variant_name && strpos(strtolower($detail->variant->variant_name), 'gb') !== false)
                      @php
                        preg_match('/(\d+)\s*gb/i', $detail->variant->variant_name, $matches);
                        $capacity = $matches[1] ?? '';
                      @endphp
                      @if($capacity)
                        <span class="storage-badge">{{ $capacity }}GB</span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    @elseif($detail->variant && $detail->variant->storage_id)
                      @php
                        // Try to get storage name from database
                        $storage = \App\Models\Storage::find($detail->variant->storage_id);
                      @endphp
                      @if($storage && $storage->name)
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
                    <span class="shipping-fee">30.000₫</span>
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
                      
                      $shippingFee = 30000; // Phí ship cố định
                      $discountPerItem = 0;
                      
                      // Tính toán giảm giá cho từng sản phẩm
                      if ($order->discount_amount > 0 && $order->orderDetails->count() > 0) {
                        $discountPerItem = $order->discount_amount / $order->orderDetails->count();
                      }
                      
                      $totalPricePerItem = $basePrice + $shippingFee - $discountPerItem;
                    @endphp
                    @if($basePrice)
                      <span class="total">{{ number_format($totalPricePerItem, 0, ',', '.') }}₫</span>
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

.payment-badge.momo {
  background: #e83e8c;
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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
// Initialize Pusher for admin order detail
try {
    window.pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
        wsHost: '{{ config("broadcasting.connections.pusher.options.host") }}',
        wsPort: {{ config("broadcasting.connections.pusher.options.port") }},
        forceTLS: {{ config("broadcasting.connections.pusher.options.useTLS") ? 'true' : 'false' }},
        disableStats: true,
        enabledTransports: ['ws', 'wss']
    });
    
    console.log('✅ Admin Order Detail Pusher initialized');
} catch (error) {
    console.error('❌ Failed to initialize Admin Order Detail Pusher:', error);
}

// Listen for order status updates
if (window.pusher) {
    const ordersChannel = window.pusher.subscribe('orders');
    ordersChannel.bind('OrderStatusUpdated', function(e) {
        console.log('📡 Admin Order Detail received OrderStatusUpdated event:', e);
        
        // Only update if this is the current order
        if (e.order_id == {{ $order->id }}) {
            // Update order status badge
            const orderStatusBadge = document.querySelector('.status-badge');
            if (orderStatusBadge) {
                const statusConfig = {
                    'pending': { label: 'Chờ xử lý', color: '#ffc107', icon: 'clock-o' },
                    'processing': { label: 'Đang chuẩn bị hàng', color: '#17a2b8', icon: 'cogs' },
                    'shipping': { label: 'Đang giao hàng', color: '#007bff', icon: 'truck' },
                    'completed': { label: 'Hoàn thành', color: '#28a745', icon: 'check-circle' },
                    'cancelled': { label: 'Đã hủy', color: '#dc3545', icon: 'times-circle' }
                };
                
                const newStatus = statusConfig[e.new_status] || { label: e.new_status, color: '#6c757d', icon: 'info-circle' };
                
                orderStatusBadge.innerHTML = `<i class="fa fa-${newStatus.icon}"></i> ${newStatus.label}`;
                orderStatusBadge.style.background = newStatus.color + '20';
                orderStatusBadge.style.color = newStatus.color;
                orderStatusBadge.style.borderColor = newStatus.color;
                
                // Show success notification
                if (e.old_status === 'shipping' && e.new_status === 'completed') {
                    // Create notification
                    const notification = document.createElement('div');
                    notification.className = 'alert alert-success alert-dismissible fade show';
                    notification.style.position = 'fixed';
                    notification.style.top = '20px';
                    notification.style.right = '20px';
                    notification.style.zIndex = '9999';
                    notification.style.minWidth = '300px';
                    notification.innerHTML = `
                        <i class="fa fa-check-circle"></i>
                        <strong>Thành công!</strong> Khách hàng đã xác nhận đã nhận hàng cho đơn hàng #${e.order_code}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 5000);
                }
                
                console.log('✅ Order status updated in admin detail page');
            }
        }
    });
}
</script>
@endpush
@endsection