@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh sửa bài viết</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
            <li class="active"><strong>Sửa</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
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

                    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung</label>
                            <textarea name="content" class="form-control" rows="6" required>{{ old('content', $post->content) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Hình ảnh hiện tại</label><br>
                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Hình ảnh" style="max-height: 150px;">
                            @else
                                <p>Không có hình ảnh</p>
                            @endif
                            <input type="file" name="image" class="form-control mt-2">
                        </div>

                        <div class="form-group">
                            <label for="published_at">Thời gian đăng bài</label>
                            <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ old('status', $post->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('status', $post->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
