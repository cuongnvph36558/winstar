@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thêm mới Video</h5>
                </div>
                <div class="ibox-content">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Phụ đề</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                        </div>

                        <div class="form-group">
                            <label for="background">Ảnh nền</label>
                            <input type="file" name="background" class="form-control-file" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="video">Video</label>
                            <input type="file" name="video" class="form-control-file" accept="video/mp4,video/*" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.video.index') }}" class="btn btn-default">Hủy</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
