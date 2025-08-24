@extends('layouts.admin')

@section('title', 'Thêm Vai trò mới')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Thêm Vai trò mới</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
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

                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Tên vai trò <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Nhập tên vai trò">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                       id="description" name="description" value="{{ old('description') }}" 
                                       placeholder="Nhập mô tả vai trò">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Quyền hạn</label>
                                <div class="row">
                                    @if($permissions->count() > 0)
                                        @php
                                            $permissionGroups = $permissions->groupBy(function($permission) {
                                                return explode('.', $permission->name)[0];
                                            });
                                        @endphp
                                        
                                        @foreach($permissionGroups as $group => $groupPermissions)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">
                                                            {{ ucfirst($group) }}
                                                            <small class="text-muted">({{ $groupPermissions->count() }})</small>
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach($groupPermissions as $permission)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="permissions[]" value="{{ $permission->id }}" 
                                                                       id="permission_{{ $permission->id }}"
                                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                    {{ $permission->description }}
                                                                    <small class="text-muted">({{ $permission->name }})</small>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                Chưa có quyền nào trong hệ thống. 
                                                <a href="{{ route('admin.permissions.create') }}">Tạo quyền mới</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Lưu vai trò
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-white">
                                <i class="fa fa-times"></i> Hủy
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all permissions in a group
    $('.card-header').click(function() {
        const card = $(this).closest('.card');
        const checkboxes = card.find('input[type="checkbox"]');
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
    });

    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection 
