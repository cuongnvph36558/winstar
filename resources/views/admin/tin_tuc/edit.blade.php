@extends('layouts.admin')

@section('content')
<h1>Chỉnh sửa tin tức</h1>

<form action="{{ route('admin.tin-tuc.update', $tinTuc->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="tieu_de" class="form-label">Tiêu đề</label>
        <input type="text" name="tieu_de" id="tieu_de" value="{{ old('tieu_de', $tinTuc->tieu_de) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="hinh_anh" class="form-label">Hình ảnh hiện tại</label><br>
        @if($tinTuc->hinh_anh)
            <img src="{{ asset('storage/' . $tinTuc->hinh_anh) }}" width="150"><br><br>
        @else
            <p>Chưa có hình ảnh</p>
        @endif
        <input type="file" name="hinh_anh" class="form-control">
    </div>

    <div class="mb-3">
        <label for="noi_dung" class="form-label">Nội dung</label>
        <textarea name="noi_dung" id="noi_dung" class="form-control" rows="6" required>{{ old('noi_dung', $tinTuc->noi_dung) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="trang_thai" class="form-label">Trạng thái</label>
        <select name="trang_thai" class="form-control">
            <option value="1" {{ $tinTuc->trang_thai ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ !$tinTuc->trang_thai ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.tin-tuc.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection
