@extends('layouts.admin')
@section('title', 'Chi tiết danh mục')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Chi tiết danh mục</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="#">Trang chủ</a>
                </li>
                <li>
                    <a href="{{ route('admin.category.index-category') }}">Danh mục</a>
                </li>
                <li class="active">
                    <strong>Chi tiết</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="font-bold m-b-xs">{{ $category->name }}</h2>
                                <hr>

                                <dl class="dl-horizontal m-t-md">
                                    <dt>Loại danh mục:</dt>
                                    <dd>{{ $category->parent_id == 0 ? 'Danh mục cha' : 'Danh mục con' }}</dd>

                                    @if($category->parent_id != 0)
                                        <dt>Danh mục cha:</dt>
                                        <dd>{{ \App\Models\Category::find($category->parent_id)->name }}</dd>
                                    @endif

                                    <dt>Mô tả:</dt>
                                    <dd>{{ $category->description ?? 'Không có mô tả' }}</dd>

                                    @if($category->parent_id == 0)
                                        <dt>Danh mục con:</dt>
                                        <dd>
                                            @php
                                                $childCategories = \App\Models\Category::where('parent_id', $category->id)->get();
                                            @endphp
                                            @if($childCategories->count() > 0)
                                                <ul class="list-unstyled">
                                                    @foreach($childCategories as $child)
                                                        <li>
                                                            <a href="{{ route('admin.category.show-category', $child->id) }}">
                                                                {{ $child->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                Không có danh mục con
                                            @endif
                                        </dd>
                                    @endif
                                </dl>

                                <div class="m-t-lg">
                                    <div class="btn-group">
                                        <a href="{{ route("admin.category.index-category") }}" class="btn btn-white btn-sm">
                                            <i class="fa fa-arrow-left"></i> Quay lại
                                        </a>
                                        <a href="{{ route('admin.category.edit-category', $category->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.category.delete', $category->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection