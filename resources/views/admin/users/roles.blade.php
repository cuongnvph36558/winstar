@extends('layouts.admin')

@section('title', 'Quản lý vai trò người dùng')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Quản lý vai trò cho: {{ $user->name }}</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-white">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('admin.users.update-roles', $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Vai trò hiện tại:</label>
                            <div class="checkbox">
                                @foreach($roles as $role)
                                    <label>
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                        {{ $role->name }} - {{ $role->description }}
                                    </label><br>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Cập nhật vai trò</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
