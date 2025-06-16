@extends('layouts.admin')

@section('content')
<h1>Thêm tin tức</h1>
<form action="{{ route('admin.tin-tuc.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Tiêu đề</label>
        <input type="text" name="tieu_de" class="form-control" required>
    </div>
    <div>
        <label>Hình ảnh</label>
        <input type="file" name="hinh_anh" class="form-control">
    </div>
    <div>
        <label>Nội dung</label>
        <textarea name="noi_dung" class="form-control" rows="5" required></textarea>
    </div>
    <div>
        <label>Trạng thái</label>
        <select name="trang_thai" class="form-control">
            <option value="1">Hiển thị</option>
            <option value="0">Ẩn</option>
        </select>
    </div>
    <button class="btn btn-success mt-2">Lưu</button>
</form>
@endsection
