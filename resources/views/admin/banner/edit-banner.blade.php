@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chỉnh sửa Banner</h5>
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

                    <form method="POST" action="{{ route('admin.banner.update-banner', $banner->id) }}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tiêu đề <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Liên kết</label>
                            <div class="col-sm-10">
                                <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Hình ảnh</label>
                            <div class="col-sm-10">
                                <input type="file" name="image_url" class="form-control">
                                @if($banner->image_url)
                                    <div class="m-t-sm">
                                        <img src="{{ asset('storage/' . $banner->image_url) }}" 
                                             alt="{{ $banner->title }}"
                                             class="img-thumbnail"
                                             style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Trạng thái</label>
                            <div class="col-sm-10">
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status', $banner->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('status', $banner->status) == '0' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ngày bắt đầu</label>
                            <div class="col-sm-10">
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $banner->start_date) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ngày kết thúc</label>
                            <div class="col-sm-10">
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $banner->end_date) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="{{ route('admin.banner.index-banner') }}" class="btn btn-white">Hủy</a>
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
