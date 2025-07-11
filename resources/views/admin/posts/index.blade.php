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
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Thêm bài viết</a>
                </div>
                <div class="ibox-content">
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
                                        @if ($post->status == 1)
                                            <span class="label label-primary">Hiển thị</span>
                                        @else
                                            <span class="label label-default">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-xs btn-warning">Sửa</a>
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
