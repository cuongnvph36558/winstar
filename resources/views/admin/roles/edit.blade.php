@extends('layouts.admin')

@section('title', 'Chỉnh sửa Vai trò')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Chỉnh sửa Vai trò: {{ $role->name }}</h5>
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

                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Tên vai trò <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $role->name) }}" 
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
                                       id="description" name="description" value="{{ old('description', $role->description) }}" 
                                       placeholder="Nhập mô tả vai trò">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
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
                                                            @php
                                                                $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
                                                                $checkedCount = count(array_intersect($groupPermissionIds, $rolePermissions));
                                                            @endphp
                                                            @if($checkedCount > 0)
                                                                <span class="badge badge-success">{{ $checkedCount }}/{{ $groupPermissions->count() }}</span>
                                                            @endif
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach($groupPermissions as $permission)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="permissions[]" value="{{ $permission->id }}" 
                                                                       id="permission_{{ $permission->id }}"
                                                                       {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
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
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Cập nhật vai trò
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

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Thông tin vai trò</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>ID:</strong> {{ $role->id }}</p>
                        <p><strong>Tên hiện tại:</strong> {{ $role->name }}</p>
                        <p><strong>Mô tả:</strong> {{ $role->description ?: 'Chưa có mô tả' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Ngày tạo:</strong> {{ $role->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Cập nhật lần cuối:</strong> {{ $role->updated_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Số quyền hiện tại:</strong> {{ $role->permissions->count() }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($role->users->count() > 0)
                            <h6>Người dùng có vai trò này ({{ $role->users->count() }}):</h6>
                            <div class="row">
                                @foreach($role->users->take(10) as $user)
                                    <div class="col-md-6">
                                        <span class="badge badge-info">{{ $user->name }}</span>
                                    </div>
                                @endforeach
                                @if($role->users->count() > 10)
                                    <div class="col-12">
                                        <small class="text-muted">và {{ $role->users->count() - 10 }} người khác...</small>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted">Chưa có người dùng nào được gán vai trò này.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.card-header').click(function() {
        const card = $(this).closest('.card');
        const checkboxes = card.find('input[type="checkbox"]');
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
        updateGroupBadge(card);
    });

    $('input[type="checkbox"]').change(function() {
        const card = $(this).closest('.card');
        updateGroupBadge(card);
    });

    function updateGroupBadge(card) {
        const checkboxes = card.find('input[type="checkbox"]');
        const checkedCount = checkboxes.filter(':checked').length;
        const totalCount = checkboxes.length;
        
        let badge = card.find('.badge');
        if (checkedCount > 0) {
            if (badge.length === 0) {
                badge = $('<span class="badge badge-success"></span>');
                card.find('.card-header h6').append(' ', badge);
            }
            badge.text(checkedCount + '/' + totalCount);
            badge.removeClass('badge-success badge-warning').addClass(checkedCount === totalCount ? 'badge-success' : 'badge-warning');
        } else {
            badge.remove();
        }
    }

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection 
