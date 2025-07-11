@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách Content 1</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.features.create') }}" class="btn btn-primary btn-xs">
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
                                    <th>Phụ đề</th>
                                    <th>Hình ảnh</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($features as $feature)
                                <tr>
                                    <td>{{ $feature->title }}</td>
                                    <td>{{ $feature->subtitle }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $feature->image) }}"
                                             alt="{{ $feature->title }}"
                                             class="img-thumbnail"
                                             style="max-width: 100px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.features.edit', $feature->id) }}" 
                                           class="btn btn-xs btn-info">
                                            <i class="fa fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.features.destroy', $feature->id) }}" 
                                              method="POST" 
                                              style="display:inline;"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa mục này?');">
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
