@extends('layouts.client')

@section('title', 'Tin tức')

@section('styles')
<style>
.news-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    min-height: 40vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.news-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.news-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 20px;
}

.news-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out;
}

.news-hero p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.news-content {
    padding: 40px 0;
    background: #f8f9fa;
}

.news-content .container {
    max-width: 1200px;
}

.news-main {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: -30px;
    position: relative;
    z-index: 3;
    animation: fadeInUp 1s ease-out 0.4s both;
}

.post-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 25px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
}

.post-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.post-thumbnail {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.post-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.post-card:hover .post-thumbnail img {
    transform: scale(1.05);
}

.post-content {
    padding: 20px;
}

.post-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 12px;
    line-height: 1.4;
}

.post-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-title a:hover {
    color: #667eea;
}

.post-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 0.85rem;
    color: #6c757d;
}

.post-meta i {
    color: #667eea;
    margin-right: 4px;
}

.post-excerpt {
    color: #555;
    line-height: 1.5;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.read-more:hover {
    color: #764ba2;
    transform: translateX(3px);
}

.read-more i {
    transition: transform 0.3s ease;
}

.read-more:hover i {
    transform: translateX(2px);
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: #495057;
}

.empty-state p {
    font-size: 1rem;
    max-width: 400px;
    margin: 0 auto;
}

.pagination-wrapper {
    margin-top: 30px;
    text-align: center;
}

.pagination {
    display: inline-flex;
    gap: 8px;
    align-items: center;
}

.pagination .page-link {
    border: none;
    background: #f8f9fa;
    color: #667eea;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.9rem;
}

.pagination .page-link:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.pagination .active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .news-hero {
        min-height: 30vh;
    }
    
    .news-hero h1 {
        font-size: 2.2rem;
    }
    
    .news-hero p {
        font-size: 1rem;
    }
    
    .news-content {
        padding: 20px 0;
    }
    
    .news-main {
        padding: 20px;
        margin-top: -20px;
    }
    
    .post-content {
        padding: 15px;
    }
    
    .post-title {
        font-size: 1.1rem;
    }
    
    .post-thumbnail {
        height: 180px;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="news-hero">
    <div class="news-hero-content">
        <h1>Tin tức</h1>
        <p>Khám phá bài viết mới nhất từ chúng tôi</p>
    </div>
</section>

<!-- Content Section -->
<section class="news-content">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('client.blog.sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="news-main">
                    @if($posts->count() > 0)
                        <div class="row">
                            @foreach($posts as $post)
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <article class="post-card">
                                        <div class="post-thumbnail">
                                            <a href="{{ route('client.posts.show', $post->id) }}">
                                                <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('client/assets/images/default.jpg') }}" 
                                                     alt="{{ $post->title }}">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <h3 class="post-title">
                                                <a href="{{ route('client.posts.show', $post->id) }}">{{ $post->title }}</a>
                                            </h3>
                                            <div class="post-meta">
                                                <span><i class="fa fa-user"></i> {{ $post->author->name ?? 'Ẩn danh' }}</span>
                                                <span><i class="fa fa-calendar"></i> {{ $post->published_at->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="post-excerpt">
                                                {{ Str::limit(strip_tags($post->content), 100) }}
                                            </div>
                                            <a href="{{ route('client.posts.show', $post->id) }}" class="read-more">
                                                Xem thêm <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fa fa-newspaper-o"></i>
                            <h3>Chưa có bài viết nào</h3>
                            <p>Hiện tại chưa có bài viết nào được đăng. Vui lòng quay lại sau!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
