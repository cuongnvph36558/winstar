@extends('layouts.client')

@section('title', 'Giới thiệu')

@section('styles')
<style>
.about-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.about-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 20px;
}

.about-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out;
}

.about-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.about-content {
    padding: 80px 0;
    background: #f8f9fa;
}

.about-content .container {
    max-width: 1000px;
}

.about-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 60px;
    margin-top: -50px;
    position: relative;
    z-index: 3;
    animation: fadeInUp 1s ease-out 0.4s both;
}

.about-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
}

.about-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
}

.about-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
    text-align: justify;
}

.about-text h2, .about-text h3 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.about-text p {
    margin-bottom: 1.5rem;
}

.about-text ul, .about-text ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.about-text li {
    margin-bottom: 0.5rem;
}

.empty-content {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-content i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-content h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #495057;
}

.empty-content p {
    font-size: 1.1rem;
    max-width: 500px;
    margin: 0 auto;
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
    .about-hero h1 {
        font-size: 2.5rem;
    }
    
    .about-hero p {
        font-size: 1rem;
    }
    
    .about-card {
        padding: 40px 20px;
        margin-top: -30px;
    }
    
    .about-title {
        font-size: 2rem;
    }
    
    .about-text {
        font-size: 1rem;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="about-hero">
    <div class="about-hero-content">
        <h1>Giới thiệu</h1>
        <p>Khám phá câu chuyện và sứ mệnh của chúng tôi</p>
    </div>
</section>

<!-- Content Section -->
<section class="about-content">
    <div class="container">
        <div class="about-card">
            @if($about)
                <h2 class="about-title">{{ $about->title }}</h2>
                <div class="about-text">
                    {!! $about->content !!}
                </div>
            @else
                <div class="empty-content">
                    <i class="fas fa-info-circle"></i>
                    <h3>Nội dung đang được cập nhật</h3>
                    <p>Hiện tại chưa có nội dung giới thiệu. Vui lòng quay lại sau để xem thông tin mới nhất về chúng tôi.</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
