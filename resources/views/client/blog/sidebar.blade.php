<div class="news-sidebar">
    <!-- Categories Widget -->
    <div class="sidebar-widget">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="fa fa-folder"></i> Danh mục
            </h5>
        </div>
        <div class="widget-content">
            <ul class="category-list">
                <li>
                    <a href="#" class="category-link">
                        <i class="fa fa-laptop"></i>
                        <span>Tin công nghệ</span>
                        <span class="category-count">12</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="category-link">
                        <i class="fa fa-gift"></i>
                        <span>Khuyến mãi</span>
                        <span class="category-count">8</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="category-link">
                        <i class="fa fa-lightbulb-o"></i>
                        <span>Thủ thuật</span>
                        <span class="category-count">15</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="category-link">
                        <i class="fa fa-heart"></i>
                        <span>Cảm nhận</span>
                        <span class="category-count">6</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Popular Posts Widget -->
    <div class="sidebar-widget">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="fa fa-fire"></i> Bài viết phổ biến
            </h5>
        </div>
        <div class="widget-content">
            @if($popularPosts->count() > 0)
                <div class="popular-posts">
                    @foreach ($popularPosts as $post)
                        <article class="popular-post">
                            <div class="post-image">
                                <a href="{{ route('client.posts.show', $post->id) }}">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('client/assets/images/default.jpg') }}"
                                         alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="post-info">
                                <h6 class="post-title">
                                    <a href="{{ route('client.posts.show', $post->id) }}">{{ $post->title }}</a>
                                </h6>
                                <div class="post-meta">
                                    <i class="fa fa-calendar"></i>
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Chưa đăng' }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-popular">
                    <i class="fa fa-newspaper-o"></i>
                    <p>Chưa có bài viết phổ biến</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tags Widget -->
    <div class="sidebar-widget">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="fa fa-tags"></i> Tags
            </h5>
        </div>
        <div class="widget-content">
            <div class="tags-cloud">
                <a href="#" class="tag-link">Blog</a>
                <a href="#" class="tag-link">Tin tức</a>
                <a href="#" class="tag-link">Khuyến mãi</a>
                <a href="#" class="tag-link">Thủ thuật</a>
                <a href="#" class="tag-link">Công nghệ</a>
                <a href="#" class="tag-link">Đánh giá</a>
                <a href="#" class="tag-link">Hướng dẫn</a>
                <a href="#" class="tag-link">Sản phẩm</a>
            </div>
        </div>
    </div>

    <!-- Newsletter Widget -->
    <div class="sidebar-widget">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="fa fa-envelope"></i> Đăng ký nhận tin
            </h5>
        </div>
        <div class="widget-content">
            <div class="newsletter-form">
                <p>Nhận tin tức mới nhất qua email</p>
                <form>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Nhập email của bạn...">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-paper-plane"></i> Đăng ký
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.news-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-widget {
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.widget-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 20px;
    color: white;
}

.widget-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.widget-content {
    padding: 15px;
}

/* Categories */
.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 8px;
}

.category-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    font-size: 0.9rem;
}

.category-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(3px);
    text-decoration: none;
}

.category-link i {
    margin-right: 8px;
    width: 16px;
    text-align: center;
}

.category-count {
    background: rgba(255,255,255,0.2);
    padding: 3px 6px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Popular Posts */
.popular-posts {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.popular-post {
    display: flex;
    gap: 10px;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.popular-post:hover {
    background: #f8f9fa;
    transform: translateX(3px);
}

.post-image {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    border-radius: 6px;
    overflow: hidden;
}

.post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-info {
    flex: 1;
    min-width: 0;
}

.post-title {
    margin: 0 0 4px 0;
    font-size: 0.85rem;
    line-height: 1.3;
}

.post-title a {
    color: #2c3e50;
    text-decoration: none;
    font-weight: 600;
}

.post-title a:hover {
    color: #667eea;
}

.post-meta {
    font-size: 0.75rem;
    color: #6c757d;
}

.post-meta i {
    margin-right: 4px;
    color: #667eea;
}

.empty-popular {
    text-align: center;
    padding: 15px;
    color: #6c757d;
}

.empty-popular i {
    font-size: 1.5rem;
    margin-bottom: 8px;
    opacity: 0.5;
}

.empty-popular p {
    font-size: 0.85rem;
    margin: 0;
}

/* Tags */
.tags-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.tag-link {
    display: inline-block;
    padding: 5px 10px;
    background: #f8f9fa;
    color: #667eea;
    text-decoration: none;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.tag-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* Newsletter */
.newsletter-form {
    text-align: center;
}

.newsletter-form p {
    margin-bottom: 12px;
    color: #6c757d;
    font-size: 0.85rem;
}

.newsletter-form .form-control {
    border-radius: 20px;
    border: 2px solid #e9ecef;
    padding: 10px 15px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.newsletter-form .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.newsletter-form .btn {
    border-radius: 20px;
    padding: 10px 15px;
    font-weight: 600;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.newsletter-form .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .news-sidebar {
        position: static;
        margin-top: 20px;
    }
    
    .sidebar-widget {
        margin-bottom: 15px;
    }
    
    .widget-content {
        padding: 12px;
    }
    
    .widget-header {
        padding: 12px 15px;
    }
}
</style>