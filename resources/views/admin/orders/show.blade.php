@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('content')
<div class="order-header-box mb-4 p-3 px-4 d-flex flex-wrap justify-content-between align-items-center shadow-sm bg-white rounded-3">
  <div class="d-flex flex-column">
    <h2 class="mb-1 fw-bold" style="font-size:2rem;">Chi tiết đơn hàng</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent px-0 py-1 mb-0" style="font-size:1rem;">
        <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
      </ol>
    </nav>
  </div>
  <div class="d-flex gap-2 mt-3 mt-md-0">
    <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-lg"><i class="fa fa-arrow-left me-1"></i> Quay lại</a>
    <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-primary btn-lg"><i class="fa fa-edit me-1"></i> Sửa đơn hàng</a>
  </div>
</div>

<div class="container py-4">
  <div class="row g-4">
    <!-- Thông tin đơn hàng -->
    <div class="col-lg-5 col-md-6">
      <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pb-2">
          <h5 class="mb-0"><i class="fa fa-info-circle text-primary me-2"></i>Thông tin đơn hàng <span class="text-muted">#{{ $order->id }}</span></h5>
        </div>
        <div class="card-body pt-2">
          <dl class="row mb-0">
            <dt class="col-5 text-muted">Khách hàng:</dt>
            <dd class="col-7 mb-2"><strong>{{ $order->user->name ?? 'ID: '.$order->user_id }}</strong></dd>
            <dt class="col-5 text-muted">Người nhận:</dt>
            <dd class="col-7 mb-2">{{ $order->receiver_name }}</dd>
            <dt class="col-5 text-muted">SĐT:</dt>
            <dd class="col-7 mb-2">{{ $order->phone }}</dd>
            <dt class="col-5 text-muted">Địa chỉ:</dt>
            <dd class="col-7 mb-2">{{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</dd>
            <dt class="col-5 text-muted">Mô tả:</dt>
            <dd class="col-7 mb-2">{{ $order->description }}</dd>
            <dt class="col-5 text-muted">Mã giảm giá:</dt>
            <dd class="col-7 mb-2">{{ $order->coupon_id }}</dd>
            <dt class="col-5 text-muted">Phương thức thanh toán:</dt>
            <dd class="col-7 mb-2">{{ $order->payment_method }}</dd>
            <dt class="col-5 text-muted">Trạng thái thanh toán:</dt>
            <dd class="col-7 mb-2">
              @php
                $paymentStatusVN = [
                  'pending' => 'Chờ thanh toán',
                  'paid' => 'Đã thanh toán',
                  'processing' => 'Đang xử lý',
                  'completed' => 'Hoàn thành',
                  'failed' => 'Thất bại',
                  'refunded' => 'Hoàn tiền',
                  'cancelled' => 'Đã hủy',
                ];
              @endphp
              <span class="badge @if($order->payment_status=='paid'||$order->payment_status=='completed') bg-success @elseif($order->payment_status=='pending') bg-warning text-dark @elseif($order->payment_status=='failed'||$order->payment_status=='cancelled') bg-danger @else bg-secondary @endif" style="font-size:13px;">
                <i class="fa @if($order->payment_status=='paid'||$order->payment_status=='completed') fa-check-circle @elseif($order->payment_status=='pending') fa-clock-o @elseif($order->payment_status=='failed'||$order->payment_status=='cancelled') fa-times-circle @else fa-info-circle @endif me-1"></i>
                {{ $paymentStatusVN[$order->payment_status] ?? ucfirst($order->payment_status) }}
              </span>
            </dd>
            <dt class="col-5 text-muted">Trạng thái đơn:</dt>
            <dd class="col-7 mb-2">
              @php
                $orderStatusVN = [
                  'pending' => 'Chờ xử lý',
                  'processing' => 'Đang chuẩn bị hàng',
                  'shipping' => 'Đang giao hàng',
                  'completed' => 'Hoàn thành',
                  'cancelled' => 'Đã hủy',
                ];
              @endphp
              <span class="badge @if($order->status=='completed') bg-success @elseif($order->status=='pending') bg-warning text-dark @elseif($order->status=='cancelled') bg-danger @elseif($order->status=='shipping') bg-primary @else bg-info text-dark @endif" style="font-size:13px;">
                <i class="fa @if($order->status=='completed') fa-check-circle @elseif($order->status=='pending') fa-clock-o @elseif($order->status=='cancelled') fa-times-circle @elseif($order->status=='shipping') fa-truck @else fa-info-circle @endif me-1"></i>
                {{ $orderStatusVN[$order->status] ?? ucfirst($order->status) }}
              </span>
            </dd>
            <dt class="col-5 text-muted">Tổng tiền:</dt>
            <dd class="col-7 mb-2"><strong style="font-size:16px">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></dd>
            <dt class="col-5 text-muted">Ngày đặt:</dt>
            <dd class="col-7">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
          </dl>
        </div>
      </div>
    </div>
    <!-- Chi tiết sản phẩm -->
    <div class="col-lg-7 col-md-6">
      <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pb-2">
          <h5 class="mb-0"><i class="fa fa-list-ul text-success me-2"></i>Chi tiết sản phẩm</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Ảnh</th>
                  <th>Sản phẩm</th>
                  <th>Biến thể</th>
                  <th>Dung lượng</th>
                  <th class="text-center">SL</th>
                  <th class="text-end">Đơn giá</th>
                  <th class="text-end">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->details as $i => $detail)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>
                    @if($detail->product && $detail->product->image)
                      <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product_name }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                    @else
                      <span class="text-muted">Không có ảnh</span>
                    @endif
                  </td>
                  <td>{{ $detail->product_name ?? ($detail->product->name ?? 'SP#'.$detail->product_id) }}</td>
                  <td>{{ $detail->variant->variant_name ?? '-' }}</td>
                  <td>
                    @if($detail->variant && $detail->variant->storage)
                      {{ $detail->variant->storage->name }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">{{ $detail->quantity }}</td>
                  <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }}₫</td>
                  <td class="text-end">{{ number_format($detail->total, 0, ',', '.') }}₫</td>
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
.card { border-radius: 14px; }
.card-header { border-radius: 14px 14px 0 0; background: #f8f9fa; }
.badge { padding: 6px 12px; border-radius: 8px; font-weight: 500; }
dl, dt, dd { margin-bottom: 0; }
.order-header-box {
  background: #f8fafc;
  border-radius: 18px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.04);
  border: 1px solid #e9ecef;
}
@media (max-width: 767px) {
  .order-header-box { flex-direction: column; align-items: flex-start !important; }
  .order-header-box .d-flex.gap-2 { width: 100%; justify-content: flex-start; margin-top: 1rem; }
}
@media (max-width: 991px) {
  .col-lg-5, .col-lg-7 { flex: 0 0 100%; max-width: 100%; }
}
</style>
@endsection