@extends('layouts.client')

@section('title', 'Giới thiệu')

@section('content')
<section class="module bg-dark-60 about-page-header" data-background="{{ asset('client/assets/images/about_bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h2 class="module-title font-alt">Giới thiệu</h2>
            </div>
        </div>
    </div>
</section>

<section class="module">
    <div class="container">
        @if($about)
            <h2 class="text-center" style="font-size: 32px; font-weight: bold;">
                {{ $about->title }}
            </h2>
            <div class="m-t-md" style="font-size: 18px; line-height: 1.8;">
                {!! $about->content !!}
            </div>
        @else
            <p class="text-center">Hiện tại chưa có nội dung giới thiệu.</p>
        @endif
    </div>
</section>

@endsection
