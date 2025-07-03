@extends('layouts.client')

@section('title', $post->title)

@section('content')
<section class="module bg-dark-60 blog-page-header" data-background="{{ asset('client/assets/images/blog_bg.jpg') }}">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <h2 class="module-title font-alt">{{ $post->title }}</h2>
        <div class="module-subtitle font-serif">
          Bởi {{ $post->author->name ?? 'Ẩn danh' }} | {{ $post->published_at->format('d/m/Y') }}
        </div>
      </div>
    </div>
  </div>
</section>

<section class="module">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        @include('client.blog.sidebar')
      </div>
      <div class="col-md-9">
        <article class="post">
          @if ($post->image)
          <div class="post-thumbnail mb-3">
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
          </div>
          @endif

          <div class="post-entry" style="font-size: 18px; line-height: 1.8;">
            {!! $post->content !!}
          </div>

          <div class="mt-5">
            <a href="{{ route('client.blog') }}" class="btn btn-primary">Quay lại danh sách bài viết</a>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
@endsection