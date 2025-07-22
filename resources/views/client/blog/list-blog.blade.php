@extends('layouts.client')

@section('title', 'Tin tức')

@section('content')
<section class="module bg-dark-60 blog-page-header" data-background="{{ asset('client/assets/images/blog_bg.jpg') }}">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <h2 class="module-title font-alt">Tin tức</h2>
        <div class="module-subtitle font-serif">Khám phá bài viết mới nhất từ chúng tôi.</div>
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
        @if($posts->count() > 0)
          @foreach($posts as $post)
        <div class="post">
          <div class="post-thumbnail mb-3">
            <a href="{{ route('client.posts.show', $post->id) }}">
              <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('client/assets/images/default.jpg') }}" alt="{{ $post->title }}">
            </a>
          </div>
          <div class="post-header font-alt">
            <h2 class="post-title">
              <a href="{{ route('client.posts.show', $post->id) }}">{{ $post->title }}</a>
            </h2>
            <div class="post-meta">
              Bởi <a href="#">{{ $post->author->name ?? 'Ẩn danh' }}</a> | {{ $post->published_at->format('d/m/Y') }}
            </div>
          </div>
          <div class="post-entry">
            <p>{{ Str::limit(strip_tags($post->content), 150) }}</p>
          </div>
          <div class="post-more">
            <a class="more-link" href="{{ route('client.posts.show', $post->id) }}">Xem thêm</a>
          </div>
        </div>
        <hr>
          @endforeach

          <div class="pagination font-alt">
            {{ $posts->links() }}
          </div>
        @else
          <div class="alert alert-info">
            <h4>Chưa có bài viết nào</h4>
            <p>Hiện tại chưa có bài viết nào được đăng. Vui lòng quay lại sau!</p>
            <p><strong>Debug info:</strong></p>
            <ul>
              <li>Posts count: {{ $posts->count() }}</li>
              <li>Popular posts count: {{ $popularPosts->count() }}</li>
            </ul>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
