@extends('layouts.admin')
@section('title', 'Chỉnh sửa mã giảm giá')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh sửa mã giảm giá</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a></li>
            <li class="active"><strong>Chỉnh sửa</strong></li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.coupon.show', $coupon->id) }}" class="btn btn-info">
                <i class="fa fa-eye"></i> Xem chi tiết
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-edit"></i> Cập nhật thông tin mã giảm giá</h5>
                    <div class="ibox-tools">
                        <span class="label label-primary">{{ $coupon->code }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="fa fa-exclamation-triangle"></i> Có lỗi xảy ra!</h4>
                            <ul class="m-b-none">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.coupon.update', $coupon->id) }}" class="form-horizontal" id="coupon-form">
                        @csrf
                        @method('PUT')

                        <!-- Thông tin cơ bản -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-primary"><i class="fa fa-info-circle"></i> Thông tin cơ bản</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mã giảm giá <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" readonly>
                                        <span class="help-block m-b-none">Mã giảm giá không thể thay đổi</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tên mã giảm giá</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $coupon->name) }}" placeholder="Nhập tên mã giảm giá">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mô tả</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả chi tiết về mã giảm giá">{{ old('description', $coupon->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">VIP Level</label>
                                    <div class="col-sm-8">
                                        <select name="vip_level" class="form-control">
                                            <option value="">Tất cả</option>
                                            <option value="Bronze" {{ old('vip_level', $coupon->vip_level) == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                                            <option value="Silver" {{ old('vip_level', $coupon->vip_level) == 'Silver' ? 'selected' : '' }}>Silver</option>
                                            <option value="Gold" {{ old('vip_level', $coupon->vip_level) == 'Gold' ? 'selected' : '' }}>Gold</option>
                                            <option value="Platinum" {{ old('vip_level', $coupon->vip_level) == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                            <option value="Diamond" {{ old('vip_level', $coupon->vip_level) == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="text-success"><i class="fa fa-percent"></i> Thông tin giảm giá</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Loại giảm giá <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="discount_type" class="form-control" id="discount_type">
                                            <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                            <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giá trị giảm <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" placeholder="Nhập giá trị giảm">
                                            <span class="input-group-addon" id="discount_unit">{{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Đơn hàng tối thiểu</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="min_order_value" class="form-control" value="{{ old('min_order_value', $coupon->min_order_value) }}" placeholder="0">
                                            <span class="input-group-addon">₫</span>
                                        </div>
                                        <span class="help-block m-b-none">Để trống nếu không có yêu cầu</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giảm giá tối đa</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', $coupon->max_discount_value) }}" placeholder="0">
                                            <span class="input-group-addon">₫</span>
                                        </div>
                                        <span class="help-block m-b-none">Để trống nếu không giới hạn</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Thời gian và giới hạn -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-warning"><i class="fa fa-calendar"></i> Thời gian hiệu lực</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $coupon->start_date->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $coupon->end_date->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Số ngày hiệu lực</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="validity_days" class="form-control" value="{{ old('validity_days', $coupon->validity_days) }}" placeholder="30">
                                        <span class="help-block m-b-none">Số ngày hiệu lực sau khi người dùng đổi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="text-info"><i class="fa fa-users"></i> Giới hạn sử dụng</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giới hạn toàn hệ thống</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="1000">
                                        <span class="help-block m-b-none">Để trống nếu không giới hạn</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giới hạn/người dùng</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" placeholder="1">
                                        <span class="help-block m-b-none">Để trống nếu không giới hạn</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Điểm đổi</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="0" name="exchange_points" class="form-control" value="{{ old('exchange_points', $coupon->exchange_points) }}" placeholder="0">
                                        <span class="help-block m-b-none">Số điểm cần để đổi mã giảm giá</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Trạng thái <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status', $coupon->status) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                                            <option value="0" {{ old('status', $coupon->status) == 0 ? 'selected' : '' }}>Tạm ngừng</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Nút hành động -->
                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <a class="btn btn-default" href="{{ route('admin.coupon.index') }}">
                                    <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <a class="btn btn-info" href="{{ route('admin.coupon.show', $coupon->id) }}">
                                    <i class="fa fa-eye"></i> Xem chi tiết
                                </a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-save"></i> Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountType = document.getElementById('discount_type');
    const discountUnit = document.getElementById('discount_unit');
    
    discountType.addEventListener('change', function() {
        if (this.value === 'percentage') {
            discountUnit.textContent = '%';
        } else {
            discountUnit.textContent = '₫';
        }
    });
});
</script>
@endsection
  
