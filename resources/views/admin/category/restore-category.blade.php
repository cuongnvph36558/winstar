@extends('layouts.admin')

@section('title', 'Khôi phục danh mục')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Khôi phục danh mục</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.category.index-category') }}">Danh mục</a>
                </li>
                <li class="active">
                    <strong>Khôi phục</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.category.index-category') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Quay lại
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
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
                                    <th data-toggle="true">Tên danh mục</th>
                                    <th data-hide="phone">Loại danh mục</th>
                                    <th class="text-right" data-sort-ignore="true">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            {{ $category->name }}
                                        </td>
                                        <td>
                                            {{ $category->parent_id == 0 ? 'Cha' : 'Con' }}
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.category.restore', $category->id) }}"
                                                    class="btn btn-warning btn-xs"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục danh mục này?')"><i
                                                        class="fa fa-recycle"></i> Khôi phục</a>
                                                <a href="{{ route('admin.category.force-delete', $category->id) }}"
                                                    class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn danh mục này?')"><i
                                                        class="fa fa-trash"></i> Xóa vĩnh viễn</a>
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