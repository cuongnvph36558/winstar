@extends('layouts.client')

@section('title', 'Liên hệ')

@section('styles')
<style>
.contact-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    min-height: 40vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.contact-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 20px;
}

.contact-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out;
}

.contact-hero p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.contact-content {
    padding: 40px 0;
    background: #f8f9fa;
}

.contact-content .container {
    max-width: 1200px;
}

.contact-main {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: -30px;
    position: relative;
    z-index: 3;
    animation: fadeInUp 1s ease-out 0.4s both;
}

.contact-form-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.contact-form-section h3 {
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
}

.form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.submit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    color: white;
}

.contact-info-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    height: 100%;
}

.contact-info-section h3 {
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(5px);
}

.info-item:hover .info-icon {
    background: rgba(255,255,255,0.2);
    color: white;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.info-content h5 {
    margin: 0 0 5px 0;
    font-weight: 600;
    font-size: 1rem;
}

.info-content p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.8;
}

.business-hours {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}

.business-hours h4 {
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 15px;
    text-align: center;
}

.hours-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.hours-list li {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.9rem;
}

.hours-list li:last-child {
    border-bottom: none;
}

.hours-list .day {
    font-weight: 600;
    color: #2c3e50;
}

.hours-list .time {
    color: #667eea;
    font-weight: 500;
}

.alert {
    border-radius: 10px;
    border: none;
    padding: 15px 20px;
    margin-top: 20px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.text-danger {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 5px;
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
    .contact-hero {
        min-height: 30vh;
    }
    
    .contact-hero h1 {
        font-size: 2.2rem;
    }
    
    .contact-hero p {
        font-size: 1rem;
    }
    
    .contact-content {
        padding: 20px 0;
    }
    
    .contact-main {
        padding: 20px;
        margin-top: -20px;
    }
    
    .contact-form-section,
    .contact-info-section {
        padding: 20px;
        margin-bottom: 20px;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="contact-hero-content">
        <h1>Liên hệ với chúng tôi</h1>
        <p>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
    </div>
</section>

<!-- Content Section -->
<section class="contact-content">
    <div class="container">
        <div class="contact-main">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 col-md-7">
                    <div class="contact-form-section">
                        <h3><i class="fa fa-envelope"></i> Gửi tin nhắn cho chúng tôi</h3>
                        
                        <form method="post" action="{{ route('client.contact.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="subject" class="form-label">Tiêu đề</label>
                                <input class="form-control" type="text" id="subject" name="subject" 
                                       placeholder="Nhập tiêu đề tin nhắn..." required>
                                @error('subject')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Nội dung tin nhắn</label>
                                <textarea class="form-control" rows="6" id="message" name="message" 
                                          placeholder="Nhập nội dung tin nhắn của bạn..." required></textarea>
                                @error('message')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <button class="submit-btn" type="submit">
                                <i class="fa fa-paper-plane"></i> Gửi tin nhắn
                            </button>

                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-5">
                    <div class="contact-info-section">
                        <h3><i class="fa fa-info-circle"></i> Thông tin liên hệ</h3>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="info-content">
                                <h5>Địa chỉ</h5>
                                <p>123 Đường ABC, Quận 1, TP.HCM</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h5>Điện thoại</h5>
                                <p>+84 123 456 789</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h5>Email</h5>
                                <p>info@winstar.com</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="info-content">
                                <h5>Giờ làm việc</h5>
                                <p>Thứ 2 - Thứ 6: 8:00 - 18:00</p>
                            </div>
                        </div>

                        <div class="business-hours">
                            <h4><i class="fa fa-calendar"></i> Lịch làm việc</h4>
                            <ul class="hours-list">
                                <li>
                                    <span class="day">Thứ 2 - Thứ 6:</span>
                                    <span class="time">8:00 - 18:00</span>
                                </li>
                                <li>
                                    <span class="day">Thứ 7:</span>
                                    <span class="time">8:00 - 12:00</span>
                                </li>
                                <li>
                                    <span class="day">Chủ nhật:</span>
                                    <span class="time">Nghỉ</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section id="map-section" style="height: 400px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; color: #6c757d;">
        <i class="fa fa-map" style="font-size: 3rem; margin-bottom: 1rem;"></i>
        <h4>Bản đồ</h4>
        <p>Bản đồ sẽ được hiển thị ở đây</p>
    </div>
</section>
@endsection