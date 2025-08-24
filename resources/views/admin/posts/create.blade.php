@extends('layouts.admin')

@section('title', 'Thêm bài viết')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Thêm bài viết</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="{{ route('admin.posts.create') }}">Bài viết</a></li>
            <li class="active"><strong>Thêm</strong></li>
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

                    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung</label>
                            <textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Hình ảnh</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="published_at">Thời gian đăng bài</label>
                            <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
