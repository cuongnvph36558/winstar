@extends('layouts.client')
@section('title', 'Dịch vụ của chúng tôi')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8 col-md-offset-2 text-center">
            <h2 class="font-alt mb-3">Dịch vụ của chúng tôi</h2>
            <p class="lead text-muted">Cam kết mang đến những dịch vụ chất lượng cao nhất cho khách hàng</p>
        </div>
    </div>
    <div class="row">
        @forelse($services as $service)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="{{ $service->icon }} fa-3x text-primary"></span>
                        </div>
                        <h5 class="card-title font-alt">{{ $service->title }}</h5>
                        <p class="card-text text-muted">{{ $service->description }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p>Chưa có dịch vụ nào được cập nhật.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection 
