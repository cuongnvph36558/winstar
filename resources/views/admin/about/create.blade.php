@extends('layouts.admin')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Thêm mới nội dung Giới thiệu</h5>
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

                        <form action="{{ route('admin.about.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="content">Nội dung</label>
                                <textarea name="content" class="form-control" rows="10" required>{{ old('content') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('admin.about.index') }}" class="btn btn-default">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
@endsection


