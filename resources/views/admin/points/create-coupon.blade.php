@extends('layouts.admin')

@section('title', 'Tạo Mã Giảm Giá Mới')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-plus text-success"></i> Tạo Mã Giảm Giá Mới</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.points.coupons') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.points.store-coupon') }}" method="POST" class="form-horizontal">
                        @csrf
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Mã giảm giá <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" 
                                       placeholder="Nhập mã giảm giá (VD: SALE2024)" required>
                                <small class="text-muted">Mã giảm giá phải là duy nhất</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tên mã giảm giá <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" 
                                       placeholder="Nhập tên mã giảm giá" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Mô tả <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="Mô tả chi tiết về mã giảm giá" required>{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Loại giảm giá <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select name="discount_type" class="form-control" required>
                                    <option value="">Chọn loại giảm giá</option>
                                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                                        Giảm theo phần trăm (%)
                                    </option>
                                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
                                        Giảm theo số tiền cố định
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Giá trị giảm <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value') }}" 
                                       placeholder="Nhập giá trị giảm" step="0.01" min="0" required>
                                <small class="text-muted">
                                    Nếu chọn phần trăm: nhập số từ 1-100. Nếu chọn số tiền: nhập số tiền VND
                                </small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Giá trị đơn hàng tối thiểu</label>
                            <div class="col-sm-10">
                                <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value', 0) }}" 
                                       placeholder="Nhập giá trị đơn hàng tối thiểu" step="1000" min="0">
                                <small class="text-muted">Để trống hoặc 0 nếu không có giới hạn</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Giới hạn sử dụng</label>
                            <div class="col-sm-10">
                                <input type="number" name="max_usage" class="form-control" value="{{ old('max_usage') }}" 
                                       placeholder="Nhập số lần sử dụng tối đa" min="1">
                                <small class="text-muted">Để trống nếu không giới hạn</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Trạng thái</label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        Kích hoạt mã giảm giá
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Tạo mã giảm giá
                                </button>
                                <a href="{{ route('admin.points.coupons') }}" class="btn btn-default">
                                    <i class="fa fa-times"></i> Hủy
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 