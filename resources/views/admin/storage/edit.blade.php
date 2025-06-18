@extends('layouts.admin')

@section('title', 'Sửa dung lượng')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Sửa Dung Lượng</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.storage.update', $storage->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="text" name="capacity" value="{{ $storage->capacity }}" class="form-control" required>
                            @error('capacity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                        <a href="{{ route('admin.storage.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
