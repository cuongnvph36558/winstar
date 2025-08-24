@extends('layouts.admin')
@section('title', 'Sửa danh mục')
@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
    <h2>Sửa danh mục</h2>
    <ol class="breadcrumb">
      <li>
      <a href="{{ route('admin.category.index-category') }}">Danh mục</a>
      </li>
      <li class="active">
      <strong>Sửa</strong>
      </li>
    </ol>
    </div>
  </div>

  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Sửa danh mục</h5>
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

        <form method="POST" action="{{ route('admin.category.update-category', $category->id) }}"
        class="form-horizontal" novalidate>
        @csrf
        @method('PUT')
        <div class="form-group">
          <label
          class="col-sm-2 control-label">{{ $category->parent_id == 0 ? 'Danh mục cha con' : 'Danh mục con' }}
          <span class="text-danger">*</span></label>
          <div class="col-sm-10">
          <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
            <option value="" {{ $category->parent_id == 0 ? 'selected' : '' }}>Danh mục cha</option>
            <optgroup label="Danh mục con">
            @foreach($categories as $cat)
            @if($cat->id != $category->id)
          <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
          {{ $cat->name }}
          </option>
          @endif
        @endforeach
            </optgroup>
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
            value="{{ old('name', $category->name) }}" placeholder="Nhập tên danh mục">
          @error('name')
        <span class="invalid-feedback" style="display: block">{{ $message }}</span>
      @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">Mô tả</label>
          <div class="col-sm-10">
          <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
            placeholder="Nhập mô tả">{{ old('description', $category->description) }}</textarea>
          @error('description')
        <span class="invalid-feedback" style="display: block">{{ $message }}</span>
      @enderror
          </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
          <a class="btn btn-white" href="{{ route('admin.category.index-category') }}">Hủy</a>
          <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
          </div>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection
