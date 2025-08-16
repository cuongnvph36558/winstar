@extends('layouts.admin')

@section('title', 'Tạo mã giảm giá mới')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Tạo mã giảm giá mới</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a></li>
            <li class="active"><strong>Tạo mới</strong></li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-default">
                <i class="fa fa-list"></i> Danh sách
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-plus"></i> Thông tin mã giảm giá mới</h5>
                    <div class="ibox-tools">
                        <span class="label label-info">Tạo mới</span>
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.coupon.store') }}" class="form-horizontal" id="coupon-form">
                        @csrf

                        <!-- Thông tin cơ bản -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-primary"><i class="fa fa-info-circle"></i> Thông tin cơ bản</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mã giảm giá <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: SALE2024, GIAM50K" required>
                                        <span class="help-block m-b-none">Mã duy nhất để khách hàng sử dụng</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tên mã giảm giá</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="VD: Mã giảm giá mùa hè">
                                        <span class="help-block m-b-none">Tên hiển thị cho mã giảm giá</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mô tả</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả chi tiết về mã giảm giá">{{ old('description') }}</textarea>
                                        <span class="help-block m-b-none">Mô tả chi tiết về điều kiện sử dụng</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">VIP Level</label>
                                    <div class="col-sm-8">
                                        <select name="vip_level" class="form-control">
                                            <option value="">Tất cả khách hàng</option>
                                            <option value="Bronze" {{ old('vip_level') == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                                            <option value="Silver" {{ old('vip_level') == 'Silver' ? 'selected' : '' }}>Silver</option>
                                            <option value="Gold" {{ old('vip_level') == 'Gold' ? 'selected' : '' }}>Gold</option>
                                            <option value="Platinum" {{ old('vip_level') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                            <option value="Diamond" {{ old('vip_level') == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                                        </select>
                                        <span class="help-block m-b-none">Chọn cấp độ VIP được phép sử dụng</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="text-success"><i class="fa fa-percent"></i> Thông tin giảm giá</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Loại giảm giá <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="discount_type" class="form-control" id="discount_type">
                                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giá trị giảm <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="discount_value" class="form-control" value="{{ old('discount_value') }}" placeholder="Nhập giá trị giảm" required>
                                            <span class="input-group-addon" id="discount_unit">%</span>
                                        </div>
                                        <span class="help-block m-b-none">Giá trị giảm giá (phần trăm hoặc số tiền)</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Đơn hàng tối thiểu</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="min_order_value" class="form-control" value="{{ old('min_order_value') }}" placeholder="0">
                                            <span class="input-group-addon">₫</span>
                                        </div>
                                        <span class="help-block m-b-none">Để trống nếu không có yêu cầu</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giảm giá tối đa</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="max_discount_value" class="form-control" value="{{ old('max_discount_value') }}" placeholder="0">
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
                                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $defaultStartDate ?? now()->format('Y-m-d\TH:i')) }}" required>
                                        <span class="help-block m-b-none">Thời điểm mã giảm giá bắt đầu có hiệu lực</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', now()->addMonths(1)->format('Y-m-d\TH:i')) }}" required>
                                        <span class="help-block m-b-none">Thời điểm mã giảm giá hết hiệu lực</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Số ngày hiệu lực</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" max="365" name="validity_days" class="form-control" value="{{ old('validity_days', 30) }}" placeholder="30">
                                        <span class="help-block m-b-none">Số ngày hiệu lực sau khi người dùng đổi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="text-info"><i class="fa fa-users"></i> Giới hạn sử dụng</h4>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giới hạn toàn hệ thống</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit', 1000) }}" placeholder="1000">
                                        <span class="help-block m-b-none">Để trống nếu không giới hạn</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Giới hạn/người dùng</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', 1) }}" placeholder="1">
                                        <span class="help-block m-b-none">Để trống nếu không giới hạn</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Điểm đổi</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="0" name="exchange_points" class="form-control" value="{{ old('exchange_points', 0) }}" placeholder="0">
                                        <span class="help-block m-b-none">Số điểm cần để đổi mã giảm giá</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Trạng thái <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm ngừng</option>
                                        </select>
                                        <span class="help-block m-b-none">Chọn trạng thái hoạt động</span>
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
                                <button class="btn btn-primary" type="submit" id="submit-btn">
                                    <i class="fa fa-save"></i> Tạo mã giảm giá
                                </button>
                                <button class="btn btn-info" type="reset">
                                    <i class="fa fa-refresh"></i> Làm mới
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
    const submitBtn = document.getElementById('submit-btn');
    
    // Thay đổi đơn vị khi chọn loại giảm giá
    discountType.addEventListener('change', function() {
        if (this.value === 'percentage') {
            discountUnit.textContent = '%';
        } else {
            discountUnit.textContent = '₫';
        }
    });

    // Validation form
    const form = document.getElementById('coupon-form');
    form.addEventListener('submit', function(e) {
        const code = form.querySelector('input[name="code"]').value.trim();
        const startDate = form.querySelector('input[name="start_date"]').value;
        const endDate = form.querySelector('input[name="end_date"]').value;
        const discountValue = form.querySelector('input[name="discount_value"]').value;

        // Kiểm tra mã giảm giá
        if (!code) {
            alert('Vui lòng nhập mã giảm giá!');
            e.preventDefault();
            return false;
        }

        // Kiểm tra ngày
        if (!startDate || !endDate) {
            alert('Vui lòng chọn ngày bắt đầu và kết thúc!');
            e.preventDefault();
            return false;
        }

        if (new Date(endDate) <= new Date(startDate)) {
            alert('Ngày kết thúc phải sau ngày bắt đầu!');
            e.preventDefault();
            return false;
        }

        // Kiểm tra giá trị giảm
        if (!discountValue || parseFloat(discountValue) <= 0) {
            alert('Vui lòng nhập giá trị giảm hợp lệ!');
            e.preventDefault();
            return false;
        }

        // Hiển thị loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang tạo...';
    });

    // Tự động tạo mã giảm giá
    const codeInput = form.querySelector('input[name="code"]');
    const generateCodeBtn = document.createElement('button');
    generateCodeBtn.type = 'button';
    generateCodeBtn.className = 'btn btn-sm btn-outline btn-info';
    generateCodeBtn.innerHTML = '<i class="fa fa-magic"></i> Tạo mã';
    generateCodeBtn.style.marginLeft = '10px';
    
    generateCodeBtn.addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < 8; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        codeInput.value = result;
    });

    codeInput.parentNode.appendChild(generateCodeBtn);
});
</script>
@endsection
