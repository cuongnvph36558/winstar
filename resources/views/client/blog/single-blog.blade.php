@extends('layouts.client')

@section('title', $post->title)

@section('styles')
<style>
.article-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    min-height: 40vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.article-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.article-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 20px;
}

.article-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out;
    line-height: 1.3;
}

.article-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-bottom: 0.5rem;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.article-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
    opacity: 0.9;
    font-size: 0.95rem;
}

.article-meta i {
    color: #ffd700;
}

.article-content {
    padding: 40px 0;
    background: #f8f9fa;
}

.article-content .container {
    max-width: 1200px;
}

.article-main {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: -30px;
    position: relative;
    z-index: 3;
    animation: fadeInUp 1s ease-out 0.4s both;
}

.article-image {
    margin: -30px -30px 25px -30px;
    border-radius: 15px 15px 0 0;
    overflow: hidden;
    height: 350px;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.article-body {
    font-size: 1rem;
    line-height: 1.7;
    color: #333;
}

.article-body h2, .article-body h3, .article-body h4 {
    color: #2c3e50;
    margin-top: 1.5rem;
    margin-bottom: 0.8rem;
    font-weight: 700;
}

.article-body h2 {
    font-size: 1.6rem;
}

.article-body h3 {
    font-size: 1.3rem;
}

.article-body h4 {
    font-size: 1.1rem;
}

.article-body p {
    margin-bottom: 1.2rem;
}

.article-body ul, .article-body ol {
    margin-bottom: 1.2rem;
    padding-left: 1.8rem;
}

.article-body li {
    margin-bottom: 0.4rem;
}

.article-body blockquote {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    margin: 1.5rem 0;
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: #495057;
}

.article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.2rem 0;
}

.article-actions {
    margin-top: 30px;
    padding-top: 25px;
    border-top: 2px solid #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    color: white;
    text-decoration: none;
}

.share-buttons {
    display: flex;
    gap: 8px;
}

.share-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.share-btn.facebook {
    background: #3b5998;
}

.share-btn.twitter {
    background: #1da1f2;
}

.share-btn.linkedin {
    background: #0077b5;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    color: white;
    text-decoration: none;
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
    .article-hero {
        min-height: 30vh;
    }
    
    .article-hero h1 {
        font-size: 1.8rem;
    }
    
    .article-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .article-content {
        padding: 20px 0;
    }
    
    .article-main {
        padding: 20px;
        margin-top: -20px;
    }
    
    .article-image {
        margin: -20px -20px 20px -20px;
        height: 200px;
    }
    
    .article-body {
        font-size: 0.95rem;
    }
    
    .article-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .share-buttons {
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="article-hero">
    <div class="article-hero-content">
        <h1>{{ $post->title }}</h1>
        <div class="article-meta">
            <span>
                <i class="fa fa-calendar"></i>
                {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Chưa đăng' }}
            </span>
            <span>
                <i class="fa fa-clock-o"></i>
                {{ $post->published_at ? $post->published_at->format('H:i') : '' }}
            </span>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="article-content">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('client.blog.sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <article class="article-main">
                    @if ($post->image)
                        <div class="article-image">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                 style="width: 100%; height: 100%; object-fit: contain; background: #f8f9fa;">
                        </div>
                    @endif

                    <div class="article-body">
                        {!! $post->content !!}
                    </div>

                    <div class="article-actions">
                        <a href="{{ route('client.blog') }}" class="back-button">
                            <i class="fa fa-arrow-left"></i>
                            Quay lại danh sách bài viết
                        </a>
                        
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" class="share-btn facebook" title="Chia sẻ trên Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                               target="_blank" class="share-btn twitter" title="Chia sẻ trên Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                               target="_blank" class="share-btn linkedin" title="Chia sẻ trên LinkedIn">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection