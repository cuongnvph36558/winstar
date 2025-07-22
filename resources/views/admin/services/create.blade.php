@extends('layouts.admin')

@section('title', 'Thêm Dịch vụ')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Thêm Dịch vụ mới</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.services.index') }}">Dịch vụ</a></li>
            <li class="active"><strong>Thêm mới</strong></li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin Dịch vụ</h5>
                </div>
                <div class="ibox-content">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.services.store') }}" method="POST">
                        @csrf
                        @php
                            $iconList = [
                                'icon-basket', 'icon-bike', 'icon-tools', 'icon-genius', 'icon-mobile', 'icon-lifesaver',
                            ];
                        @endphp
                        <!-- Section chọn icon -->
                        <div class="ibox mb-4">
                            <div class="ibox-title bg-light">
                                <h5 class="mb-0">Chọn Icon cho dịch vụ</h5>
                            </div>
                            <div class="ibox-content">
                                <style>
                                    .icon-option.selected {
                                        border: 2px solid #007bff !important;
                                        background: #eaf4ff !important;
                                        box-shadow: 0 0 0 1.5px #007bff33;
                                    }
                                    .icon-option {
                                        transition: border 0.2s, background 0.2s;
                                        min-width: 0;
                                        display: flex;
                                        align-items: center;
                                        justify-content: flex-start;
                                        font-size: 1.1rem;
                                        cursor: pointer;
                                        padding: 6px 14px;
                                        border: 1.5px solid #ddd;
                                        border-radius: 24px;
                                        margin-bottom: 8px;
                                        width: fit-content;
                                        gap: 12px;
                                    }
                                    .icon-option .icon-img {
                                        font-size: 2.2rem;
                                        min-width: 36px;
                                        color: #222;
                                        display: inline-block;
                                        vertical-align: middle;
                                        font-family: 'et-line', sans-serif !important;
                                    }
                                    .icon-option .icon-label {
                                        font-size: 1.25rem;
                                        color: #222;
                                        text-align: left;
                                        vertical-align: middle;
                                        font-weight: 600;
                                        letter-spacing: 0.2px;
                                    }
                                </style>
                                <div class="form-group mb-0">
                                    <div class="d-flex flex-wrap" id="icon-picker">
                                        @foreach($iconList as $icon)
                                            <div class="icon-option {{ old('icon') == $icon ? 'selected' : '' }}"
                                                 data-icon="{{ $icon }}">
                                                <span class="icon-img {{ $icon }}"></span>
                                                <span class="icon-label">{{ $icon }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="icon" id="icon-input" value="{{ old('icon') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="order">Thứ tự hiển thị</label>
                            <input type="number" name="order" id="order" class="form-control" value="{{ old('order', 0) }}">
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Lưu Dịch vụ
                            </button>
                            <a href="{{ route('admin.services.index') }}" class="btn btn-default">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.querySelectorAll('.icon-option').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.icon-option').forEach(e => e.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('icon-input').value = this.getAttribute('data-icon');
        });
    });
</script>
@endpush
@endsection
