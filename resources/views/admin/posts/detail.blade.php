@extends('layouts.admin')

@section('title', 'Chi tiết bài viết')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chi tiết bài viết</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
            <li class="active"><strong>Chi tiết</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2>{{ $post->title }}</h2>
                    <p><strong>Tác giả:</strong> {{ $post->author->name ?? 'Không xác định' }}</p>
                    <p><strong>Trạng thái:</strong> <span class="label {{ $post->status == 'published' ? 'label-primary' : 'label-default' }}">{{ $post->status == 'published' ? 'Đã đăng' : 'Bản nháp' }}</span></p>
                    <p><strong>Thời gian đăng:</strong> {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}</p>
                    @if($post->image)
                        <p><strong>Hình ảnh:</strong></p>
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh bài viết" style="max-width: 100%; height: auto;">
                    @endif
                    <hr>
                    <p><strong>Nội dung:</strong></p>
                    <div>{!! nl2br(e($post->content)) !!}</div>
                    <hr>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Quay lại danh sách</a>
                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-primary">Chỉnh sửa</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
