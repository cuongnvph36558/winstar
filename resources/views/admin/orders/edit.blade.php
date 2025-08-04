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
                    <span><strong>Thanh toán:</strong> 
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
                    <i class="fa fa-calendar text-warning"></i>
                    <span><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</span>
                  </div>
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
                'completed' => ['label' => 'Hoàn thành', 'color' => '#28a745', 'bg_color' => '#d4edda', 'icon' => 'check-circle'],
                'cancelled' => ['label' => 'Đã hủy', 'color' => '#dc3545', 'bg_color' => '#f8d7da', 'icon' => 'times-circle']
              ];
              $currentStatus = $order->status;
              $currentStep = array_search($currentStatus, array_keys($statusFlow)) + 1;
            @endphp

            <!-- Current Status Display -->
            <div class="current-status-display">
              <h4 class="status-title"><i class="fa fa-info-circle text-primary"></i> Trạng thái hiện tại:</h4>
              <div class="status-badge order-detail-status" style="background: {{ $statusFlow[$currentStatus]['bg_color'] }}; color: {{ $statusFlow[$currentStatus]['color'] }}; border-color: {{ $statusFlow[$currentStatus]['color'] }};">
                <i class="fa fa-{{ $statusFlow[$currentStatus]['icon'] }} fa-3x"></i>
                <div class="status-text">
                  <h3 class="current-status">{{ $statusFlow[$currentStatus]['label'] }}</h3>
                  <p>Trạng thái hiện tại của đơn hàng</p>
                </div>
              </div>
            </div>

            <!-- Status Progress -->
            <div class="status-progress">
              <div class="progress-container">
                @php
                  $progressPercent = ($currentStep / count($statusFlow)) * 100;
                  if ($currentStatus === 'cancelled') $progressPercent = 100;
                @endphp
                <div class="progress-bar-bg">
                  <div class="progress-bar-fill" style="width: {{ $progressPercent }}%; background: {{ $statusFlow[$currentStatus]['color'] }};"></div>
                </div>
                <div class="progress-text">
                  <span>Bước {{ $currentStep }} / {{ count($statusFlow) }}</span>
                  <span class="progress-percent">{{ round($progressPercent) }}%</span>
                </div>
              </div>
            </div>

            <form method="POST" action="{{ route('admin.order.update', $order->id) }}" class="form-horizontal" id="statusUpdateForm">
              @csrf
              @method('PUT')
              
              <!-- Next Status Selection -->
              <div class="status-selection">
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
                          // Có thể hủy từ bất kỳ trạng thái nào trừ khi đã hoàn thành
                          $canSelect = ($currentStatus !== 'completed');
                        } else {
                          // Chỉ có thể chuyển đến trạng thái tiếp theo
                          $canSelect = ($statusIndex === $currentStep + 1);
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

              <!-- Payment Status (only for non-COD orders) -->
              @if(strtolower($order->payment_method) !== 'cod')
              <div class="payment-status-section">
                <label class="selection-label">
                  <i class="fa fa-credit-card"></i> Trạng thái thanh toán:
                </label>
                <select name="payment_status" class="form-control payment-select">
                  <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                  <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                  <option value="processing" {{ $order->payment_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                  <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                  <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                  <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                  <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
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
                <button type="submit" class="btn btn-primary btn-lg" id="updateBtn" onclick="console.log('Button clicked');">
                  <i class="fa fa-check"></i> Cập nhật trạng thái
                </button>
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

.payment-badge.momo {
  background: #e83e8c;
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

.status-select, .payment-select {
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

.status-select option, .payment-select option {
  color: #495057 !important;
  background-color: white !important;
  padding: 8px 12px !important;
  font-size: 14px !important;
  font-weight: 500 !important;
}

.status-select:focus, .payment-select:focus {
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

/* Payment Status Section */
.payment-status-section {
  padding: 20px 30px;
  background: #f8f9fa;
  border-top: 1px solid #dee2e6;
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
</style>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Status description mapping
  const statusDescriptions = {
    'pending': 'Đơn hàng đã được đặt và đang chờ xử lý. Nhân viên sẽ kiểm tra và xác nhận đơn hàng.',
    'processing': 'Đơn hàng đang được chuẩn bị. Sản phẩm đang được đóng gói và chuẩn bị giao hàng.',
    'shipping': 'Đơn hàng đang được giao đến địa chỉ của khách hàng. Vui lòng theo dõi trạng thái giao hàng.',
    'completed': 'Đơn hàng đã được giao thành công đến khách hàng. Quá trình mua hàng đã hoàn tất.',
    'cancelled': 'Đơn hàng đã bị hủy. Sản phẩm sẽ được hoàn lại kho và hoàn tiền nếu cần thiết.'
  };

  // Update status description when selection changes
  $('#statusSelect').change(function() {
    const selectedStatus = $(this).val();
    const description = statusDescriptions[selectedStatus];
    
    if (description) {
      $('#statusDescriptionText').text(description);
      $('#statusDescription').fadeIn(300);
    } else {
      $('#statusDescription').fadeOut(300);
    }
  });

  // Show initial description
  $('#statusSelect').trigger('change');

  // Ensure dropdown text is visible
  $('#statusSelect, .payment-select').on('change', function() {
    const selectedText = $(this).find('option:selected').text();
    if (selectedText && selectedText.trim() !== '') {
      $(this).css('color', '#495057');
    }
  });

  // Force text visibility on page load
  setTimeout(function() {
    $('#statusSelect, .payment-select').each(function() {
      const selectedText = $(this).find('option:selected').text();
      if (selectedText && selectedText.trim() !== '') {
        $(this).css('color', '#495057');
      }
    });
  }, 100);

  // Form submission confirmation
  $('#statusUpdateForm').submit(function(e) {
    const currentStatus = '{{ $order->status }}';
    const newStatus = $('#statusSelect').val();
    
    console.log('Current status:', currentStatus);
    console.log('New status:', newStatus);
    
    if (currentStatus === newStatus) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Thông báo',
        text: 'Trạng thái đã được chọn giống với trạng thái hiện tại!',
        confirmButtonColor: '#007bff'
      });
      return false;
    }
    
    if (newStatus === 'cancelled') {
      e.preventDefault();
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
          $('#updateBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang cập nhật...');
          $('#statusUpdateForm').off('submit').submit();
        }
      });
      return false;
    }
    
    // Disable button to prevent double submission
    $('#updateBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang cập nhật...');
  });

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