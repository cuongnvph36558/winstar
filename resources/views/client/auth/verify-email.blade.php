@extends('layouts.client')

@section('title', 'Xác nhận email - Winstar')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Xác nhận email</h2>
            <p>Vui lòng nhập mã xác nhận đã được gửi đến email của bạn</p>
            @if(session('success') && str_contains(session('success'), 'Google'))
                <div class="alert alert-info mt-3">
                    <i class="fa fa-info-circle"></i>
                    <strong>Lưu ý:</strong> Bạn đã đăng ký bằng Google. Vui lòng kiểm tra email để hoàn tất quá trình đăng ký.
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verify.email.post') }}" class="verification-form">
            @csrf
            
            <div class="form-group">
                <label for="verification_code">Mã xác nhận</label>
                <input 
                    type="text" 
                    id="verification_code"
                    name="verification_code" 
                    class="form-control @error('verification_code') is-invalid @enderror" 
                    placeholder="Nhập mã 6 số"
                    maxlength="6"
                    required
                    autocomplete="off"
                />
                @error('verification_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Xác nhận
                </button>
            </div>

            <div class="form-group text-center">
                <p>Chưa nhận được mã? <a href="#" id="resend-link" class="resend-link">Gửi lại mã</a></p>
                <div id="resend-message" class="alert" style="display: none; margin-top: 10px;"></div>

            </div>
        </form>

        <div class="auth-footer">
            <p>Quay lại <a href="{{ route('login') }}">đăng nhập</a></p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.auth-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 40px;
    width: 100%;
    max-width: 400px;
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h2 {
    color: #333;
    margin-bottom: 10px;
    font-size: 24px;
}

.auth-header p {
    color: #666;
    font-size: 14px;
}

.verification-form {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.resend-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.resend-link:hover {
    text-decoration: underline;
}

.resend-link:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

#resend-message {
    transition: all 0.3s ease;
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e1e5e9;
}

.auth-footer a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.auth-footer a:hover {
    text-decoration: underline;
}

.alert {
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('verification_code');
    
    // Chỉ cho phép nhập số
    input.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Tự động focus vào input
    input.focus();
    
    // Xử lý gửi lại mã xác nhận
    const resendLink = document.getElementById('resend-link');
    const resendMessage = document.getElementById('resend-message');
    
    resendLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Disable link và hiển thị loading
        resendLink.style.pointerEvents = 'none';
        resendLink.textContent = 'Đang gửi...';
        
        // Gửi ajax request
        fetch('{{ route("resend.verification") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            })
        })
        .then(response => response.json())
        .then(data => {
            // Hiển thị thông báo
            resendMessage.style.display = 'block';
            resendMessage.className = 'alert ' + (data.success ? 'alert-success' : 'alert-danger');
            resendMessage.textContent = data.message;
            
            // Reset link
            resendLink.style.pointerEvents = 'auto';
            resendLink.textContent = 'Gửi lại mã';
            
            // Ẩn thông báo sau 5 giây
            setTimeout(() => {
                resendMessage.style.display = 'none';
            }, 5000);
        })
        .catch(error => {
            // Hiển thị lỗi
            resendMessage.style.display = 'block';
            resendMessage.className = 'alert alert-danger';
            resendMessage.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            
            // Reset link
            resendLink.style.pointerEvents = 'auto';
            resendLink.textContent = 'Gửi lại mã';
        });
        

    });
});
</script>
@endsection 