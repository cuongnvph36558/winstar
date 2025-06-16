@extends('layouts.admin')

@section('content')
<h1>Chi tiết tin tức</h1>

<div class="mb-3">
    <strong>Tiêu đề:</strong> {{ $tinTuc->tieu_de }}
</div>

<div class="mb-3">
    <strong>Nội dung:</strong><br>
    {!! nl2br(e($tinTuc->noi_dung)) !!}
</div>

<div class="mb-3">
    <strong>Hình ảnh:</strong><br>
    @if($tinTuc->hinh_anh)
        <img src="{{ asset('storage/' . $tinTuc->hinh_anh) }}" width="200">
    @endif
</div>

<div class="mb-3">
    <strong>Trạng thái:</strong> {{ $tinTuc->trang_thai ? 'Hiển thị' : 'Ẩn' }}
</div>

<a href="{{ route('admin.tin-tuc.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
