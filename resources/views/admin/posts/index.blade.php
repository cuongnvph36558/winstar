@extends('layouts.admin')

@section('title', 'Danh sách bài viết')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Danh sách bài viết</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li class="active"><strong>Bài viết</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Thêm bài viết</a>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="label label-primary">Tổng: {{ $posts->total() }}</span>
                            <span class="label label-success">Đã đăng: {{ $posts->where('status', 'published')->count() }}</span>
                            <span class="label label-default">Bản nháp: {{ $posts->where('status', 'draft')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.posts.index') }}" class="form-inline mb-3">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề..." value="{{ request('search') }}">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Làm mới</a>
                    </form>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tiêu đề</th>
                                <th>Ngày đăng</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}</td>
                                    <td>
                                        @if ($post->status == 'published')
                                            <span class="label label-primary">Đã đăng</span>
                                        @else
                                            <span class="label label-default">Bản nháp</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                                        <a href="{{ route('admin.posts.detail', $post->id) }}" class="btn btn-xs btn-info">Xem</a>
                                        @if($post->status == 'draft')
                                            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="published">
                                                <button type="submit" class="btn btn-xs btn-success" onclick="return confirm('Đăng bài viết này?')">Đăng</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="draft">
                                                <button type="submit" class="btn btn-xs btn-default" onclick="return confirm('Chuyển về bản nháp?')">Bản nháp</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
