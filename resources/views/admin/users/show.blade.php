@extends('layouts.admin')

@section('title', 'Chi tiết thành viên')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chi tiết thành viên: {{ $user->name }}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}">Quản lý thành viên</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Chi tiết</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="title-action">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin cơ bản -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin cơ bản</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><strong>ID:</strong></td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tên đầy đủ:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Số điện thoại:</strong></td>
                                    <td>{{ $user->phone ?: 'Chưa cung cấp' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><strong>Trạng thái:</strong></td>
                                    <td>
                                        @if($user->status == 1)
                                            <span class="label label-success">
                                                <i class="fa fa-check"></i> Hoạt động
                                            </span>
                                        @else
                                            <span class="label label-danger">
                                                <i class="fa fa-ban"></i> Tạm khóa
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cập nhật cuối:</strong></td>
                                    <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email xác thực:</strong></td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="label label-success">
                                                <i class="fa fa-check"></i> Đã xác thực
                                            </span>
                                            <br><small>{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="label label-warning">
                                                <i class="fa fa-clock-o"></i> Chưa xác thực
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($user->address)
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h4>Địa chỉ</h4>
                                <p class="well">{{ $user->address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vai trò và quyền -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Vai trò và quyền hạn</h5>
                </div>
                <div class="ibox-content">
                    @if($user->roles->count() > 0)
                        <div class="row">
                            @foreach($user->roles as $role)
                                <div class="col-md-6 mb-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="fa fa-user-tag"></i> {{ $role->description ?? $role->name }}
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p><strong>Tên vai trò:</strong> {{ $role->name }}</p>
                                            @if($role->description)
                                                <p><strong>Mô tả:</strong> {{ $role->description }}</p>
                                            @endif
                                            
                                            @if($role->permissions->count() > 0)
                                                <p><strong>Quyền hạn:</strong></p>
                                                <div class="tags">
                                                    @foreach($role->permissions as $permission)
                                                        <span class="tag">{{ $permission->description ?? $permission->name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted">Không có quyền hạn nào</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> Thành viên này chưa được gán vai trò nào.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Actions -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Hành động</h5>
                </div>
                <div class="ibox-content">
                    <div class="btn-group-vertical btn-group-full">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Chỉnh sửa thông tin
                        </a>
                        @if($user->id != auth()->id())
                            <button type="button" class="btn btn-warning toggle-status-btn" 
                                    data-user-id="{{ $user->id }}" data-current-status="{{ $user->status }}">
                                @if($user->status == 1)
                                    <i class="fa fa-ban"></i> Tạm khóa tài khoản
                                @else
                                    <i class="fa fa-check"></i> Kích hoạt tài khoản
                                @endif
                            </button>
                            <button type="button" class="btn btn-danger delete-btn" 
                                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                <i class="fa fa-trash"></i> Xóa thành viên
                            </button>
                        @endif
                        <a href="{{ route('admin.users.index') }}" class="btn btn-white">
                            <i class="fa fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thống kê</h5>
                </div>
                <div class="ibox-content">
                    <div class="row text-center">
                        <div class="col-xs-6">
                            <h2 class="text-primary">{{ $user->roles->count() }}</h2>
                            <span>Vai trò</span>
                        </div>
                        <div class="col-xs-6">
                            <h2 class="text-success">
                                {{ $user->roles->sum(function($role) { return $role->permissions->count(); }) }}
                            </h2>
                            <span>Quyền hạn</span>
                        </div>
                    </div>
                    <div class="row text-center" style="margin-top: 20px;">
                        <div class="col-xs-12">
                            <h2 class="text-info">{{ $user->created_at ? $user->created_at->diffInDays() : 0 }}</h2>
                            <span>Ngày gia nhập</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->id == auth()->id())
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Đây là tài khoản của bạn.</strong>
                            <br>Bạn không thể thay đổi trạng thái hoặc xóa tài khoản này.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal xác nhận thay đổi trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title">Xác nhận thay đổi trạng thái</h4>
            </div>
            <div class="modal-body">
                <p id="statusConfirmText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Xác nhận</button>
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
    // Xử lý thay đổi trạng thái
    $('.toggle-status-btn').click(function() {
        var userId = $(this).data('user-id');
        var currentStatus = $(this).data('current-status');
        var newStatus = currentStatus == 1 ? 0 : 1;
        var actionText = newStatus == 1 ? 'kích hoạt' : 'tạm khóa';
        
        $('#statusConfirmText').text('Bạn có chắc chắn muốn ' + actionText + ' tài khoản này không?');
        $('#confirmStatusChange').data('user-id', userId);
        $('#statusModal').modal('show');
    });

    $('#confirmStatusChange').click(function() {
        var userId = $(this).data('user-id');
        
        $.ajax({
            url: '/admin/users/' + userId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error('Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'Có lỗi xảy ra';
                toastr.error(error);
            }
        });
        
        $('#statusModal').modal('hide');
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
.tags {
    margin-top: 10px;
}

.tag {
    display: inline-block;
    background: #f3f3f4;
    color: #676a6c;
    padding: 4px 8px;
    margin: 2px;
    border-radius: 3px;
    font-size: 11px;
    border: 1px solid #e7eaec;
}

.btn-group-full .btn {
    margin-bottom: 5px;
}

.panel {
    margin-bottom: 20px;
}

.panel-primary {
    border-color: #1ab394;
}

.panel-primary > .panel-heading {
    background-color: #1ab394;
    border-color: #1ab394;
    color: white;
}

.well {
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    padding: 19px;
    margin-bottom: 20px;
}
</style>
@endsection 