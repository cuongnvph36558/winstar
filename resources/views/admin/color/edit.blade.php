@extends('layouts.admin')
@section('title', 'Edit Color')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10"><h2>Edit Color</h2></div>
</div>

<div class="wrapper wrapper-content">
    <form action="{{ route('admin.color.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Color Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $color->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Color</button>
        <a href="{{ route('admin.color.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
