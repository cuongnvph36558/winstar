@extends('layouts.admin')
@section('title', 'Dung lượng lưu trữ')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Storage List</h2>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="{{ route('admin.storage.index') }}">Storage</a></li>
        </ol>
    </div>
    <div class="col-lg-2 text-right" style="margin-top:30px">
        <a href="{{ route('admin.storage.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Storage
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="ibox-content">

        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>capacity</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($storages as $storage)
                    <tr>
                        <td>{{ $storage->id }}</td>
                        <td>{{ $storage->capacity }}</td>
                        <td>{{ $storage->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $storage->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.storage.edit', $storage->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.storage.delete', $storage->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Bạn có chắc chắn muốn xóa chứ???')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection