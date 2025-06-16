@extends('layouts.admin')

@section('title', 'Màu sắc sản phẩm')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Color List</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="{{ route('admin.color.index') }}">Color</a></li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.color.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Color
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
            <form action="{{ route('admin.color.index') }}" method="get" class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="name">Color Name</label>
                        <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Color Name"
                            class="form-control" onkeyup="this.form.submit()">
                    </div>
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
                                    <th>STT</th>
                                    <th data-toggle="true">Color Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="text-right" data-sort-ignore="true">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($colors as $color)
                                <tr>
                                    <td>{{ $color->id }}</td>
                                    <td>{{ $color->name }}</td>
                                    <td>{{ $color->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $color->updated_at->format('d/m/Y') }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.color.edit', $color->id) }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.color.delete', $color->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Bạn có chắc muốn xóa chứ???')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
