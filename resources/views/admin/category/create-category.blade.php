@extends('layouts.admin')
@section('title', 'Thêm danh mục')
@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
    <h2>Thêm danh mục</h2>
    <ol class="breadcrumb">
      <li>
      <a href="{{ route('admin.category.index-category') }}">Category</a>
      </li>
      <li class="active">
      <strong>Thêm danh mục</strong>
      </li>
    </ol>
    </div>
  </div>

  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Thêm danh mục</h5>
      </div>
      <div class="ibox-content">
        @if ($errors->any())
      <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
      </ul>
      </div>
      @endif

        @if(session('error'))
      <div class="alert alert-danger">
      {{ session('error') }}
      </div>
      @endif

        <form method="POST" action="{{ route('admin.category.store') }}" class="form-horizontal" novalidate>
        @csrf
        <div class="form-group">
          <label class="col-sm-2 control-label">Kiểu danh mục <span class="text-danger">*</span></label>
          <div class="col-sm-10">
          <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
            <option value="0">Danh mục cha</option>
            @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
        {{ $category->name }}
        </option>
        @endforeach
          </select>
          @error('parent_id')
        <span class="invalid-feedback" style="display: block">{{ $message }}</span>
      @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">Tên danh mục <span class="text-danger">*</span></label>
          <div class="col-sm-10">
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}" placeholder="Enter category name">
          @error('name')
        <span class="invalid-feedback" style="display: block">{{ $message }}</span>
      @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">Mô tả</label>
          <div class="col-sm-10">
          <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
            placeholder="Enter description">{{ old('description') }}</textarea>
          @error('description')
        <span class="invalid-feedback" style="display: block">{{ $message }}</span>
      @enderror
          </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
          <a class="btn btn-white" href="{{ route('admin.category.index-category') }}">Hủy</a>
          <button class="btn btn-primary" type="submit">Lưu</button>
          </div>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection
