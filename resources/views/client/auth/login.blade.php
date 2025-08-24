@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('content')
<!-- Hero Section with Background -->
<section class="login-hero">
    <!-- Animated Background Elements -->
    <div class="animated-bg">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
    </div>
    
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <!-- Login Card -->
                <div class="login-card">
                    <!-- Header -->
                    <div class="login-header text-center">
                        <div class="logo-container">
                            <i class="fas fa-star logo-icon"></i>
                </div>
                        <h2 class="login-title">Chào mừng trở lại</h2>
                        <p class="login-subtitle">Đăng nhập để tiếp tục trải nghiệm</p>
                </div>
                
                    <!-- Alert Messages -->
                    @if(session('error'))
                    <div class="alert alert-danger alert-modern" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="alert alert-success alert-modern" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <!-- Google Login -->
                    <div class="social-login">
                        <a href="{{ route('auth.google') }}" class="btn btn-google-modern">
                            <i class="fab fa-google"></i>
                            <span>Đăng nhập với Google</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="divider-modern">
                        <span>hoặc</span>
                    </div>

                    <!-- Login Form -->
                    <form class="login-form" action="{{ route('postLogin') }}" method="POST">
                    @csrf
                        
                        <!-- Email Field -->
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control-modern @error('email') is-invalid @enderror" 
                                    placeholder="Email của bạn"
                                    value="{{ old('email') }}" 
                                    required
                                />
                            </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                        <!-- Password Field -->
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control-modern @error('password') is-invalid @enderror" 
                                    placeholder="Mật khẩu"
                                    required
                                />
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="form-options">
                            <div class="form-check-modern">
                                <input type="checkbox" id="remember" name="remember" class="form-check-input-modern">
                                <label for="remember" class="form-check-label-modern">
                                    <i class="fas fa-user-check"></i>
                                    <span>Ghi nhớ đăng nhập</span>
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                <i class="fas fa-key"></i>
                                <span>Quên mật khẩu?</span>
                            </a>
                    </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-login-modern">
                            <span class="btn-text">Đăng nhập</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                </form>
                
                    <!-- Footer Links -->
                    <div class="login-footer text-center">
                        <p class="register-text">
                            Chưa có tài khoản? 
                            <a href="{{ route('register') }}" class="register-link">Đăng ký ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
/* Login Hero Section */
.login-hero {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 40px 20px;
}

/* Animated Background */
.animated-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.floating-shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 10%;
    animation-delay: 2s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

.shape-4 {
    width: 100px;
    height: 100px;
    top: 10%;
    right: 20%;
    animation-delay: 1s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Login Card */
.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 28px;
    padding: 60px 50px;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 2;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 60vw !important;
    margin: 0 auto;
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.25);
}

/* Logo */
.logo-container {
    display: inline-block;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 32px;
    transition: transform 0.3s ease;
}

.logo-icon {
    font-size: 2.5rem;
    color: white;
}

/* Typography */
.login-header {
    margin-bottom: 40px;
}

.login-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
    line-height: 1.2;
}

.login-subtitle {
    color: #718096;
    font-size: 1.1rem;
    margin-bottom: 0;
    line-height: 1.5;
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: 16px;
    padding: 16px 20px;
    font-size: 1rem;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
}

.alert-danger {
    background: linear-gradient(135deg, #fed7d7, #feb2b2);
    color: #c53030;
}

.alert-success {
    background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
    color: #22543d;
}

/* Google Button */
.social-login {
    margin-bottom: 32px;
}

.btn-google-modern {
    width: 100%;
    background: linear-gradient(135deg, #4285f4, #34a853);
    border: none;
    border-radius: 16px;
    padding: 18px 24px;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 1.1rem;
}

.btn-google-modern:hover {
    background: linear-gradient(135deg, #3367d6, #2d8f47);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(66, 133, 244, 0.3);
}

.btn-google-modern i {
    font-size: 1.3rem;
}

/* Divider */
.divider-modern {
    text-align: center;
    margin: 32px 0;
    position: relative;
}

.divider-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
}

.divider-modern span {
    background: rgba(255, 255, 255, 0.95);
    padding: 0 20px;
    color: #718096;
    font-size: 1rem;
    font-weight: 500;
}

/* Form Groups */
.form-group-modern {
    position: relative;
    margin-bottom: 24px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 20px;
    color: #a0aec0;
    font-size: 1.1rem;
    z-index: 2;
    transition: color 0.3s ease;
}

.form-control-modern {
    width: 100%;
    padding: 18px 20px 18px 56px;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-size: 1.1rem;
    background: white;
    transition: all 0.3s ease;
    color: #2d3748;
    height: 60px;
}

.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control-modern:focus + .input-icon {
    color: #667eea;
}

.form-control-modern.is-invalid {
    border-color: #e53e3e;
}

.password-toggle {
    position: absolute;
    right: 20px;
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 0;
    font-size: 1.1rem;
    transition: color 0.3s ease;
    z-index: 2;
}

.password-toggle:hover {
    color: #667eea;
}

/* Error Messages */
.error-message {
    color: #e53e3e;
    font-size: 0.9rem;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Form Options */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
}

.form-check-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
}

.form-check-input-modern {
    width: 22px;
    height: 22px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    background: white;
    transition: all 0.3s ease;
    position: relative;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.form-check-input-modern:checked {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    position: relative;
}

.form-check-input-modern:checked::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    line-height: 1;
}

.form-check-input-modern:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    border-color: #667eea;
}

.form-check-label-modern {
    color: #4a5568;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
    user-select: none;
}

.form-check-label-modern:hover {
    color: #667eea;
}

.form-check-label-modern i {
    color: #667eea;
    font-size: 0.9rem;
    transition: transform 0.3s ease;
}

.form-check-modern:hover .form-check-label-modern i {
    transform: scale(1.1);
}

.forgot-link {
    color: #667eea;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    background: rgba(102, 126, 234, 0.05);
}

.forgot-link:hover {
    color: #5a67d8;
    background: rgba(102, 126, 234, 0.1);
    text-decoration: none;
    transform: translateY(-1px);
}

.forgot-link i {
    font-size: 0.9rem;
    transition: transform 0.3s ease;
}

.forgot-link:hover i {
    transform: rotate(-10deg);
}

/* Login Button */
.btn-login-modern {
    width: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 16px;
    padding: 18px 28px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-login-modern:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-login-modern:active {
    transform: translateY(0);
}

.btn-loading {
    display: none;
}

/* Footer */
.login-footer {
    margin-top: 32px;
}

.register-text {
    color: #718096;
    font-size: 1rem;
    margin-bottom: 0;
    line-height: 1.5;
}

.register-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.register-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .login-card {
        width: 65vw !important;
        padding: 50px 40px;
    }
    
    .login-title {
        font-size: 2.25rem;
    }
    
    .logo-container {
        width: 90px;
        height: 90px;
    }
    
    .logo-icon {
        font-size: 2.25rem;
    }
}

@media (max-width: 1200px) {
    .login-card {
        width: 70vw !important;
        padding: 50px 40px;
    }
    
    .login-title {
        font-size: 2.25rem;
    }
    
    .logo-container {
        width: 90px;
        height: 90px;
    }
    
    .logo-icon {
        font-size: 2.25rem;
    }
}

@media (max-width: 768px) {
    .login-card {
        width: 85vw !important;
        padding: 40px 30px;
        margin: 20px;
        border-radius: 24px;
    }
    
    .login-title {
        font-size: 2rem;
    }
    
    .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
    .logo-container {
        width: 80px;
        height: 80px;
        margin-bottom: 24px;
    }
    
    .logo-icon {
        font-size: 2rem;
    }
    
    .form-control-modern {
        height: 56px;
        padding: 16px 18px 16px 52px;
    }
    
    .btn-login-modern {
        height: 56px;
    }
}

@media (max-width: 480px) {
    .login-card {
        width: 95vw !important;
        padding: 32px 24px;
        margin: 16px;
        border-radius: 20px;
    }
    
    .login-title {
        font-size: 1.75rem;
    }
    
    .logo-container {
        width: 70px;
        height: 70px;
        margin-bottom: 20px;
    }
    
    .logo-icon {
        font-size: 1.75rem;
    }
    
    .form-control-modern {
        height: 52px;
        padding: 14px 16px 14px 48px;
        font-size: 1rem;
    }
    
    .btn-login-modern {
        height: 52px;
        font-size: 1rem;
    }
    
    .btn-google-modern {
        font-size: 1rem;
        padding: 16px 20px;
    }
}

/* Loading State */
.btn-login-modern.loading .btn-text {
    display: none;
}

.btn-login-modern.loading .btn-loading {
    display: inline-block;
}

/* Hover Effects */
.login-card:hover .logo-container {
    transform: scale(1.05);
}

/* Perfect centering for all screen sizes */
@media (min-height: 600px) {
    .login-hero {
        min-height: 100vh;
    }
}

@media (max-height: 600px) {
    .login-hero {
        min-height: 100vh;
        padding: 60px 20px;
    }
    
    .login-card {
        padding: 40px 30px;
    }
}
</style>
@endsection

<script>
function togglePassword(button) {
    const input = button.parentElement.querySelector('input');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Form submission with loading state
document.querySelector('.login-form').addEventListener('submit', function(e) {
    const button = this.querySelector('.btn-login-modern');
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');
    
    button.classList.add('loading');
    button.disabled = true;
    
    // Re-enable after 5 seconds if no response
    setTimeout(() => {
        button.classList.remove('loading');
        button.disabled = false;
    }, 5000);
});
</script>
