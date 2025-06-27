@extends('layouts.admin')

@section('title', 'Tạo đơn hàng')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>Tạo đơn hàng mới</h2>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.order.index') }}">Đơn hàng</a></li>
      <li class="active"><strong>Tạo mới</strong></li>
    </ol>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Thông tin đơn hàng</h5>
        </div>
        <div class="ibox-content">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.order.store') }}" class="form-horizontal">
            @csrf
            <div class="form-group">
              <label class="col-sm-2 control-label">Tên người nhận <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="receiver_name" class="form-control" value="{{ old('receiver_name') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Số điện thoại</label>
              <div class="col-sm-10">
                <input type="text" name="receiver_phone" class="form-control" value="{{ old('receiver_phone') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Địa chỉ</label>
              <div class="col-sm-10">
                <textarea name="address" class="form-control">{{ old('address') }}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Địa chỉ giao hàng</label>
              <div class="col-sm-10">
                <textarea name="shipping_address" class="form-control">{{ old('shipping_address') }}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Phương thức thanh toán</label>
              <div class="col-sm-10">
                <select name="payment_method" class="form-control">
                  <option value="cod">Thanh toán khi nhận hàng</option>
                  <option value="bank">Chuyển khoản</option>
                  <option value="paypal">PayPal</option>
                </select>
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <h4>Chi tiết sản phẩm</h4>
            <div id="product-list">
              <div class="form-group">
                <label class="col-sm-2 control-label">Sản phẩm</label>
                <div class="col-sm-5">
                  <select name="items[0][variant_id]" class="form-control">
                    @foreach($variants as $variant)
                      <option value="{{ $variant->id }}">{{ $variant->variant_name }} - {{ $variant->price }} VNĐ</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-3">
                  <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-10 col-sm-offset-2">
                <button type="button" class="btn btn-secondary" onclick="addProductRow()">+ Thêm sản phẩm</button>
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <a class="btn btn-white" href="{{ route('admin.order.index') }}">Huỷ</a>
                <button class="btn btn-primary" type="submit">Lưu đơn hàng</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let index = 1;
  function addProductRow() {
    const container = document.getElementById('product-list');
    const group = document.createElement('div');
    group.className = 'form-group';
    group.innerHTML = `
      <label class=\"col-sm-2 control-label\">Sản phẩm</label>
      <div class=\"col-sm-5\">
        <select name=\"items[${index}][variant_id]\" class=\"form-control\">
          @foreach($variants as $variant)
            <option value=\"{{ $variant->id }}\">{{ $variant->variant_name }} - {{ $variant->price }} VNĐ</option>
          @endforeach
        </select>
      </div>
      <div class=\"col-sm-3\">
        <input type=\"number\" name=\"items[${index}][quantity]\" class=\"form-control\" value=\"1\" min=\"1\">
      </div>
    `;
    container.appendChild(group);
    index++;
  }
</script>
@endsection
