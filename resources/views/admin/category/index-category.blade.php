@extends('layouts.admin')

@section('title', 'Danh mục')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Category list</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="{{ route('admin.category.index-category') }}">Category</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.category.restore-category') }}" class="btn btn-warning">
                    <i class="fa fa-recycle"></i> Restore Categories
                </a>
            </div>
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.category.create-category') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Category
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

        <div class="ibox-content m-b-sm border-bottom">
            <form action="{{ route('admin.category.index-category') }}" method="get" class="row">
                @csrf
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="name">Category Name</label>
                        <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Category Name"
                            class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="parent_id">Type Category</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="">All</option>
                            <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>Parent</option>
                            <option value="1" {{ request('parent_id') === '1' ? 'selected' : '' }}>Child</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 text-right">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">

                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10">
                            <thead>
                                <tr>
                                    <th data-toggle="true">Category Name</th>
                                    <th data-hide="phone">Type Category</th>
                                    <th class="text-right" data-sort-ignore="true">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        {{ $category->name }}
                                    </td>
                                    <td>
                                        {{ $category->parent_id == 0 ? 'Parent' : 'Child' }}
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.category.show-category', $category->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View</a>
                                            <a href="{{ route('admin.category.edit-category', $category->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                            <form action="{{ route('admin.category.delete', $category->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this category?')"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <ul class="pagination float-right"></ul>
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