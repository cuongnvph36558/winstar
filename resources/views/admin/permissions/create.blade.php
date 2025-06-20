@extends('layouts.admin')

@section('title', 'Thêm Quyền mới')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Thêm Quyền mới</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên quyền <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Ví dụ: user.create, product.edit">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Sử dụng định dạng: [module].[action] (ví dụ: user.view, product.create)
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                               id="description" name="description" value="{{ old('description') }}" 
                               placeholder="Mô tả chi tiết về quyền này">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="hr-line-dashed"></div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Lưu quyền
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-white">
                            <i class="fa fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Gợi ý đặt tên quyền</h5>
            </div>
            <div class="ibox-content">
                <h6>Quyền người dùng:</h6>
                <ul class="list-unstyled">
                    <li><code>user.view</code> - Xem danh sách</li>
                    <li><code>user.create</code> - Tạo mới</li>
                    <li><code>user.edit</code> - Chỉnh sửa</li>
                    <li><code>user.delete</code> - Xóa</li>
                </ul>

                <h6>Quyền sản phẩm:</h6>
                <ul class="list-unstyled">
                    <li><code>product.view</code> - Xem danh sách</li>
                    <li><code>product.create</code> - Tạo mới</li>
                    <li><code>product.edit</code> - Chỉnh sửa</li>
                    <li><code>product.delete</code> - Xóa</li>
                </ul>

                <h6>Quyền đơn hàng:</h6>
                <ul class="list-unstyled">
                    <li><code>order.view</code> - Xem danh sách</li>
                    <li><code>order.process</code> - Xử lý đơn hàng</li>
                    <li><code>order.cancel</code> - Hủy đơn hàng</li>
                </ul>

                <div class="alert alert-info mt-3">
                    <strong>Lưu ý:</strong> Tên quyền nên theo format [module].[action] để dễ quản lý và nhóm theo chức năng.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto generate description based on permission name
    $('#name').on('blur', function() {
        let name = $(this).val();
        let description = $('#description').val();
        
        if (name && !description) {
            // Parse permission name to generate description
            let parts = name.split('.');
            if (parts.length === 2) {
                let module = parts[0];
                let action = parts[1];
                
                let moduleNames = {
                    'user': 'người dùng',
                    'role': 'vai trò',
                    'permission': 'quyền',
                    'category': 'danh mục',
                    'product': 'sản phẩm',
                    'order': 'đơn hàng',
                    'review': 'đánh giá',
                    'dashboard': 'dashboard',
                    'report': 'báo cáo',
                    'setting': 'cài đặt'
                };
                
                let actionNames = {
                    'view': 'Xem danh sách',
                    'create': 'Tạo mới',
                    'edit': 'Chỉnh sửa',
                    'update': 'Cập nhật',
                    'delete': 'Xóa',
                    'manage': 'Quản lý',
                    'process': 'Xử lý',
                    'export': 'Xuất'
                };
                
                let moduleName = moduleNames[module] || module;
                let actionName = actionNames[action] || action;
                
                $('#description').val(actionName + ' ' + moduleName);
            }
        }
    });

    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection 