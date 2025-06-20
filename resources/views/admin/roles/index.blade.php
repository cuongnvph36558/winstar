@extends('layouts.admin')

@section('title', 'Quản lý Vai trò')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Quản lý Vai trò</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Thêm vai trò
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
                                <th>Tên vai trò</th>
                                <th>Mô tả</th>
                                <th>Số quyền</th>
                                <th>Số người dùng</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>
                                        <strong>{{ $role->name }}</strong>
                                        @if($role->name === 'super_admin')
                                            <span class="badge badge-danger">Super Admin</span>
                                        @elseif($role->name === 'admin')
                                            <span class="badge badge-warning">Admin</span>
                                        @elseif($role->name === 'manager')
                                            <span class="badge badge-info">Manager</span>
                                        @elseif($role->name === 'staff')
                                            <span class="badge badge-success">Staff</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($role->name) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $role->permissions->count() }}</span>
                                        <a href="{{ route('admin.roles.permissions', $role) }}" class="btn btn-xs btn-outline btn-primary">
                                            <i class="fa fa-key"></i> Quản lý
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $role->users->count() }}</span>
                                    </td>
                                    <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($role->users->count() === 0)
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-danger btn-sm" disabled title="Không thể xóa vai trò đang được sử dụng">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <em>Chưa có vai trò nào</em>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($roles->hasPages())
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info">
                                Hiển thị {{ $roles->firstItem() }} đến {{ $roles->lastItem() }} của {{ $roles->total() }} vai trò
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers">
                                {{ $roles->links() }}
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