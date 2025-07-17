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

                        <form action="{{ route('admin.video.update', $video->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $video->title) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="subtitle">Phụ đề</label>
                                <input type="text" name="subtitle" class="form-control"
                                    value="{{ old('subtitle', $video->subtitle) }}">
                            </div>

                            <div class="form-group">
                                <label for="background">Ảnh nền hiện tại</label><br>
                                @if ($video->background)
                                    <img src="{{ asset('storage/' . $video->background) }}" alt="Background"
                                        class="img-thumbnail" style="max-width: 200px;">
                                @endif
                                <input type="file" name="background" class="form-control-file" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label for="path">Video hiện tại</label><br>
                                @if ($video->path)
                                    <video width="320" height="240" controls>
                                        <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                                        Trình duyệt của bạn không hỗ trợ video.
                                    </video>
                                @endif
                                <input type="file" name="path" class="form-control-file" accept="video/mp4,video/*">
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