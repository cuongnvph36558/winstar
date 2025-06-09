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
                    <a href="{{ route('admin.category.index') }}">Category</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight ecommerce">

        <div class="ibox-content m-b-sm border-bottom">
            <form action="{{ route('admin.category.index') }}" method="get" class="row">
                @csrf
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="name">Category Name</label>
                        <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Category Name"
                            class="form-control" onkeyup="this.form.submit()">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="parent_id">Type Category</label>
                        <select name="parent_id" id="parent_id" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>Parent</option>
                            <option value="1" {{ request('parent_id') === '1' ? 'selected' : '' }}>Child</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">

                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
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
                                            <button class="btn-white btn btn-xs">View</button>
                                            <button class="btn-white btn btn-xs">Edit</button>
                                            <button class="btn-white btn btn-xs">Delete</button>
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