@extends('layouts.admin')

@section('title', 'Sửa Dịch vụ')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Sửa Dịch vụ</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.services.index') }}">Dịch vụ</a></li>
            <li class="active"><strong>Sửa</strong></li>
        </ol>
    </div>
    <div class="col-lg-2 text-right" style="margin-top: 30px;">
        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Chỉnh sửa thông tin Dịch vụ</h5>
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

                    <form method="POST" action="{{ route('admin.services.update', $service->id) }}">
                        @csrf
                        @method('PUT')

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
                                        border-color: #007bff !important;
                                        background: #eaf4ff !important;
                                        box-shadow: 0 0 0 2px #007bff33;
                                    }
                                    .icon-option {
                                        transition: border 0.2s, background 0.2s;
                                        min-width: 180px;
                                        display: flex;
                                        align-items: center;
                                        gap: 10px;
                                        font-size: 1.1rem;
                                        cursor: pointer;
                                        justify-content: flex-start;
                                        padding-left: 12px;
                                    }
                                    .icon-option .icon-img {
                                        font-size: 2rem;
                                        min-width: 32px;
                                        color: #222;
                                        display: inline-block;
                                        vertical-align: middle;
                                    }
                                    .icon-option .icon-label {
                                        font-size: 1.15rem;
                                        color: #222;
                                        text-align: left;
                                        vertical-align: middle;
                                        font-weight: 500;
                                        letter-spacing: 0.2px;
                                    }
                                </style>
                                <div class="form-group mb-0">
                                    <div class="d-flex flex-column" id="icon-picker">
                                        @foreach($iconList as $icon)
                                            <div class="icon-option m-2 {{ old('icon', $service->icon) == $icon ? 'selected' : '' }}"
                                                 data-icon="{{ $icon }}"
                                                 style="border:1px solid #ddd; border-radius:6px; padding:10px;">
                                                <span class="icon-img"><span class="{{ $icon }}"></span></span>
                                                <span class="icon-label">{{ $icon }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="icon" id="icon-input" value="{{ old('icon', $service->icon) }}">
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

                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $service->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="order">Thứ tự hiển thị</label>
                            <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $service->order) }}">
                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-default">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
