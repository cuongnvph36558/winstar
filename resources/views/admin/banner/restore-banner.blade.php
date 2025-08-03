@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Khôi phục Banner</h5>
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
                                    <th>Hình ảnh</th>
                                    <th>Liên kết</th>
                                    <th>Ngày xóa</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banners as $banner)
                                <tr>
                                    <td>{{ $banner->title }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $banner->image_url) }}" 
                                             alt="{{ $banner->title }}"
                                             class="img-thumbnail"
                                             style="max-width: 100px;">
                                    </td>
                                    <td>{{ $banner->link }}</td>
                                    <td>{{ $banner->deleted_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.banner.restore', $banner->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-undo"></i> Khôi phục
                                        </a>
                                        <form action="{{ route('admin.banner.force-delete', $banner->id) }}" 
                                              method="POST" 
                                              style="display:inline;"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn banner này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{ route('admin.banner.index-banner') }}" class="btn btn-white">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
