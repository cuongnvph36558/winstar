<div class="widget">
  <h5 class="widget-title font-alt">Danh mục</h5>
  <ul class="icon-list">
    <li><a href="#">Tin công nghệ</a></li>
    <li><a href="#">Khuyến mãi</a></li>
    <li><a href="#">Thủ thuật</a></li>
    <li><a href="#">Cảm nhận</a></li>
  </ul>
</div>
<div class="widget">
  <h5 class="widget-title font-alt">Bài viết phổ biến</h5>
  <ul class="widget-posts">
    @foreach ($popularPosts as $post)
    <li class="clearfix">
      <div class="widget-posts-image">
      <a href="{{ route('admin.posts.edit', $post->id) }}">
        <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('client/assets/images/default.jpg') }}"
        alt="Post Thumbnail" style="width: 60px; height: 60px; object-fit: cover;" />
      </a>
      </div>
      <div class="widget-posts-body">
      <div class="widget-posts-title">
        <a href="{{ route('admin.posts.edit', $post->id) }}">{{ $post->title }}</a>
      </div>
      <div class="widget-posts-meta">{{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Chưa đăng' }}
      </div>
      </div>
    </li>
  @endforeach
  </ul>
</div>

<div class="widget">
  <h5 class="widget-title font-alt">Tags</h5>
  <div class="tags font-serif">
    <a href="#">Blog</a>
    <a href="#">Tin tức</a>
    <a href="#">Khuyến mãi</a>
    <a href="#">Thủ thuật</a>
  </div>
</div>