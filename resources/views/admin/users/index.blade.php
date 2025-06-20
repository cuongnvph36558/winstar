@extends('layouts.admin')

@section('title', 'Quản lý thành viên')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Quản lý thành viên</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Quản lý thành viên</strong>
            </li>
        </ol>
    </div>

</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Danh sách thành viên</h5>
                </div>
                <div class="ibox-content">
                    <!-- Form tìm kiếm và lọc -->
                    <form method="GET" action="{{ route('admin.users.index') }}" class="m-b-md">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Tìm kiếm theo tên, email, số điện thoại..." class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <select name="role" class="form-control">
                                    <option value="">Tất cả vai trò</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->description ?? $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="status" class="form-control">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Tìm kiếm
                                </button>
                            </div>
                            <div class="col-sm-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-white">
                                    <i class="fa fa-refresh"></i> Làm mới
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Thông báo -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Bảng danh sách -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id == auth()->id())
                                                <span class="label label-info">Bạn</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?: '-' }}</td>
                                        <td>
                                            @forelse($user->roles as $role)
                                                <span class="label label-primary">{{ $role->description ?? $role->name }}</span>
                                            @empty
                                                <span class="label label-default">Chưa có vai trò</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <div class="switch">
                                                <div class="onoffswitch">
                                                    <input type="checkbox" class="onoffswitch-checkbox status-toggle" 
                                                           id="status{{ $user->id }}" 
                                                           data-user-id="{{ $user->id }}"
                                                           {{ $user->status == 1 ? 'checked' : '' }}
                                                           {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                                    <label class="onoffswitch-label" for="status{{ $user->id }}">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                                   class="btn btn-info btn-xs" title="Xem chi tiết">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                   class="btn btn-warning btn-xs" title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if($user->id != auth()->id())
                                                    <button type="button" class="btn btn-danger btn-xs delete-btn" 
                                                            data-user-id="{{ $user->id }}" 
                                                            data-user-name="{{ $user->name }}" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    @if($users->hasPages())
                        <div class="text-center">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title">Xác nhận xóa</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa thành viên <strong id="userName"></strong> không?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Xử lý toggle trạng thái
    $('.status-toggle').change(function() {
        var userId = $(this).data('user-id');
        var isChecked = $(this).is(':checked');
        
        $.ajax({
            url: '/admin/users/' + userId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error('Có lỗi xảy ra');
                    // Revert toggle
                    $(this).prop('checked', !isChecked);
                }
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'Có lỗi xảy ra';
                toastr.error(error);
                // Revert toggle
                $(this).prop('checked', !isChecked);
            }
        });
    });

    // Xử lý xóa
    $('.delete-btn').click(function() {
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        
        $('#userName').text(userName);
        $('#deleteForm').attr('action', '/admin/users/' + userId);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection

@section('styles')
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.onoffswitch {
    position: relative;
    width: 60px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

.onoffswitch-checkbox {
    display: none;
}

.onoffswitch-label {
    display: block;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid #999999;
    border-radius: 20px;
}

.onoffswitch-inner {
    display: block;
    width: 200%;
    margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}

.onoffswitch-inner:before,
.onoffswitch-inner:after {
    display: block;
    float: left;
    width: 50%;
    height: 30px;
    padding: 0;
    line-height: 30px;
    font-size: 14px;
    color: white;
    font-family: Trebuchet, Arial, sans-serif;
    font-weight: bold;
    box-sizing: border-box;
}

.onoffswitch-inner:before {
    content: "ON";
    padding-left: 10px;
    background-color: #1AB394;
    color: #FFFFFF;
}

.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 10px;
    background-color: #EEEEEE;
    color: #999999;
    text-align: right;
}

.onoffswitch-switch {
    display: block;
    width: 22px;
    margin: 4px;
    background: #FFFFFF;
    position: absolute;
    top: 0;
    bottom: 0;
    right: 26px;
    border: 2px solid #999999;
    border-radius: 20px;
    transition: all 0.3s ease-in 0s;
}

.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}

.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px;
}

.onoffswitch-checkbox:disabled + .onoffswitch-label {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@endsection 