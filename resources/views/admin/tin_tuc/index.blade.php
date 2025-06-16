@extends('layouts.admin')

@section('content')
<h1>Danh sách tin tức</h1>
<a href="{{ route('admin.tin-tuc.create') }}" class="btn btn-success">Thêm mới</a>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Hình ảnh</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tinTucs as $tt)
        <tr>
            <td>{{ $tt->id }}</td>
            <td>{{ $tt->tieu_de }}</td>
            <td>
                @if($tt->hinh_anh)
                <img src="{{ asset('storage/' . $tt->hinh_anh) }}" width="80">
                @endif
            </td>
            <td>{{ \Str::limit(strip_tags($tt->noi_dung), 100) }}</td> {{-- Hiển thị rút gọn 100 ký tự --}}
            <td>{{ $tt->trang_thai ? 'Hiển thị' : 'Ẩn' }}</td>
            <td>
                <a href="{{ route('admin.tin-tuc.edit', $tt) }}" class="btn btn-primary btn-sm">Sửa</a>
                <form action="{{ route('admin.tin-tuc.destroy', $tt) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn?')">Xoá</button>
                </form>
                <form action="{{ route('admin.tin-tuc.toggle', $tt) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Đổi trạng thái</button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection