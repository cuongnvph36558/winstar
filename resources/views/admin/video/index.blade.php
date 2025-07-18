@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách Video</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.video.create') }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Video</th>
                                    <th>Hình nền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($videos as $video)
                                <tr>
                                    <td>{{ $video->title }}</td>
                                    <td>
                                        <video width="200" controls poster="{{ $video->background ? asset('storage/' . $video->background) : '' }}">
                                            <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                                            Trình duyệt của bạn không hỗ trợ thẻ video.
                                        </video>
                                    </td>
                                    <td>
                                        @if ($video->background)
                                            <img src="{{ asset('storage/' . $video->background) }}" alt="Background" width="100">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.video.edit', ['id' => $video->id]) }}" class="btn btn-xs btn-info">
                                            <i class="fa fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.video.destroy', ['id' => $video->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa video này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
