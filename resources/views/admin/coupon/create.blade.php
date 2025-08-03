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

          <form method="POST" action="{{ route('admin.coupon.store') }}" class="form-horizontal" id="coupon-form">
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
                <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" max="999999.99">
                <small class="text-muted">Tối đa: 999,999.99</small>
                <div class="error-message text-danger" style="display: none;"></div>
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
                <input type="number" step="0.01" name="min_order_value" class="form-control" value="{{ old('min_order_value') }}" max="999999999.99">
                <small class="text-muted">Tối đa: 999,999,999.99</small>
                <div class="error-message text-danger" style="display: none;"></div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Giá trị giảm tối đa</label>
              <div class="col-sm-10">
                <input type="number" step="0.01" name="max_discount_value" class="form-control" value="{{ old('max_discount_value') }}" max="999999999.99">
                <small class="text-muted">Tối đa: 999,999,999.99</small>
                <div class="error-message text-danger" style="display: none;"></div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Số lần sử dụng</label>
              <div class="col-sm-10">
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" max="999999">
                <small class="text-muted">Tối đa: 999,999</small>
                <div class="error-message text-danger" style="display: none;"></div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Số lần/người</label>
              <div class="col-sm-10">
                <input type="number" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user') }}" max="999999">
                <small class="text-muted">Tối đa: 999,999</small>
                <div class="error-message text-danger" style="display: none;"></div>
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

@section('styles')
<style>
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.error-message {
    margin-top: 5px;
    font-size: 12px;
}

.text-muted {
    font-size: 11px;
    color: #6c757d;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validation rules
    const validationRules = {
        'discount_value': { max: 999999.99, message: 'Giá trị giảm không được vượt quá 999,999.99' },
        'min_order_value': { max: 999999999.99, message: 'Đơn hàng tối thiểu không được vượt quá 999,999,999.99' },
        'max_discount_value': { max: 999999999.99, message: 'Giá trị giảm tối đa không được vượt quá 999,999,999.99' },
        'usage_limit': { max: 999999, message: 'Số lần sử dụng không được vượt quá 999,999' },
        'usage_limit_per_user': { max: 999999, message: 'Số lần sử dụng/người không được vượt quá 999,999' }
    };

    // Function to validate field
    function validateField(fieldName, value) {
        const rule = validationRules[fieldName];
        if (!rule) return true;

        const numValue = parseFloat(value);
        if (isNaN(numValue)) return true;

        if (numValue > rule.max) {
            showFieldError(fieldName, rule.message);
            return false;
        } else {
            hideFieldError(fieldName);
            return true;
        }
    }

    // Function to show field error
    function showFieldError(fieldName, message) {
        const field = $(`input[name="${fieldName}"]`);
        const errorDiv = field.closest('.form-group').find('.error-message');
        errorDiv.text(message).show();
        field.addClass('is-invalid');
    }

    // Function to hide field error
    function hideFieldError(fieldName) {
        const field = $(`input[name="${fieldName}"]`);
        const errorDiv = field.closest('.form-group').find('.error-message');
        errorDiv.hide();
        field.removeClass('is-invalid');
    }

    // Add validation on input change
    Object.keys(validationRules).forEach(fieldName => {
        $(`input[name="${fieldName}"]`).on('input', function() {
            validateField(fieldName, $(this).val());
        });
    });

    // Form submission validation
    $('#coupon-form').on('submit', function(e) {
        let isValid = true;
        
        // Validate all fields
        Object.keys(validationRules).forEach(fieldName => {
            const value = $(`input[name="${fieldName}"]`).val();
            if (!validateField(fieldName, value)) {
                isValid = false;
            }
        });

        // Additional validations
        if (!$('input[name="code"]').val().trim()) {
            alert('Mã giảm giá là bắt buộc');
            isValid = false;
        }

        if (!$('input[name="start_date"]').val()) {
            alert('Ngày bắt đầu là bắt buộc');
            isValid = false;
        }

        if (!$('input[name="end_date"]').val()) {
            alert('Ngày kết thúc là bắt buộc');
            isValid = false;
        }

        // Check if end_date is after start_date
        const startDate = new Date($('input[name="start_date"]').val());
        const endDate = new Date($('input[name="end_date"]').val());
        if (endDate <= startDate) {
            alert('Ngày kết thúc phải sau ngày bắt đầu');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading
        $('button[type="submit"]').prop('disabled', true).text('Đang lưu...');
    });
});
</script>
@endsection
