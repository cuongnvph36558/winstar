@extends('layouts.admin')

@section('title', 'Sửa Voucher')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-edit text-warning"></i> Sửa Voucher</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.points.vouchers') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.points.update-voucher', $voucher) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên Voucher <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name', $voucher->name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points_required">Điểm Cần Thiết <span class="text-danger">*</span></label>
                                    <input type="number" name="points_required" id="points_required"
                                           class="form-control" value="{{ old('points_required', $voucher->points_required) }}"
                                           min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô Tả <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control"
                                      rows="3" required>{{ old('description', $voucher->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type">Loại Giảm Giá <span class="text-danger">*</span></label>
                                    <select name="discount_type" id="discount_type" class="form-control" required>
                                        <option value="">Chọn loại giảm giá</option>
                                        <option value="percentage" {{ old('discount_type', $voucher->discount_type) === 'percentage' ? 'selected' : '' }}>
                                            Phần trăm (%)
                                        </option>
                                        <option value="fixed" {{ old('discount_type', $voucher->discount_type) === 'fixed' ? 'selected' : '' }}>
                                            Số tiền cố định (VND)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_value">Giá Trị Giảm Giá <span class="text-danger">*</span></label>
                                    <input type="number" name="discount_value" id="discount_value"
                                           class="form-control" value="{{ old('discount_value', $voucher->discount_value) }}"
                                           min="1" step="0.01" required>
                                    <small class="form-text text-muted">
                                        Nếu chọn phần trăm, nhập số từ 1-100. Nếu chọn số tiền, nhập số tiền VND.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_order_value">Đơn Hàng Tối Thiểu (VND)</label>
                                    <input type="number" name="min_order_value" id="min_order_value"
                                           class="form-control" value="{{ old('min_order_value', $voucher->min_order_value) }}"
                                           min="0" step="1000">
                                    <small class="form-text text-muted">
                                        Để trống hoặc 0 nếu không giới hạn
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_usage">Số Lượng Tối Đa</label>
                                    <input type="number" name="max_usage" id="max_usage"
                                           class="form-control" value="{{ old('max_usage', $voucher->max_usage) }}"
                                           min="1">
                                    <small class="form-text text-muted">
                                        Để trống nếu không giới hạn số lượng sử dụng
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date"
                                           class="form-control" value="{{ old('start_date', $voucher->start_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Ngày Kết Thúc <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date"
                                           class="form-control" value="{{ old('end_date', $voucher->end_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                                    Kích hoạt voucher
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-save"></i> Cập Nhật Voucher
                            </button>
                            <a href="{{ route('admin.points.vouchers') }}" class="btn btn-default">
                                <i class="fa fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
