@extends('layouts.admin')

@section('title', 'Quản lý Quyền - ' . $role->name)

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Quản lý Quyền cho Vai trò: {{ $role->name }}</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Quay lại danh sách
                    </a>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i> Chỉnh sửa vai trò
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-8">
                        <h4>{{ $role->name }}</h4>
                        <p class="text-muted">{{ $role->description ?: 'Chưa có mô tả' }}</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-success" id="selectAll">
                                <i class="fa fa-check-square-o"></i> Chọn tất cả
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning" id="deselectAll">
                                <i class="fa fa-square-o"></i> Bỏ chọn tất cả
                            </button>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.roles.update-permissions', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @if($permissions->count() > 0)
                        @php
                            $permissionGroups = $permissions->groupBy(function($permission) {
                                return explode('.', $permission->name)[0];
                            });
                        @endphp
                        
                        <div class="row">
                            @foreach($permissionGroups as $group => $groupPermissions)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            @php
                                                $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
                                                $checkedCount = count(array_intersect($groupPermissionIds, $rolePermissions));
                                                $totalCount = $groupPermissions->count();
                                            @endphp
                                            <h6 class="mb-0">
                                                {{ ucfirst($group) }}
                                                <small class="text-muted">({{ $totalCount }})</small>
                                                <span class="badge {{ $checkedCount === $totalCount ? 'badge-success' : ($checkedCount > 0 ? 'badge-warning' : 'badge-secondary') }}">
                                                    {{ $checkedCount }}/{{ $totalCount }}
                                                </span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input group-toggle" type="checkbox" 
                                                       data-group="{{ $group }}" 
                                                       {{ $checkedCount === $totalCount ? 'checked' : '' }}>
                                                <label class="form-check-label font-weight-bold">
                                                    Chọn tất cả
                                                </label>
                                            </div>
                                            <hr class="my-2">
                                            @foreach($groupPermissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}"
                                                           data-group="{{ $group }}"
                                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
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
                        </div>
                    @else
                        <div class="alert alert-info">
                            Chưa có quyền nào trong hệ thống. 
                            <a href="{{ route('admin.permissions.create') }}">Tạo quyền mới</a>
                        </div>
                    @endif

                    <div class="hr-line-dashed"></div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Lưu thay đổi
                                </button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-white">
                                    <i class="fa fa-times"></i> Hủy
                                </a>
                            </div>
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
    // Select/Deselect all buttons
    $('#selectAll').click(function() {
        $('.permission-checkbox').prop('checked', true);
        updateGroupBadges();
        updateGroupToggles();
    });
    
    $('#deselectAll').click(function() {
        $('.permission-checkbox').prop('checked', false);
        updateGroupBadges();
        updateGroupToggles();
    });
    
    // Group toggle functionality
    $('.group-toggle').change(function() {
        const group = $(this).data('group');
        const isChecked = $(this).is(':checked');
        
        $(`.permission-checkbox[data-group="${group}"]`).prop('checked', isChecked);
        updateGroupBadge(group);
    });
    
    // Individual permission change
    $('.permission-checkbox').change(function() {
        const group = $(this).data('group');
        updateGroupBadge(group);
        updateGroupToggle(group);
    });
    
    function updateGroupBadges() {
        $('.group-toggle').each(function() {
            const group = $(this).data('group');
            updateGroupBadge(group);
        });
    }
    
    function updateGroupToggles() {
        $('.group-toggle').each(function() {
            const group = $(this).data('group');
            updateGroupToggle(group);
        });
    }
    
    function updateGroupBadge(group) {
        const total = $(`.permission-checkbox[data-group="${group}"]`).length;
        const checked = $(`.permission-checkbox[data-group="${group}"]:checked`).length;
        
        const card = $(`.group-toggle[data-group="${group}"]`).closest('.card');
        const badge = card.find('.card-header .badge');
        
        badge.text(`${checked}/${total}`);
        badge.removeClass('badge-success badge-warning badge-secondary');
        
        if (checked === total) {
            badge.addClass('badge-success');
        } else if (checked > 0) {
            badge.addClass('badge-warning');
        } else {
            badge.addClass('badge-secondary');
        }
    }
    
    function updateGroupToggle(group) {
        const total = $(`.permission-checkbox[data-group="${group}"]`).length;
        const checked = $(`.permission-checkbox[data-group="${group}"]:checked`).length;
        
        const toggle = $(`.group-toggle[data-group="${group}"]`);
        toggle.prop('checked', checked === total);
    }
    
    // Initialize group badges and toggles
    updateGroupBadges();
    updateGroupToggles();
    
    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection 
