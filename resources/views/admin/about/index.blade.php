@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Giới thiệu</h5>
                    <div class="ibox-tools">
                        @if($about)
                            <a href="{{ route('admin.about.edit') }}" class="btn btn-info btn-xs">
                                <i class="fa fa-edit"></i> Chỉnh sửa nội dung
                            </a>
                        @else
                            <a href="{{ route('admin.about.create') }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-plus"></i> Thêm mới nội dung
                            </a>
                        @endif
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($about)
                        <h3>{{ $about->title }}</h3>
                        <div class="m-t-md">
                            {!! $about->content !!} <!-- Cho phép HTML -->
                        </div>
                    @else
                        <p class="text-center">Chưa có nội dung giới thiệu. Vui lòng thêm mới.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
