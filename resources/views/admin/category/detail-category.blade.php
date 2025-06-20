@extends('layouts.admin')
@section('title', 'Chi tiết danh mục')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Detail Category</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li>
                <a href="{{ route('admin.category.index-category') }}">Category</a>
            </li>
            <li class="active">
                <strong>Detail</strong>
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
                                <dt>Category Type:</dt>
                                <dd>{{ $category->parent_id == 0 ? 'Parent Category' : 'Child Category' }}</dd>
                                
                                @if($category->parent_id != 0)
                                    <dt>Parent Category:</dt>
                                    <dd>{{ \App\Models\Category::find($category->parent_id)->name }}</dd>
                                @endif
                                
                                <dt>Description:</dt>
                                <dd>{{ $category->description ?? 'No description' }}</dd>

                                @if($category->parent_id == 0)
                                    <dt>Child Categories:</dt>
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
                                            No child categories
                                        @endif
                                    </dd>
                                @endif
                            </dl>

                            <div class="m-t-lg">
                                <div class="btn-group">
                                    <a href="{{ route("admin.category.index-category") }}" class="btn btn-white btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                    <a href="{{ route('admin.category.edit-category', $category->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.category.delete', $category->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fa fa-trash"></i> Delete
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
