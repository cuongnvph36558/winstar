@extends('layouts.admin')

@section('title', 'Khôi phục sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Khôi phục sản phẩm</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.product.index-product') }}">Sản phẩm</a>
            </li>
            <li class="active">
                <strong>Khôi phục</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    @if(session('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Ảnh</th>
                                <th class="text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" width="50">
                                    @else
                                        Không có ảnh
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.product.restore', $product->id) }}" class="btn btn-warning btn-xs">
                                            <i class="fa fa-recycle"></i> Khôi phục
                                        </a>
                                        <form action="{{ route('admin.product.force-delete', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs" onclick="return confirm('Xóa vĩnh viễn sản phẩm này?')">
                                                <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <div class="pull-right">
                            
                                    </div>
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
