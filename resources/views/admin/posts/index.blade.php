{{-- @extends('layouts.admin')

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
    <div class="col-lg-2 text-right" style="margin-top: 30px;">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm bài viết
        </a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    @if(session('success'))
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10">
                        <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Tác giả</th>
                                <th>Trạng thái</th>
                                <th>Ngày đăng</th>
                                <th class="text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->author->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="label {{ $post->status ? 'label-primary' : 'label-default' }}">
                                            {{ $post->status ? 'Hiển thị' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.posts.detail', $post->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Xác nhận xóa?')" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
