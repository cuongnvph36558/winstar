@extends('layouts.admin')

@section('title', 'Thêm mã giảm giá')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>Thêm mã giảm giá</h2>
    <ol class="breadcrumb">
      <li>
        <a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a>
      </li>
      <li class="active">
        <strong>Thêm mới</strong>
      </li>
    </ol>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Thêm mã giảm giá</h5>
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

          @if(session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          @endif

          <form method="POST" action="{{ route('admin.coupon.store') }}" class="form-horizontal">
            @csrf
            <div class="form-group">
              <label class="col-sm-2 control-label">Mã <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Nhập mã giảm giá">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Loại giảm giá</label>
              <div class="col-sm-10">
                <select name="discount_type" class="form-control">
                  <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                  <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Cố định</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Giá trị giảm</label>
              <div class="col-sm-10">
                <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Ngày bắt đầu</label>
              <div class="col-sm-10">
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Ngày kết thúc</label>
              <div class="col-sm-10">
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Đơn hàng tối thiểu</label>
              <div class="col-sm-10">
                <input type="number" step="0.01" name="min_order_value" class="form-control" value="{{ old('min_order_value') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Giá trị giảm tối đa</label>
              <div class="col-sm-10">
                <input type="number" step="0.01" name="max_discount_value" class="form-control" value="{{ old('max_discount_value') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Số lần sử dụng</label>
              <div class="col-sm-10">
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Số lần/người</label>
              <div class="col-sm-10">
                <input type="number" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user') }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Trạng thái</label>
              <div class="col-sm-10">
                <select name="status" class="form-control">
                  <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                  <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm ngừng</option>
                </select>
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <a class="btn btn-white" href="{{ route('admin.coupon.index') }}">Hủy</a>
                <button class="btn btn-primary" type="submit">Lưu</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
