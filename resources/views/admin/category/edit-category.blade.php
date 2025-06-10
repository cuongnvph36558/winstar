@extends('layouts.admin')
@section('title', 'Sửa danh mục')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>Edit Category</h2>
    <ol class="breadcrumb">
      <li>
        <a href="{{ route('admin.category.index-category') }}">Category</a>
      </li>
      <li class="active">
        <strong>Edit</strong>
      </li>
    </ol>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Edit Category</h5>
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

          <form method="POST" action="{{ route('admin.category.update-category', $category->id) }}" class="form-horizontal" novalidate>
            @csrf
            @method('PUT')
            <div class="form-group">
              <label class="col-sm-2 control-label">Category Type <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                  <option value="0" {{ $category->parent_id == 0 ? 'selected' : '' }}>Parent Category</option>
                  <optgroup label="Child Category">
                    @foreach($categories as $cat)
                      @if($cat->id != $category->id)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
              <label class="col-sm-2 control-label">Name Category <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" placeholder="Enter category name">
                @error('name')
                  <span class="invalid-feedback" style="display: block">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Enter description">{{ old('description', $category->description) }}</textarea>
                @error('description')
                  <span class="invalid-feedback" style="display: block">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <a class="btn btn-white" href="{{ route('admin.category.index-category') }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
