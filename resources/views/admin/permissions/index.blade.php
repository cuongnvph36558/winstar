@extends('layouts.admin')

@section('title', 'Quản lý Quyền')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Quản lý Quyền</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Thêm quyền
                    </a>
                    <a href="{{ route('admin.permissions.bulk-create') }}" class="btn btn-info btn-sm">
                        <i class="fa fa-plus-circle"></i> Thêm nhiều quyền
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

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên quyền</th>
                                <th>Mô tả</th>
                                <th>Nhóm</th>
                                <th>Số vai trò sử dụng</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                @php
                                    $group = explode('.', $permission->name)[0] ?? 'other';
                                    $groupColors = [
                                        'user' => 'primary',
                                        'role' => 'success',
                                        'permission' => 'warning',
                                        'category' => 'info',
                                        'product' => 'secondary',
                                        'order' => 'danger',
                                        'review' => 'dark',
                                        'dashboard' => 'success',
                                        'report' => 'primary',
                                        'setting' => 'warning'
                                    ];
                                    $color = $groupColors[$group] ?? 'light';
                                @endphp
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>
                                        <code>{{ $permission->name }}</code>
                                    </td>
                                    <td>{{ $permission->description }}</td>
                                    <td>
                                        <span class="badge badge-{{ $color }}">{{ ucfirst($group) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $permission->roles->count() }}</span>
                                        @if($permission->roles->count() > 0)
                                            <small class="text-muted">
                                                ({{ $permission->roles->pluck('name')->join(', ') }})
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($permission->roles->count() === 0)
                                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quyền này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-danger btn-sm" disabled title="Không thể xóa quyền đang được sử dụng">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <em>Chưa có quyền nào</em>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($permissions->hasPages())
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info">
                                Hiển thị {{ $permissions->firstItem() }} đến {{ $permissions->lastItem() }} của {{ $permissions->total() }} quyền
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers">
                                {{ $permissions->links("pagination::bootstrap-4") }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection 
