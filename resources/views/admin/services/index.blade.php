@extends('layouts.admin')

@section('title', 'Quản lý Dịch vụ')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Danh sách Dịch vụ</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li class="active"><strong>Dịch vụ</strong></li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Thêm dịch vụ
            </a>
        </div>
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
                                <th>#</th>
                                <th>Icon</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Thứ tự</th>
                                <th class="text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span class="{{ $service->icon }}" style="font-size:2rem;"></span>
                                        <div style="font-size:0.85rem; color:#888;">{{ $service->icon }}</div>
                                    </td>
                                    <td>{{ $service->title }}</td>
                                    <td>{{ $service->description }}</td>
                                    <td>{{ $service->order }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <ul class="pagination pull-right"></ul>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
