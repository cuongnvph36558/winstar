@extends('layouts.admin')

@section('title', 'Tạo nhiều quyền')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Tạo nhiều quyền</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-white">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('admin.permissions.bulk-store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label>Danh sách quyền (mỗi dòng một quyền):</label>
                            <textarea name="permissions" class="form-control" rows="10" placeholder="user.view&#10;user.create&#10;user.edit&#10;user.delete"></textarea>
                            <small class="form-text text-muted">Mỗi dòng một quyền, format: module.action</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Tạo quyền</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
