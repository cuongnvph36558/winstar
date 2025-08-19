@extends('layouts.admin')
@section('title', 'Quản lý biến thể sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Quản lý biến thể sản phẩm</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a>
            </li>
            <li>
                <a href="{{ route('admin.product.index-product') }}">Sản phẩm</a>
            </li>
            <li class="active">
                <strong>Quản lý biến thể</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Color Variants Section -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-palette"></i> Biến thể màu sắc</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.product.product-variant.variant.create-color') }}" class="btn btn-primary btn-xs" title="Thêm biến thể màu sắc">
                            <i class="fa fa-plus"></i> Thêm màu sắc
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên màu sắc</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($colors as $key => $color)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <span class="label label-primary">{{ $color->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.product-variant.variant.edit-color', $color->id) }}" 
                                                   class="btn btn-warning btn-xs" 
                                                   title="Chỉnh sửa màu sắc">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.product-variant.variant.delete-color', $color->id) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa biến thể màu sắc này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-xs" 
                                                            title="Xóa màu sắc">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> Không tìm thấy biến thể màu sắc nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Variants Section -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-hdd-o"></i> Biến thể dung lượng</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.product.product-variant.variant.create-storage') }}" class="btn btn-primary btn-xs" title="Thêm biến thể dung lượng">
                            <i class="fa fa-plus"></i> Thêm dung lượng
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Dung lượng lưu trữ</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storages as $key => $storage)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <span class="label label-info">{{ $storage->capacity }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.product-variant.variant.edit-storage', $storage->id) }}" 
                                                   class="btn btn-warning btn-xs" 
                                                   title="Chỉnh sửa dung lượng">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.product-variant.variant.delete-storage', $storage->id) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa biến thể dung lượng này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-xs" 
                                                            title="Xóa dung lượng">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> Không tìm thấy biến thể dung lượng nào
                                        </td>
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
