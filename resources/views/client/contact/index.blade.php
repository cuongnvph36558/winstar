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
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: block;
}

.submit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 10px;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
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

.contact-info-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.contact-info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.contact-info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 20px;
}

.contact-info-content h5 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-weight: 600;
}

.contact-info-content p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.alert {
    border-radius: 10px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 20px;
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
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.user-info {
    background: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.user-info h5 {
    color: #1976d2;
    margin-bottom: 10px;
    font-size: 16px;
}

.user-info p {
    margin: 5px 0;
    color: #424242;
    font-size: 14px;
}

/* Contact History Styles */
.contact-history-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    margin-bottom: 30px;
}

.contact-history-section h3 {
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
}

.contact-item {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.contact-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.contact-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.contact-subject {
    font-weight: 600;
    color: #2c3e50;
    font-size: 16px;
}

.contact-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.contact-status.pending {
    background: #fff3cd;
    color: #856404;
}

.contact-status.resolved {
    background: #d4edda;
    color: #155724;
}

.contact-date {
    color: #6c757d;
    font-size: 12px;
    margin-top: 5px;
}

.contact-body {
    padding: 20px;
}

.contact-message {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
}

.contact-message h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 14px;
}

.contact-message p {
    margin: 0;
    color: #495057;
    line-height: 1.6;
    white-space: pre-wrap;
}

.contact-reply {
    background: #e8f5e8;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #28a745;
    margin-top: 15px;
}

.contact-reply h6 {
    color: #155724;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 14px;
}

.contact-reply p {
    margin: 0;
    color: #155724;
    line-height: 1.6;
    white-space: pre-wrap;
}

.no-contacts {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-contacts i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
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
    .contact-hero h1 {
        font-size: 2rem;
    }
    
    .contact-hero p {
        font-size: 1rem;
    }
    
    .contact-content {
        padding: 20px 0;
    }
    
    .contact-main {
        margin-top: -20px;
        padding: 20px;
    }
    
    .contact-form-section,
    .contact-info-section,
    .contact-history-section {
        padding: 20px;
    }
    
    .contact-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
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
            
            <!-- Contact History for Authenticated Users -->
            @if(auth()->check())
            <div class="contact-history-section">
                <h3><i class="fa fa-history"></i> Lịch sử liên hệ của bạn</h3>
                
                @if($userContacts->count() > 0)
                    @foreach($userContacts as $contact)
                    <div class="contact-item">
                        <div class="contact-header">
                            <div>
                                <div class="contact-subject">{{ $contact->subject }}</div>
                                <div class="contact-date">{{ $contact->created_at ? $contact->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                            </div>
                            <span class="contact-status {{ $contact->status }}">
                                @if($contact->status === 'resolved')
                                    <i class="fa fa-check"></i> Đã phản hồi
                                @else
                                    <i class="fa fa-clock-o"></i> Chưa phản hồi
                                @endif
                            </span>
                        </div>
                        <div class="contact-body">
                            <div class="contact-message">
                                <h6><i class="fa fa-user"></i> Tin nhắn của bạn:</h6>
                                <p>{{ $contact->message }}</p>
                            </div>
                            @if($contact->reply)
                            <div class="contact-reply">
                                <h6><i class="fa fa-reply"></i> Phản hồi từ chúng tôi:</h6>
                                <p>{{ $contact->reply }}</p>
                                <small class="text-muted">Phản hồi lúc: {{ $contact->updated_at ? $contact->updated_at->format('d/m/Y H:i') : 'N/A' }}</small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-contacts">
                        <i class="fa fa-inbox"></i>
                        <h4>Chưa có tin nhắn nào</h4>
                        <p>Bạn chưa gửi tin nhắn nào. Hãy gửi tin nhắn đầu tiên của bạn bên dưới!</p>
                    </div>
                @endif
            </div>
            @endif

            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 col-md-7">
                    <div class="contact-form-section">
                        <h3><i class="fa fa-envelope"></i> Gửi tin nhắn cho chúng tôi</h3>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if(auth()->check())
                            <div class="user-info">
                                <h5><i class="fa fa-user"></i> Thông tin của bạn</h5>
                                <p><strong>Tên:</strong> {{ auth()->user()->name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                <strong>Lưu ý:</strong> Bạn có thể gửi tin nhắn mà không cần đăng nhập. 
                                Tuy nhiên, việc đăng nhập sẽ giúp chúng tôi phản hồi nhanh hơn và bạn có thể xem lịch sử liên hệ.
                            </div>
                        @endif
                        
                        <form method="post" action="{{ route('client.contact.store') }}" id="contactForm">
                            @csrf

                            <div class="form-group">
                                <label for="subject" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input class="form-control @error('subject') is-invalid @enderror" 
                                       type="text" id="subject" name="subject" 
                                       placeholder="Nhập tiêu đề tin nhắn..." 
                                       value="{{ old('subject') }}" required>
                                @error('subject')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Nội dung tin nhắn <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          rows="6" id="message" name="message" 
                                          placeholder="Nhập nội dung tin nhắn của bạn (tối thiểu 10 ký tự)..." 
                                          required>{{ old('message') }}</textarea>
                                <small class="form-text text-muted">Nội dung tin nhắn phải có ít nhất 10 ký tự.</small>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button class="submit-btn" type="submit" id="submitBtn">
                                <i class="fa fa-paper-plane"></i> Gửi tin nhắn
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-5">
                    <div class="contact-info-section">
                        <h3><i class="fa fa-info-circle"></i> Thông tin liên hệ</h3>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Địa chỉ</h5>
                                <p>Cổng Ong, Tòa nhà FPT Polytechnic<br>13 Trịnh Văn Bô, Phường Xuân Phương, TP Hà Nội</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Điện thoại</h5>
                                <p>0567899999</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Email</h5>
                                <p>winstar@gmail.com</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Giờ làm việc</h5>
                                <p>Thứ 2 - Thứ 6: 8:00 - 18:00<br>Thứ 7: 8:00 - 12:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#contactForm').on('submit', function(e) {
        var subject = $('#subject').val().trim();
        var message = $('#message').val().trim();
        var isValid = true;
        
        // Reset previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.text-danger').remove();
        
        // Validate subject
        if (subject.length === 0) {
            $('#subject').addClass('is-invalid');
            $('#subject').after('<span class="text-danger">Vui lòng nhập tiêu đề tin nhắn.</span>');
            isValid = false;
        } else if (subject.length > 255) {
            $('#subject').addClass('is-invalid');
            $('#subject').after('<span class="text-danger">Tiêu đề không được vượt quá 255 ký tự.</span>');
            isValid = false;
        }
        
        // Validate message
        if (message.length === 0) {
            $('#message').addClass('is-invalid');
            $('#message').after('<span class="text-danger">Vui lòng nhập nội dung tin nhắn.</span>');
            isValid = false;
        } else if (message.length < 10) {
            $('#message').addClass('is-invalid');
            $('#message').after('<span class="text-danger">Nội dung tin nhắn phải có ít nhất 10 ký tự.</span>');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang gửi...');
    });
    
    // Auto-resize textarea
    $('#message').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Character counter for message
    $('#message').on('input', function() {
        var length = $(this).val().length;
        var minLength = 10;
        
        if (length < minLength) {
            $(this).next('.form-text').html('Nội dung tin nhắn phải có ít nhất ' + minLength + ' ký tự. (Hiện tại: ' + length + ' ký tự)');
        } else {
            $(this).next('.form-text').html('Nội dung tin nhắn hợp lệ. (' + length + ' ký tự)');
        }
    });

    // Initialize contact notifications
    @if(auth()->check())
    window.isAuthenticated = true;
    window.contactCheckUrl = '{{ route("client.contact.check-replies") }}';
    @endif
});
</script>

<!-- Include contact notifications script -->
<script src="{{ asset('client/assets/js/contact-notifications.js') }}"></script>

<style>
.new-reply-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    z-index: 9999;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    max-width: 350px;
}

.new-reply-notification.show {
    transform: translateX(0);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-content i.fa-bell {
    font-size: 18px;
    animation: bell-ring 1s ease-in-out;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0;
    margin-left: auto;
    font-size: 16px;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.notification-close:hover {
    opacity: 1;
}

@keyframes bell-ring {
    0%, 100% { transform: rotate(0deg); }
    20%, 60% { transform: rotate(15deg); }
    40%, 80% { transform: rotate(-15deg); }
}

@media (max-width: 768px) {
    .new-reply-notification {
        top: 10px;
        right: 10px;
        left: 10px;
        max-width: none;
        transform: translateY(-100px);
    }
    
    .new-reply-notification.show {
        transform: translateY(0);
    }
}
</style>
@endpush
@endsection