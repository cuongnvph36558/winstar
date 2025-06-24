@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách Banner</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.banner.create-banner') }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i> Thêm mới Banner
                        </a>
                        <a href="{{ route('admin.banner.restore-banner') }}" class="btn btn-warning btn-xs">
                            <i class="fa fa-recycle"></i> Khôi phục Banner
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
                                    <th>Hình ảnh</th>
                                    <th>Liên kết</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
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
                                    <td>
                                        <span class="label {{ $banner->status == 'active' ? 'label-primary' : 'label-default' }}">
                                            {{ ucfirst($banner->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $banner->start_date }}</td>
                                    <td>{{ $banner->end_date }}</td>
                                    <td>
                                        <a href="{{ route('admin.banner.detail-banner', $banner->id) }}" 
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-eye"></i> Chi tiết
                                        </a>
                                        <a href="{{ route('admin.banner.edit-banner', $banner->id) }}" 
                                           class="btn btn-xs btn-info">
                                            <i class="fa fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.banner.destroy-banner', $banner->id) }}" 
                                              method="POST" 
                                              style="display:inline;"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');">
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
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
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