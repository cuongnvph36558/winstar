@extends('layouts.admin')

@section('title', 'Chuyển đổi trạng thái đơn hàng')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><i class="fa fa-exchange text-primary"></i> Chuyển đổi trạng thái đơn hàng</h2>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
      <li class="active"><strong>Chuyển đổi trạng thái</strong></li>
    </ol>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-10 col-lg-offset-1">
      <div class="ibox float-e-margins">
        <div class="ibox-title" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
          <h5 style="color: white; margin: 0;">
            <i class="fa fa-exchange"></i> 
            Đơn hàng: <span class="font-bold">{{ $order->code_order ?? '#' . $order->id }}</span>
          </h5>
        </div>
        <div class="ibox-content" style="background: #fafbfc;">
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade in">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
          @endif

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade in">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <i class="fa fa-check-circle"></i> <strong>Thành công!</strong> {{ session('success') }}
            </div>
          @endif

          <!-- Order Summary Cards -->
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="info-card customer-card">
                <div class="card-header">
                  <i class="fa fa-user-circle"></i>
                  <h6>Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                  <div class="info-item">
                    <i class="fa fa-user text-primary"></i>
                    <span><strong>Người nhận:</strong> {{ $order->receiver_name }}</span>
                  </div>
                  <div class="info-item">
                    <i class="fa fa-phone text-success"></i>
                    <span><strong>Số điện thoại:</strong> {{ $order->phone }}</span>
                  </div>
                  <div class="info-item">
                    <i class="fa fa-map-marker text-danger"></i>
                    <span><strong>Địa chỉ:</strong> {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-card order-card">
                <div class="card-header">
                  <i class="fa fa-shopping-cart"></i>
                  <h6>Thông tin đơn hàng</h6>
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
                     <i class="fa fa-check-circle text-success"></i>
                     <span><strong>Trạng thái thanh toán:</strong> 
                       <span class="current-payment-status current-payment-status-badge payment-status-badge">
                         @php
                           // Xác định trạng thái thanh toán mặc định cho VNPay
                           $paymentStatus = $order->payment_status;
                           if ($order->payment_method === 'vnpay' && empty($paymentStatus)) {
                             // Nếu là VNPay và chưa có payment_status, mặc định là pending
                             $paymentStatus = 'pending';
                           }
                         @endphp
                         @switch($paymentStatus)
                           @case('pending')
                             <span class="badge badge-warning current-payment-status-text">Chờ thanh toán</span>
                             @break
                           @case('paid')
                             <span class="badge badge-success current-payment-status-text">Đã thanh toán</span>
                             @break
                           @case('processing')
                             <span class="badge badge-info current-payment-status-text">Đang xử lý</span>
                             @break
                           @case('completed')
                             <span class="badge badge-success current-payment-status-text">Hoàn thành</span>
                             @break
                           @case('failed')
                             <span class="badge badge-danger current-payment-status-text">Thất bại</span>
                             @break
                           @case('refunded')
                             <span class="badge badge-secondary current-payment-status-text">Hoàn tiền</span>
                             @break
                           @case('cancelled')
                             <span class="badge badge-danger current-payment-status-text">Đã hủy</span>
                             @break
                           @default
                             @if($order->payment_method === 'vnpay')
                               <span class="badge badge-warning current-payment-status-text">Chờ thanh toán</span>
                             @else
                               <span class="badge badge-secondary current-payment-status-text">{{ $paymentStatus ?? 'Chưa xác định' }}</span>
                             @endif
                         @endswitch
                       </span>
                     </span>
                   </div>
                   
                   <!-- VNPay Payment Information -->
                   @if($order->payment_method === 'vnpay')
                     <div class="info-item">
                       <i class="fa fa-credit-card text-info"></i>
                       <span><strong>Thông tin thanh toán VNPay:</strong></span>
                     </div>
                     <div class="info-item" style="margin-left: 20px;">
                       <i class="fa fa-clock-o text-warning"></i>
                       <span><strong>Trạng thái giao dịch:</strong> 
                         @if($order->payment_status === 'paid')
                           <span class="badge badge-success">Giao dịch thành công</span>
                         @elseif($order->payment_status === 'pending')
                           <span class="badge badge-warning">Chờ thanh toán</span>
                         @elseif($order->payment_status === 'failed')
                           <span class="badge badge-danger">Giao dịch thất bại</span>
                         @else
                           <span class="badge badge-secondary">Chưa xác định</span>
                         @endif
                       </span>
                     </div>
                     @if($order->vnpay_transaction_id)
                       <div class="info-item" style="margin-left: 20px;">
                         <i class="fa fa-hashtag text-primary"></i>
                         <span><strong>Mã giao dịch VNPay:</strong> {{ $order->vnpay_transaction_id }}</span>
                       </div>
                     @endif
                     @if($order->vnpay_bank_code)
                       <div class="info-item" style="margin-left: 20px;">
                         <i class="fa fa-university text-success"></i>
                         <span><strong>Ngân hàng:</strong> {{ $order->vnpay_bank_code }}</span>
                       </div>
                     @endif
                   @endif
                   
                   <div class="info-item">
                    <i class="fa fa-calendar text-warning"></i>
                    <span><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</span>
                  </div>
                  @if($order->status === 'cancelled' && $order->cancellation_reason)
                    <div class="info-item">
                      <i class="fa fa-times-circle text-danger"></i>
                      <span><strong>Lý do hủy:</strong> {{ $order->cancellation_reason }}</span>
                    </div>
                    <div class="info-item">
                      <i class="fa fa-clock-o text-danger"></i>
                      <span><strong>Hủy lúc:</strong> {{ $order->cancelled_at ? \Carbon\Carbon::parse($order->cancelled_at)->format('d/m/Y H:i:s') : 'N/A' }}</span>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <!-- Status Change Section -->
          <div class="status-change-section">
            <div class="section-header">
              <h4><i class="fa fa-cogs"></i> Chuyển đổi trạng thái đơn hàng</h4>
            </div>
            
            @php
                      $statusFlow = [
          'pending' => ['label' => 'Chờ xử lý', 'color' => '#ffc107', 'bg_color' => '#fff3cd', 'icon' => 'clock-o'],
          'processing' => ['label' => 'Đang chuẩn bị hàng', 'color' => '#17a2b8', 'bg_color' => '#d1ecf1', 'icon' => 'cogs'],
          'shipping' => ['label' => 'Đang giao hàng', 'color' => '#007bff', 'bg_color' => '#cce7ff', 'icon' => 'truck'],
          'delivered' => ['label' => 'Đã giao hàng', 'color' => '#fd7e14', 'bg_color' => '#ffe5d0', 'icon' => 'check-square-o'],
          'received' => ['label' => 'Đã nhận hàng', 'color' => '#6f42c1', 'bg_color' => '#e2d9f3', 'icon' => 'handshake-o'],
          'completed' => ['label' => 'Hoàn thành', 'color' => '#28a745', 'bg_color' => '#d4edda', 'icon' => 'check-circle'],
          'cancelled' => ['label' => 'Đã hủy', 'color' => '#dc3545', 'bg_color' => '#f8d7da', 'icon' => 'times-circle']
        ];
              $currentStatus = $order->status;
              $currentStep = array_search($currentStatus, array_keys($statusFlow)) + 1;
            @endphp

            <!-- Current Status Display -->
            <div class="current-status-display">
              <h4 class="status-title"><i class="fa fa-info-circle text-primary"></i> Trạng thái hiện tại:</h4>
              <div class="status-badge order-detail-status current-status-badge" style="background: {{ $statusFlow[$currentStatus]['bg_color'] }}; color: {{ $statusFlow[$currentStatus]['color'] }}; border-color: {{ $statusFlow[$currentStatus]['color'] }};">
                <i class="fa fa-{{ $statusFlow[$currentStatus]['icon'] }} fa-3x"></i>
                <div class="status-text">
                  <h3 class="current-status current-status-text">{{ $statusFlow[$currentStatus]['label'] }}</h3>
                  <p>
                    @if($order->status === 'received')
                      Khách hàng đã xác nhận nhận hàng
                    @elseif($order->status === 'completed')
                      Đơn hàng đã hoàn thành
                    @else
                      Trạng thái hiện tại của đơn hàng
                    @endif
                  </p>
                </div>
              </div>
            </div>

            <!-- Status Progress -->
            <div class="status-progress">
              <div class="progress-container">
                @php
                  $progressPercent = ($currentStep / count($statusFlow)) * 100;
                  $totalSteps = count($statusFlow);
                  
                  // Điều chỉnh progress cho các trạng thái đặc biệt
                  if ($currentStatus === 'cancelled') {
                    $progressPercent = 100;
                  } elseif ($currentStatus === 'completed') {
                    $progressPercent = 100;
                  }
                @endphp
                <div class="progress-bar-bg">
                  <div class="progress-bar-fill" style="width: {{ $progressPercent }}%; background: {{ $statusFlow[$currentStatus]['color'] }};"></div>
                </div>
                <div class="progress-text">
                  <span>Bước {{ $currentStep }} / {{ $totalSteps }}</span>
                  <span class="progress-percent">{{ round($progressPercent) }}%</span>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ route('admin.order.update', $order->id) }}" class="form-horizontal" id="statusUpdateForm">
              @csrf
              @method('PUT')
              
              <!-- Next Status Selection -->
              <div class="status-selection">

                
                                 @if($order->status === 'received')
                   <div class="alert alert-info alert-dismissible fade in">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <i class="fa fa-info-circle"></i> 
                     <strong>Thông báo:</strong> Đơn hàng đã được khách hàng xác nhận nhận hàng. Admin không thể cập nhật trạng thái nữa - chỉ khách hàng mới có quyền xác nhận nhận hàng.
                   </div>
                 @endif
                 
                 @if($order->status === 'delivered')
                   <div class="alert alert-info alert-dismissible fade in">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <i class="fa fa-info-circle"></i> 
                     <strong>Đã giao hàng:</strong> Đơn hàng đã được giao thành công. Admin không thể hủy đơn hàng nữa.
                     @if(!$order->is_received)
                       <br><strong>Hệ thống sẽ tự động chuyển sang "Đã nhận hàng" sau 1 ngày</strong> nếu khách hàng không xác nhận.
                       <br><small class="text-muted">Thời gian giao: {{ $order->updated_at->format('d/m/Y H:i:s') }} ({{ $order->updated_at->diffForHumans() }})</small>
                     @else
                       <br>Khách hàng đã xác nhận nhận hàng.
                     @endif
                   </div>
                 @endif
                

                
                @if($order->status === 'completed')
                  <div class="alert alert-success alert-dismissible fade in">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa fa-check-circle"></i> 
                    <strong>Hoàn thành:</strong> Đơn hàng đã hoàn thành. Admin không thể thay đổi trạng thái nữa.
                  </div>
                @endif
                <label class="selection-label">
                  <i class="fa fa-arrow-right"></i> Chuyển đến trạng thái:
                </label>
                <div class="selection-controls">
                  <select name="status" class="form-control status-select" id="statusSelect">
                    @php
                      $hasValidOptions = false;
                    @endphp
                    
                    @foreach($statusFlow as $status => $info)
                      @php
                        $statusIndex = array_search($status, array_keys($statusFlow)) + 1;
                        $canSelect = false;
                        
                                                 if ($status === 'cancelled') {
                           // Có thể hủy từ bất kỳ trạng thái nào trừ khi đã giao hàng, nhận hàng hoặc hoàn thành
                           $canSelect = !in_array($currentStatus, ['delivered', 'received', 'completed']);
                         } else {
                          // Admin không thể chuyển đến trạng thái 'completed' - chỉ người dùng mới có thể
                          if ($status === 'completed') {
                            $canSelect = false; // Admin không thể set trạng thái 'completed'
                          } else if ($status === 'received') {
                            $canSelect = false; // Admin không thể set trạng thái 'received'
                          } else {
                            // Admin chỉ có thể chuyển đến trạng thái tiếp theo
                            $canSelect = ($statusIndex === $currentStep + 1);
                          }
                        }
                        
                        if ($canSelect) {
                          $hasValidOptions = true;
                        }
                      @endphp
                      
                      @if($canSelect)
                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                          {{ $info['label'] }}
                        </option>
                      @endif
                    @endforeach
                    
                    <!-- Fallback option if no valid statuses are available -->
                    @if(!$hasValidOptions)
                      <option value="{{ $currentStatus }}" selected>
                        {{ $statusFlow[$currentStatus]['label'] }} (Hiện tại - Không thể chuyển đổi)
                      </option>
                    @endif
                    

                    

                  </select>
                  
                  <!-- Status Description -->
                  <div class="status-description" id="statusDescription">
                    <i class="fa fa-info-circle"></i>
                    <span id="statusDescriptionText"></span>
                  </div>
                </div>
              </div>

              

              <!-- Cancellation Reason Section -->
              <div class="cancellation-reason-section" id="cancellationReasonSection" style="display: none;">
                <div class="cancellation-reason-card">
                  <div class="card-header">
                    <h5><i class="fa fa-exclamation-triangle text-danger"></i> Lý do hủy đơn hàng</h5>
                    <p class="card-subtitle">Vui lòng nhập lý do hủy đơn hàng để thông báo cho khách hàng</p>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="cancellation_reason" class="form-label">
                        <i class="fa fa-edit text-primary"></i> Lý do hủy: <span class="text-danger">*</span>
                      </label>
                      <textarea 
                          id="cancellation_reason"
                          name="cancellation_reason" 
                          class="form-control cancellation-textarea" 
                          rows="5" 
                          placeholder="Ví dụ: Khách hàng yêu cầu hủy đơn hàng do thay đổi ý định mua hàng. Hoặc: Sản phẩm không còn hàng trong kho. Hoặc: Địa chỉ giao hàng không chính xác..."
                          minlength="10"
                          maxlength="500"
                      >{{ $order->cancellation_reason ?? '' }}</textarea>
                      <div class="form-text">
                        <i class="fa fa-info-circle text-info"></i>
                        <span id="charCount">0</span>/500 ký tự (tối thiểu 10 ký tự)
                      </div>
                      <div class="invalid-feedback">
                        <i class="fa fa-exclamation-circle"></i>
                        Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự)
                      </div>
                    </div>
                    
                    <div class="cancellation-examples">
                      <h6><i class="fa fa-lightbulb-o text-warning"></i> Gợi ý lý do hủy:</h6>
                      <div class="example-list">
                        <div class="example-item">
                          <i class="fa fa-times-circle text-danger"></i>
                          <span>Khách hàng yêu cầu hủy đơn hàng</span>
                        </div>
                        <div class="example-item">
                          <i class="fa fa-times-circle text-danger"></i>
                          <span>Sản phẩm không còn hàng trong kho</span>
                        </div>
                        <div class="example-item">
                          <i class="fa fa-times-circle text-danger"></i>
                          <span>Địa chỉ giao hàng không chính xác</span>
                        </div>
                        <div class="example-item">
                          <i class="fa fa-times-circle text-danger"></i>
                          <span>Không thể liên lạc với khách hàng</span>
                        </div>
                        <div class="example-item">
                          <i class="fa fa-times-circle text-danger"></i>
                          <span>Lý do khác (vui lòng mô tả chi tiết)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- VNPay Payment Status Display Section (Compact) -->
              @if($order->payment_method === 'vnpay')
                <div class="payment-status-section">
                  <div class="payment-status-compact">
                    <div class="compact-header">
                      <i class="fa fa-credit-card text-info"></i>
                      <span class="compact-title">Trạng thái VNPay:</span>
                      @php
                        $paymentStatus = $order->payment_status;
                        if ($order->payment_method === 'vnpay' && empty($paymentStatus)) {
                          $paymentStatus = 'pending';
                        }
                      @endphp
                      @switch($paymentStatus)
                        @case('pending')
                          <span class="badge badge-warning payment-status-badge">Chờ thanh toán</span>
                          @break
                        @case('paid')
                          <span class="badge badge-success payment-status-badge">Đã thanh toán</span>
                          @break
                        @case('failed')
                          <span class="badge badge-danger payment-status-badge">Thất bại</span>
                          @break
                        @case('refunded')
                          <span class="badge badge-secondary payment-status-badge">Hoàn tiền</span>
                          @break
                        @default
                          <span class="badge badge-secondary payment-status-badge">{{ $paymentStatus ?? 'Chưa xác định' }}</span>
                      @endswitch
                    </div>
                    
                    <!-- Hidden form fields to preserve data -->
                    <input type="hidden" name="payment_status" value="{{ $order->payment_status ?? 'pending' }}">
                  </div>
                </div>
              @endif

              <!-- Action Buttons -->
              <div class="action-buttons">
                <a href="{{ route('admin.order.index') }}" class="btn btn-default btn-lg">
                  <i class="fa fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-info btn-lg">
                  <i class="fa fa-eye"></i> Xem chi tiết
                </a>
                @if($order->status === 'received')
                  <button type="submit" class="btn btn-default btn-lg" id="updateBtn" disabled>
                    <i class="fa fa-lock"></i> Không thể cập nhật
                  </button>
                @else
                  <button type="submit" class="btn btn-primary btn-lg" id="updateBtn" onclick="// console.log removed">
                    <i class="fa fa-check"></i> Cập nhật trạng thái
                  </button>
                @endif
                
                <!-- Cancel Order Button -->
                @if(!in_array($order->status, ['delivered', 'received', 'completed']))
                  <button type="button" class="btn btn-danger btn-lg" id="cancelOrderBtn" onclick="showCancelOrderForm();">
                    <i class="fa fa-times"></i> Hủy đơn hàng
                  </button>
                @endif
                

                

                

              </div>
            </form>
          </div>

          <!-- Status Flow Information -->
          <div class="status-flow-section">
            <div class="section-header">
              <h4><i class="fa fa-info-circle"></i> Quy trình trạng thái đơn hàng</h4>
            </div>
            <div class="status-flow">
              @foreach($statusFlow as $status => $info)
                @php
                  $statusIndex = array_search($status, array_keys($statusFlow)) + 1;
                  $isCurrent = ($currentStatus === $status);
                  $isCompleted = array_search($currentStatus, array_keys($statusFlow)) >= array_search($status, array_keys($statusFlow));
                  $isCancelled = ($currentStatus === 'cancelled');
                @endphp
                <div class="status-step {{ $isCurrent ? 'current' : ($isCompleted ? 'completed' : 'pending') }} {{ $isCancelled && $status === 'cancelled' ? 'cancelled' : '' }}">
                  <div class="step-icon" style="color: {{ $statusFlow[$status]['color'] }};">
                    <i class="fa fa-{{ $info['icon'] }} fa-2x"></i>
                  </div>
                  <div class="step-label">
                    <strong>{{ $info['label'] }}</strong>
                  </div>
                  @if($isCurrent)
                    <div class="current-indicator">
                      <span class="current-badge">Hiện tại</span>
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Overall Layout */
.ibox-content {
  padding: 30px;
  border-radius: 8px;
}

/* Info Cards */
.info-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  margin-bottom: 20px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 20px;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  align-items: center;
  gap: 10px;
}

.card-header i {
  font-size: 20px;
  color: #007bff;
}

.card-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
}

.card-body {
  padding: 20px;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 15px;
  padding: 10px;
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
}

.info-item span {
  flex: 1;
  color: #495057;
}

.amount {
  font-size: 18px;
  font-weight: bold;
  color: #28a745;
}

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

/* Status Change Section */
.status-change-section {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  margin-bottom: 30px;
  overflow: hidden;
}

.section-header {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  padding: 20px 30px;
  border-bottom: none;
}

.section-header h4 {
  margin: 0;
  font-weight: 600;
}

.section-header i {
  margin-right: 10px;
}

/* Current Status Display */
.current-status-display {
  padding: 30px;
  text-align: center;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 20px;
  padding: 30px 40px;
  border-radius: 15px;
  border: 3px solid;
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
  transition: transform 0.3s ease;
}

.status-badge:hover {
  transform: scale(1.02);
}

.status-text h3 {
  margin: 0 0 5px 0;
  font-weight: 700;
  font-size: 24px;
}

.status-text p {
  margin: 0;
  opacity: 0.8;
  font-size: 14px;
}

/* Status Progress */
.status-progress {
  padding: 0 30px 30px;
}

.progress-container {
  position: relative;
}

.progress-bar-bg {
  height: 12px;
  background: #e9ecef;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 10px;
}

.progress-bar-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 0.8s ease;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
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

/* Status Selection */
.status-selection {
  padding: 30px;
  background: #f8f9fa;
  border-top: 1px solid #dee2e6;
}

.selection-label {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 15px;
  font-size: 16px;
}

.selection-label i {
  color: #007bff;
  margin-right: 8px;
}

.selection-controls {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

 .status-select {
   border: 2px solid #dee2e6;
   border-radius: 8px;
   padding: 12px 15px;
   font-size: 16px;
   transition: all 0.3s ease;
   box-shadow: 0 2px 8px rgba(0,0,0,0.05);
   color: #495057 !important;
   background-color: white !important;
   font-weight: 500 !important;
   min-height: 50px;
 }

 .status-select option {
   color: #495057 !important;
   background-color: white !important;
   padding: 8px 12px !important;
   font-size: 14px !important;
   font-weight: 500 !important;
 }

 .status-select:focus {
   border-color: #007bff !important;
   box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25), 0 4px 15px rgba(0,0,0,0.1) !important;
   transform: translateY(-2px) !important;
   color: #495057 !important;
   outline: none !important;
 }

.status-description {
  background: #e3f2fd;
  border: 1px solid #bbdefb;
  border-radius: 8px;
  padding: 15px;
  color: #1976d2;
  font-size: 14px;
  display: none;
}

.status-description i {
  margin-right: 8px;
}



/* Cancellation Reason Section */
.cancellation-reason-section {
  margin-top: 20px;
}

/* Payment Status Section */
.payment-status-section {
  margin-top: 15px;
}

.payment-status-compact {
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 12px 15px;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.payment-status-compact:hover {
  background: #e9ecef;
  border-color: #17a2b8;
}

.compact-header {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
}

.compact-header i {
  font-size: 16px;
  color: #17a2b8;
}

.compact-title {
  font-weight: 600;
  color: #495057;
  font-size: 14px;
}

.payment-status-badge {
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 15px;
  font-weight: 600;
  margin-left: auto;
}

.cancellation-reason-card {
  background: #fff;
  border: 2px solid #dc3545;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.1);
  transition: all 0.3s ease;
  animation: fadeIn 0.3s ease;
}

.cancellation-reason-card:hover {
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.15);
  transform: translateY(-2px);
}

.cancellation-reason-card .card-header {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  padding: 20px 25px;
  border-bottom: none;
}

.cancellation-reason-card .card-header h5 {
  margin: 0 0 5px 0;
  font-size: 18px;
  font-weight: 600;
}

.cancellation-reason-card .card-subtitle {
  margin: 0;
  opacity: 0.9;
  font-size: 14px;
}

.cancellation-reason-card .card-body {
  padding: 25px;
}

.cancellation-textarea {
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s ease;
  resize: vertical;
}

.cancellation-textarea:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.cancellation-textarea.is-invalid {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.cancellation-examples {
  margin-top: 20px;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid #ffc107;
}

.cancellation-examples h6 {
  margin: 0 0 15px 0;
  color: #495057;
  font-weight: 600;
}

.example-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.example-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 12px;
  background: white;
  border-radius: 6px;
  border: 1px solid #e9ecef;
  transition: all 0.2s ease;
  cursor: pointer;
}

.example-item:hover {
  background: #fff3cd;
  border-color: #ffc107;
  transform: translateX(5px);
}

.example-item i {
  font-size: 12px;
  flex-shrink: 0;
}

.example-item span {
  font-size: 13px;
  color: #495057;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 8px;
}

.form-text {
  margin-top: 8px;
  font-size: 13px;
}

.invalid-feedback {
  display: block;
  margin-top: 5px;
  font-size: 13px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Action Buttons */
.action-buttons {
  padding: 30px;
  background: white;
  border-top: 1px solid #dee2e6;
  display: flex;
  gap: 15px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  border-radius: 8px;
  font-weight: 600;
  padding: 12px 25px;
  transition: all 0.3s ease;
  border: none;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.btn-info {
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.btn-default {
  background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
  color: white;
}

.btn-danger {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-danger:hover {
  background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
  transform: translateY(-1px);
}

/* Status Flow Section */
.status-flow-section {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  overflow: hidden;
}

.status-flow {
  padding: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.status-step {
  flex: 1;
  min-width: 120px;
  text-align: center;
  padding: 20px 15px;
  border-radius: 12px;
  transition: all 0.3s ease;
  position: relative;
}

.status-step.current {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: 2px solid #007bff;
  transform: scale(1.05);
}

.status-step.completed {
  background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
  border: 2px solid #28a745;
}

.status-step.pending {
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  opacity: 0.6;
}

.status-step.cancelled {
  background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
  border: 2px solid #dc3545;
}

.step-icon {
  margin-bottom: 10px;
}

.step-label {
  font-size: 12px;
  line-height: 1.3;
}

.current-indicator {
  margin-top: 10px;
}

.current-badge {
  background: #007bff;
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
  .status-flow {
    flex-direction: column;
    gap: 15px;
  }
  
  .status-step {
    min-width: auto;
    width: 100%;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
  }
  
  .status-badge {
    flex-direction: column;
    gap: 15px;
  }
  
  .info-item {
    flex-direction: column;
    text-align: center;
    gap: 8px;
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

.ibox-content > * {
  animation: fadeInUp 0.6s ease forwards;
}

.ibox-content > *:nth-child(1) { animation-delay: 0.1s; }
.ibox-content > *:nth-child(2) { animation-delay: 0.2s; }
.ibox-content > *:nth-child(3) { animation-delay: 0.3s; }
.ibox-content > *:nth-child(4) { animation-delay: 0.4s; }

/* Status Badge Styles for AJAX Updates */
.status-badge.status-pending {
  background: #fff3cd !important;
  color: #856404 !important;
  border-color: #ffeaa7 !important;
}

.status-badge.status-processing {
  background: #d1ecf1 !important;
  color: #0c5460 !important;
  border-color: #bee5eb !important;
}

.status-badge.status-shipping {
  background: #cce7ff !important;
  color: #004085 !important;
  border-color: #b3d7ff !important;
}

.status-badge.status-delivered {
  background: #ffe5d0 !important;
  color: #a0522d !important;
  border-color: #ffd4a3 !important;
}

.status-badge.status-received {
  background: #e2d9f3 !important;
  color: #4a148c !important;
  border-color: #d1c4e9 !important;
}

.status-badge.status-completed {
  background: #d4edda !important;
  color: #155724 !important;
  border-color: #c3e6cb !important;
}

.status-badge.status-cancelled {
  background: #f8d7da !important;
  color: #721c24 !important;
  border-color: #f5c6cb !important;
}

/* Payment Status Badge Styles */
.payment-status-badge .badge {
  font-size: 0.8rem;
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: 500;
}

.badge-warning {
  background-color: #ffc107;
  color: #212529;
}

.badge-success {
  background-color: #28a745;
  color: white;
}

.badge-info {
  background-color: #17a2b8;
  color: white;
}

.badge-danger {
  background-color: #dc3545;
  color: white;
}

.badge-secondary {
  background-color: #6c757d;
  color: white;
}

/* Animation for status updates */
.status-badge, .payment-status-badge {
  transition: all 0.3s ease;
}

.status-badge.updated {
  animation: pulse 0.6s ease-in-out;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}
</style>

@endsection

<script>
// Global functions - defined before document ready
function showCancelOrderForm() {
  // Check if form element exists
  const form = document.getElementById('cancellationReasonSection');
  
  if (!form) {
    console.error('Form element not found!');
    return;
  }
  
  // Set dropdown to cancelled
  const dropdown = document.getElementById('statusSelect');
  if (dropdown) {
    dropdown.value = 'cancelled';
  }
  
  // Show the form directly first
  form.style.display = 'block';
  
  // Trigger change event to update UI
  $('#statusSelect').trigger('change');
  
  // Focus on textarea
  setTimeout(() => {
    const textarea = document.getElementById('cancellation_reason');
    if (textarea) {
      textarea.focus();
    }
  }, 200);
  
  // Show info message
  Swal.fire({
    icon: 'info',
    title: 'Hủy đơn hàng',
    text: 'Vui lòng nhập lý do hủy đơn hàng bên dưới trước khi cập nhật trạng thái.',
    confirmButtonColor: '#007bff',
    timer: 4000,
    showConfirmButton: false
  });
}


</script>

@push('scripts')

$(document).ready(function() {
  // Get current status from PHP
  let currentStatus = '{{ $order->status }}';
  
  console.log('🎯 Page loaded with order status:', currentStatus);
  console.log('🎯 Order payment method:', '{{ $order->payment_method }}');
  console.log('🎯 Order payment status:', '{{ $order->payment_status }}');
  
  // Status description mapping
  const statusDescriptions = {
    'pending': 'Đơn hàng đã được đặt và đang chờ xử lý. Nhân viên sẽ kiểm tra và xác nhận đơn hàng.',
    'processing': 'Đơn hàng đang được chuẩn bị. Sản phẩm đang được đóng gói và chuẩn bị giao hàng.',
    'shipping': 'Đơn hàng đang được giao đến địa chỉ của khách hàng. Khách hàng sẽ xác nhận khi nhận được hàng.',
    'received': 'Đơn hàng đã được khách hàng xác nhận nhận hàng. Chỉ khách hàng mới có thể hoàn thành đơn hàng.',
    'completed': 'Đơn hàng đã hoàn thành. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.',
    'cancelled': 'Đơn hàng đã bị hủy. Sản phẩm sẽ được hoàn lại kho và hoàn tiền nếu cần thiết.'
  };

  // console.log removed
  
     // When status is delivered, received, or completed, disable all updates
   if (['delivered', 'received', 'completed'].includes(currentStatus)) {
     // Disable the entire form
     $('#statusSelect').prop('disabled', true);
     $('#updateBtn').prop('disabled', true);
     
     // Hide cancellation reason section to avoid validation issues
     $('#cancellationReasonSection').hide();
   }
  

  

  

  


  // Update status description when selection changes
  $('#statusSelect').change(function() {
    const selectedStatus = $(this).val();
    // console.log removed
    
    const description = statusDescriptions[selectedStatus];
    
    if (description) {
      $('#statusDescriptionText').text(description);
      $('#statusDescription').fadeIn(300);
    } else {
      $('#statusDescription').fadeOut(300);
    }
    
    // Show/hide cancellation reason section
    if (selectedStatus === 'cancelled') {
      // console.log removed
      
      // Use simple JavaScript to show the form
      document.getElementById('cancellationReasonSection').style.display = 'block';
      
      // Focus on textarea
      setTimeout(() => {
        const textarea = document.getElementById('cancellation_reason');
        if (textarea) {
          textarea.focus();
        }
      }, 100);
      
      // Show info message for cancellation
      Swal.fire({
        icon: 'info',
        title: 'Hủy đơn hàng',
        text: 'Vui lòng nhập lý do hủy đơn hàng bên dưới trước khi cập nhật trạng thái.',
        confirmButtonColor: '#007bff',
        timer: 4000,
        showConfirmButton: false
      });
    } else {
      // console.log removed
      document.getElementById('cancellationReasonSection').style.display = 'none';
    }
  });

  // Show initial description
  $('#statusSelect').trigger('change');
  

  
  // Character count for cancellation reason
  $('textarea[name="cancellation_reason"]').on('input', function() {
    const count = $(this).val().length;
    $('#charCount').text(count);
    
    if (count < 10) {
      $(this).addClass('is-invalid');
    } else {
      $(this).removeClass('is-invalid');
    }
  });
  
  // Initialize character count
  $('textarea[name="cancellation_reason"]').trigger('input');
  
  // Handle example clicks for cancellation reason
  $('.example-item').click(function() {
    const exampleText = $(this).find('span').text();
    const textarea = $('#cancellation_reason');
    
    // Ensure the cancellation section is visible
    $('#cancellationReasonSection').show();
    
    // Add example text to textarea
    textarea.val(exampleText);
    textarea.trigger('input');
    
    // Focus on textarea after ensuring it's visible
    setTimeout(() => {
      if (textarea.is(':visible')) {
        textarea.focus();
      }
    }, 100);
    
    // Show success message
    Swal.fire({
      icon: 'success',
      title: 'Đã thêm gợi ý',
      text: 'Bạn có thể chỉnh sửa lý do hủy theo ý muốn',
      timer: 1500,
      showConfirmButton: false
    });
  });
  
  // Function to safely focus on cancellation textarea
  function focusCancellationTextarea() {
    const textarea = $('#cancellation_reason');
    if (textarea.length && textarea.is(':visible')) {
      textarea.focus();
      return true;
    }
    return false;
  }
  


     // Ensure dropdown text is visible
   $('#statusSelect').on('change', function() {
     const selectedText = $(this).find('option:selected').text();
     if (selectedText && selectedText.trim() !== '') {
       $(this).css('color', '#495057');
     }
   });

   // Force text visibility on page load
   setTimeout(function() {
     $('#statusSelect').each(function() {
       const selectedText = $(this).find('option:selected').text();
       if (selectedText && selectedText.trim() !== '') {
         $(this).css('color', '#495057');
       }
     });
   }, 100);



  // Form submission confirmation with AJAX
  $('#statusUpdateForm').submit(function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const newStatus = $('#statusSelect').val();
    const form = $(this);
    const updateBtn = $('#updateBtn');
    
    console.log('🎯 Form submitted:', {
      currentStatus: currentStatus,
      newStatus: newStatus,
      formAction: form.attr('action')
    });
    
    // Prevent form submission if status is delivered, received, or completed
    if (['delivered', 'received', 'completed'].includes(currentStatus)) {
      console.log('🎯 Status update blocked - order already delivered/received/completed');
      Swal.fire({
        icon: 'info',
        title: 'Không thể cập nhật',
        text: 'Đơn hàng đã được giao hàng, xác nhận nhận hàng hoặc đã hoàn thành. Admin không thể cập nhật trạng thái nữa.',
        confirmButtonColor: '#007bff'
      });
      return false;
    }
    
    if (currentStatus === newStatus) {
      console.log('🎯 Status update blocked - same status selected');
      Swal.fire({
        icon: 'warning',
        title: 'Thông báo',
        text: 'Trạng thái đã được chọn giống với trạng thái hiện tại!',
        confirmButtonColor: '#007bff'
      });
      return false;
    }
    
    console.log('🎯 Status update allowed, proceeding...');

    if (newStatus === 'cancelled') {
      // Check if cancellation reason is provided
      const cancellationReason = document.querySelector('textarea[name="cancellation_reason"]').value.trim();
      
      if (!cancellationReason || cancellationReason.length < 10) {
        // Ensure the cancellation section is visible
        document.getElementById('cancellationReasonSection').style.display = 'block';
        
        // Focus on textarea
        setTimeout(() => {
          const textarea = document.getElementById('cancellation_reason');
          if (textarea) {
            textarea.focus();
          }
        }, 100);
        
        Swal.fire({
          icon: 'error',
          title: 'Lý do hủy đơn hàng',
          text: 'Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự) để thông báo cho khách hàng!',
          confirmButtonColor: '#dc3545'
        });
        
        return false;
      }
      
      // Show confirmation dialog for cancellation
      Swal.fire({
        icon: 'warning',
        title: 'Xác nhận hủy đơn hàng',
        text: 'Bạn có chắc chắn muốn hủy đơn hàng này? Hành động này không thể hoàn tác.',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Có, hủy đơn hàng',
        cancelButtonText: 'Không, giữ nguyên'
      }).then((result) => {
        if (result.isConfirmed) {
          submitFormAjax(form, updateBtn);
        }
      });
      return false;
    }
    
    // For other status changes, show confirmation
    Swal.fire({
      icon: 'question',
      title: 'Xác nhận cập nhật',
      text: `Bạn có chắc chắn muốn chuyển đơn hàng sang trạng thái "${$('#statusSelect option:selected').text()}"?`,
      showCancelButton: true,
      confirmButtonColor: '#007bff',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Có, cập nhật',
      cancelButtonText: 'Không, hủy bỏ'
    }).then((result) => {
      if (result.isConfirmed) {
        submitFormAjax(form, updateBtn);
      }
    });
  });
  
  // Function to submit form via AJAX
  function submitFormAjax(form, updateBtn) {
    console.log('🎯 Starting AJAX submission...');
    
    // Disable button to prevent double submission
    updateBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang cập nhật...');
    
    // Get form data
    const formData = new FormData(form[0]);
    
    // Add AJAX header
    formData.append('_ajax', '1');
    
    console.log('🎯 Form data prepared, sending AJAX request...');
    console.log('🎯 Form action URL:', form.attr('action'));
    console.log('🎯 CSRF token:', $('meta[name="csrf-token"]').attr('content'));
    
    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log('🎯 AJAX success response:', response);
        console.log('🎯 Response type:', typeof response);
        
        // Check if response is a string (HTML error page)
        if (typeof response === 'string') {
          console.log('🎯 Response is HTML string, likely an error page');
          Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Server trả về trang HTML thay vì JSON. Có thể có lỗi server.',
            confirmButtonColor: '#dc3545'
          });
          return;
        }
        
        if (response.success) {
          // Show success message
          Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: response.message,
            confirmButtonColor: '#28a745'
          });
          
          // Update current status
          currentStatus = response.order.status;
          console.log('🎯 Updated currentStatus to:', currentStatus);
          
          // Update status display
          updateStatusDisplay(response.order);
          
          // Update status steps
          updateStatusSteps(response.order.status);
          
          // Update status select
          $('#statusSelect').val(response.order.status);
          
          // Update status description
          updateStatusDescription(response.order.status);
          
        } else {
          console.log('🎯 AJAX success but response.success is false');
          Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: response.message || 'Có lỗi xảy ra khi cập nhật trạng thái.',
            confirmButtonColor: '#dc3545'
          });
        }
      },
      error: function(xhr, status, error) {
        console.log('🎯 AJAX error details:', {
          status: status,
          error: error,
          xhr: xhr
        });
        console.log('🎯 Response text:', xhr.responseText);
        
        let errorMessage = 'Có lỗi xảy ra khi cập nhật trạng thái.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseText) {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.message) {
              errorMessage = response.message;
            }
          } catch (e) {
            console.log('🎯 Failed to parse response as JSON:', e);
            // If not JSON, try to extract error from HTML
            const match = xhr.responseText.match(/<div[^>]*class="[^"]*alert[^"]*"[^>]*>([^<]*)<\/div>/);
            if (match) {
              errorMessage = match[1].trim();
            } else {
              // Show first 200 characters of response for debugging
              errorMessage = 'Server error: ' + xhr.responseText.substring(0, 200);
            }
          }
        }
        
        Swal.fire({
          icon: 'error',
          title: 'Lỗi!',
          text: errorMessage,
          confirmButtonColor: '#dc3545'
        });
      },
      complete: function() {
        console.log('🎯 AJAX request completed');
        // Re-enable button
        updateBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Cập nhật trạng thái');
      }
    });
  }
  
  // Function to update status display
  function updateStatusDisplay(order) {
    console.log('🎯 Updating status display with order data:', order);
    
    // Update status badge with animation
    const statusBadge = $('.current-status-badge');
    console.log('🎯 Found status badge:', statusBadge.length);
    
    statusBadge.removeClass().addClass(`status-badge status-${order.status}`);
    statusBadge.find('.current-status-text').text(order.status_text);
    
    // Add animation
    statusBadge.addClass('updated');
    setTimeout(() => {
      statusBadge.removeClass('updated');
    }, 600);
    
    // Update payment status if available
    if (order.payment_status) {
      console.log('🎯 Updating payment status to:', order.payment_status);
      
      const paymentStatusMap = {
        'pending': '<span class="badge badge-warning current-payment-status-text">Chờ thanh toán</span>',
        'paid': '<span class="badge badge-success current-payment-status-text">Đã thanh toán</span>',
        'processing': '<span class="badge badge-info current-payment-status-text">Đang xử lý</span>',
        'completed': '<span class="badge badge-success current-payment-status-text">Hoàn thành</span>',
        'failed': '<span class="badge badge-danger current-payment-status-text">Thất bại</span>',
        'refunded': '<span class="badge badge-secondary current-payment-status-text">Hoàn tiền</span>',
        'cancelled': '<span class="badge badge-danger current-payment-status-text">Đã hủy</span>'
      };
      
      const paymentStatusHtml = paymentStatusMap[order.payment_status] || 
                               `<span class="badge badge-secondary current-payment-status-text">${order.payment_status}</span>`;
      
      const paymentStatusElement = $('.current-payment-status-badge');
      console.log('🎯 Found payment status element:', paymentStatusElement.length);
      console.log('🎯 Payment status HTML:', paymentStatusHtml);
      
      paymentStatusElement.html(paymentStatusHtml);
      
      // Add animation to payment status
      paymentStatusElement.addClass('updated');
      setTimeout(() => {
        paymentStatusElement.removeClass('updated');
      }, 600);
    }
  }
  
  // Function to update status steps
  function updateStatusSteps(newStatus) {
    // Remove current class from all steps
    $('.status-step').removeClass('current completed');
    
    // Add appropriate classes based on new status
    const statusFlow = ['pending', 'processing', 'shipping', 'delivered', 'received', 'completed'];
    const newStatusIndex = statusFlow.indexOf(newStatus);
    
    if (newStatusIndex >= 0) {
      // Mark completed steps
      for (let i = 0; i < newStatusIndex; i++) {
        $(`.status-step[data-status="${statusFlow[i]}"]`).addClass('completed');
      }
      
      // Mark current step
      $(`.status-step[data-status="${newStatus}"]`).addClass('current');
    }
  }

  // Add hover effects to status steps
  $('.status-step').hover(
    function() {
      $(this).css('transform', 'scale(1.05)');
    },
    function() {
      if (!$(this).hasClass('current')) {
        $(this).css('transform', 'scale(1)');
      }
    }
  );
});
</script>
@endpush
