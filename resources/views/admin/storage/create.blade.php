@extends('layouts.admin')

@section('title', 'Thêm dung lượng')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thêm Dung Lượng</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.storage.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="text" name="capacity" class="form-control" placeholder="VD: 128GB, 1TB..." required>
                            @error('capacity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.storage.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
