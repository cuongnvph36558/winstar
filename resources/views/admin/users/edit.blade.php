@extends('layouts.admin')

@section('title', 'Chỉnh sửa thành viên')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh sửa thành viên: {{ $user->name }}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}">Quản lý thành viên</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Chỉnh sửa</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            {{-- val --}}
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin thành viên</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tên đầy đủ <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" placeholder="Nhập tên đầy đủ">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" placeholder="Nhập địa chỉ email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Số điện thoại</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Địa chỉ</label>
                            <div class="col-sm-9">
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" placeholder="Nhập địa chỉ">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Mật khẩu mới</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Để trống nếu không muốn thay đổi mật khẩu">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu hiện tại</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Xác nhận mật khẩu</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control" 
                                       placeholder="Nhập lại mật khẩu mới để xác nhận">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Trạng thái <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Tạm khóa</option>
                                </select>
                                @if($user->id == auth()->id())
                                    <input type="hidden" name="status" value="{{ $user->status }}">
                                @endif
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->id == auth()->id())
                                    <small class="form-text text-warning">Bạn không thể thay đổi trạng thái của chính mình</small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Vai trò</label>
                            <div class="col-sm-9">
                                @if($roles->count() > 0)
                                    @php
                                        $userRoleIds = old('roles', $user->roles->pluck('id')->toArray());
                                    @endphp
                                    @foreach($roles as $role)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" 
                                                   value="{{ $role->id }}" id="role{{ $role->id }}"
                                                   {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role{{ $role->id }}">
                                                <strong>{{ $role->description ?? $role->name }}</strong>
                                                @if($role->description && $role->description != $role->name)
                                                    <small class="text-muted">({{ $role->name }})</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Chưa có vai trò nào được tạo</p>
                                @endif
                                @error('roles')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Cập nhật thành viên
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-white">
                                    <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">
                                    <i class="fa fa-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin hiện tại</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tên:</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                @if($user->status == 1)
                                    <span class="label label-success">Hoạt động</span>
                                @else
                                    <span class="label label-danger">Tạm khóa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Vai trò:</strong></td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="label label-primary">{{ $role->description ?? $role->name }}</span>
                                @empty
                                    <span class="text-muted">Chưa có vai trò</span>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Ngày tạo:</strong></td>
                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật cuối:</strong></td>
                            <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="ibox">
                <div class="ibox-title">
                    <h5>Hướng dẫn</h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Lưu ý:</h4>
                        <ul class="m-b-none">
                            <li>Để trống mật khẩu nếu không muốn thay đổi</li>
                            <li>Email phải là duy nhất trong hệ thống</li>
                            <li>Không thể tự thay đổi trạng thái của mình</li>
                            <li>Có thể gán/bỏ vai trò bất kỳ lúc nào</li>
                        </ul>
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
    // Disable status field if editing self
    @if($user->id == auth()->id())
        $('select[name="status"]').prop('disabled', true);
    @endif

    // Validation frontend
    $('form').submit(function(e) {
        var password = $('input[name="password"]').val();
        var confirmPassword = $('input[name="password_confirmation"]').val();
        
        // Only validate if password is entered
        if (password.length > 0) {
            if (password !== confirmPassword) {
                e.preventDefault();
                toastr.error('Mật khẩu xác nhận không khớp');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                toastr.error('Mật khẩu phải có ít nhất 8 ký tự');
                return false;
            }
        }
    });
});
</script>
@endsection 