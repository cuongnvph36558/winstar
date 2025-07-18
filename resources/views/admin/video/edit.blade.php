@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chỉnh sửa Video</h5>
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

                    <form action="{{ route('admin.video.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $video->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Phụ đề</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $video->subtitle) }}">
                        </div>

                        <div class="form-group">
                            <label>Ảnh nền hiện tại</label><br>
                            @if ($video->background)
                                <img src="{{ asset('storage/' . $video->background) }}" alt="Background" width="150">
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="file" name="background" class="form-control-file" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label>Video hiện tại</label><br>
                            @if ($video->video_path)
                                <video width="240" height="135" controls>
                                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                    Trình duyệt không hỗ trợ video.
                                </video>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="file" name="video" class="form-control-file" accept="video/mp4,video/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.video.index') }}" class="btn btn-default">Hủy</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
