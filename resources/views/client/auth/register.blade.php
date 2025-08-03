@extends('layouts.client')

@section('title', 'Đăng ký')

@section('content')
<!-- Hero Section with Background -->
<section class="register-hero">
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
                <!-- Register Card -->
                <div class="register-card">
                    <!-- Header -->
                    <div class="register-header text-center">
                        <div class="logo-container">
                            <i class="fas fa-user-plus logo-icon"></i>
                        </div>
                        <h2 class="register-title">Tạo tài khoản mới</h2>
                        <p class="register-subtitle">Tham gia cùng chúng tôi ngay hôm nay</p>
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

                    <!-- Google Register -->
                    <div class="social-login">
                        <a href="{{ route('auth.google') }}" class="btn btn-google-modern">
                            <i class="fab fa-google"></i>
                            <span>Đăng ký với Google</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="divider-modern">
                        <span>hoặc</span>
                    </div>

                    <!-- Register Form -->
                    <form class="register-form" action="{{ route('postRegister') }}" method="POST">
                        @csrf
                        
                        <!-- Personal Information Row -->
                        <div class="form-row-modern">
                            <!-- Name Field -->
                            <div class="form-group-modern">
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        class="form-control-modern @error('name') is-invalid @enderror" 
                                        placeholder="Họ và tên"
                                        value="{{ old('name') }}" 
                                        required
                                    />
                                </div>
                                @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

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
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    class="form-control-modern @error('phone') is-invalid @enderror" 
                                    placeholder="Số điện thoại"
                                    value="{{ old('phone') }}" 
                                    required
                                />
                            </div>
                            @error('phone')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Address Fields -->
                        <div class="address-section">
                            <h6 class="address-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Thông tin địa chỉ
                            </h6>
                            
                            <!-- Province & District Row -->
                            <div class="form-row-modern">
                                <!-- Province Field -->
                                <div class="form-group-modern">
                                    <div class="input-wrapper">
                                        <i class="fas fa-map-marker input-icon"></i>
                                        <select 
                                            name="billing_city" 
                                            id="billing_city" 
                                            class="form-control-modern @error('billing_city') is-invalid @enderror" 
                                            required
                                            data-old="{{ old('billing_city') }}"
                                        >
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                        </select>
                                    </div>
                                    @error('billing_city')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- District Field -->
                                <div class="form-group-modern">
                                    <div class="input-wrapper">
                                        <i class="fas fa-map input-icon"></i>
                                        <select 
                                            name="billing_district" 
                                            id="billing_district" 
                                            class="form-control-modern @error('billing_district') is-invalid @enderror" 
                                            required
                                            disabled
                                            data-old="{{ old('billing_district') }}"
                                        >
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                    </div>
                                    @error('billing_district')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ward & Street Address Row -->
                            <div class="form-row-modern">
                                <!-- Ward Field -->
                                <div class="form-group-modern">
                                    <div class="input-wrapper">
                                        <i class="fas fa-map-pin input-icon"></i>
                                        <select 
                                            name="billing_ward" 
                                            id="billing_ward" 
                                            class="form-control-modern @error('billing_ward') is-invalid @enderror" 
                                            required
                                            disabled
                                            data-old="{{ old('billing_ward') }}"
                                        >
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                    </div>
                                    @error('billing_ward')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Street Address Field -->
                                <div class="form-group-modern">
                                    <div class="input-wrapper">
                                        <i class="fas fa-home input-icon"></i>
                                        <input 
                                            type="text" 
                                            name="billing_address" 
                                            id="billing_address"
                                            class="form-control-modern @error('billing_address') is-invalid @enderror" 
                                            placeholder="Số nhà, tên đường"
                                            value="{{ old('billing_address') }}" 
                                            required
                                        />
                                    </div>
                                    @error('billing_address')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Row -->
                        <div class="form-row-modern">
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

                            <!-- Confirm Password Field -->
                            <div class="form-group-modern">
                                <div class="input-wrapper">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input 
                                        type="password" 
                                        name="password_confirmation" 
                                        class="form-control-modern" 
                                        placeholder="Xác nhận mật khẩu"
                                        required
                                    />
                                    <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="form-group-modern">
                            <div class="form-check-modern terms-check">
                                <input type="checkbox" id="terms" name="terms" class="form-check-input-modern" required>
                                <label for="terms" class="form-check-label-modern">
                                    Tôi đồng ý với <a href="#" class="terms-link">Điều khoản sử dụng</a> và <a href="#" class="terms-link">Chính sách bảo mật</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-register-modern">
                            <span class="btn-text">Đăng ký</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </form>

                    <!-- Footer Links -->
                    <div class="register-footer text-center">
                        <p class="login-text">
                            Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="login-link">Đăng nhập ngay</a>
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
/* Register Hero Section */
.register-hero {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 20px 0;
    width: 100%;
}

.register-hero .container-fluid {
    width: 100%;
    max-width: none;
    padding: 0;
}

.register-hero .row {
    width: 100%;
    margin: 0;
    justify-content: center;
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

/* Register Card */
.register-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 48px 60px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 2;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 60vw !important;
    min-width: 800px;
    max-width: 1200px;
    margin: 0 auto;
}

.register-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
}

/* Logo */
.logo-container {
    display: inline-block;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    transition: transform 0.3s ease;
}

.logo-icon {
    font-size: 2rem;
    color: white;
}

/* Typography */
.register-header {
    margin-bottom: 32px;
}

.register-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
    line-height: 1.2;
}

.register-subtitle {
    color: #718096;
    font-size: 1rem;
    margin-bottom: 0;
    line-height: 1.5;
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.9rem;
    margin-bottom: 24px;
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
    margin-bottom: 24px;
}

.btn-google-modern {
    width: 100%;
    background: linear-gradient(135deg, #4285f4, #34a853);
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 1rem;
}

.btn-google-modern:hover {
    background: linear-gradient(135deg, #3367d6, #2d8f47);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(66, 133, 244, 0.3);
}

.btn-google-modern i {
    font-size: 1.2rem;
}

/* Divider */
.divider-modern {
    text-align: center;
    margin: 24px 0;
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
    padding: 0 16px;
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Address Section */
.address-section {
    background: rgba(102, 126, 234, 0.05);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.address-title {
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}

.address-title i {
    color: #667eea;
}

/* Form Rows */
.form-row-modern {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
}

.form-row-modern .form-group-modern {
    flex: 1;
    margin-bottom: 0;
}

/* Form Groups */
.form-group-modern {
    position: relative;
    margin-bottom: 20px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: #a0aec0;
    font-size: 1rem;
    z-index: 2;
    transition: color 0.3s ease;
}

.form-control-modern {
    width: 100%;
    padding: 16px 16px 16px 48px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    background: white;
    transition: all 0.3s ease;
    color: #2d3748;
    height: 52px;
}

.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control-modern:focus + .input-icon {
    color: #667eea;
}

.form-control-modern.is-invalid {
    border-color: #e53e3e;
}

.form-control-modern:disabled {
    background-color: #f7fafc;
    color: #a0aec0;
    cursor: not-allowed;
}

.password-toggle {
    position: absolute;
    right: 16px;
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 0;
    font-size: 1rem;
    transition: color 0.3s ease;
    z-index: 2;
}

.password-toggle:hover {
    color: #667eea;
}

/* Error Messages */
.error-message {
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Terms Check */
.terms-check {
    margin-bottom: 0;
}

.terms-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.terms-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

/* Register Button */
.btn-register-modern {
    width: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    padding: 16px 24px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-register-modern:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-register-modern:active {
    transform: translateY(0);
}

.btn-loading {
    display: none;
}

/* Footer */
.register-footer {
    margin-top: 24px;
}

.login-text {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0;
    line-height: 1.5;
}

.login-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.login-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

/* Form Check Modern */
.form-check-modern {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.form-check-input-modern {
    width: 18px;
    height: 18px;
    border: 2px solid #cbd5e0;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 2px;
    flex-shrink: 0;
}

.form-check-label-modern {
    color: #4a5568;
    font-size: 0.9rem;
    cursor: pointer;
    line-height: 1.4;
}



/* Responsive Design */
@media (max-width: 768px) {
    .register-card {
        padding: 40px 30px;
        margin: 20px;
        border-radius: 20px;
        width: 90vw !important;
        min-width: auto;
        max-width: 100%;
    }
    
    .register-title {
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
    
    .address-section {
        padding: 16px;
    }
    
    /* Stack form rows on mobile */
    .form-row-modern {
        flex-direction: column;
        gap: 20px;
    }
    
    .form-row-modern .form-group-modern {
        margin-bottom: 0;
    }
}

@media (max-width: 480px) {
    .register-card {
        padding: 32px 20px;
        margin: 16px;
        width: 95vw !important;
        min-width: auto;
        max-width: 100%;
    }
    
    .register-title {
        font-size: 1.5rem;
    }
    
    .logo-container {
        width: 60px;
        height: 60px;
        margin-bottom: 16px;
    }
    
    .logo-icon {
        font-size: 1.5rem;
    }
    
    .form-control-modern {
        height: 48px;
        padding: 14px 14px 14px 44px;
    }
    
    .btn-register-modern {
        height: 48px;
    }
    
    .address-section {
        padding: 12px;
    }
    
    /* Ensure proper spacing on very small screens */
    .form-row-modern {
        gap: 16px;
    }
}

/* Loading State */
.btn-register-modern.loading .btn-text {
    display: none;
}

.btn-register-modern.loading .btn-loading {
    display: inline-block;
}

/* Hover Effects */
.register-card:hover .logo-container {
    transform: scale(1.05);
}

/* Perfect centering for all screen sizes */
@media (min-height: 600px) {
    .register-hero {
        min-height: 100vh;
    }
}

@media (max-height: 600px) {
    .register-hero {
        min-height: 100vh;
        padding: 40px 0;
    }
    
    .register-card {
        padding: 32px 24px;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Old values from Laravel
    const oldCity = '{{ old("billing_city") }}';
    const oldDistrict = '{{ old("billing_district") }}';
    const oldWard = '{{ old("billing_ward") }}';

    // Load provinces
    console.log('Loading provinces...');
    
    function loadProvincesFromAPI() {
        console.log('Loading provinces from external API...');
        return fetch('{{ asset("assets/external/data/vietnam-provinces.json") }}')
            .then(response => {
                console.log('External API response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('External API data received:', data);
                // Transform the data to match our expected format
                return data.map(province => ({
                    name: province.Name,
                    code: province.Id,
                    districts: province.Districts.map(district => ({
                        name: district.Name,
                        code: district.Id,
                        wards: district.Wards.map(ward => ({
                            name: ward.Name,
                            code: ward.Id
                        }))
                    }))
                }));
            });
    }
    
    function loadProvincesFromLocal() {
        console.log('Loading provinces from local file...');
        return fetch('{{ asset("client/assets/js/vietnam-provinces.json") }}')
            .then(response => response.json())
            .then(data => {
                console.log('Local provinces data:', data);
                // Transform the data to match our expected format
                return data.map(province => ({
                    name: province.Name,
                    code: province.Id,
                    districts: province.Districts.map(district => ({
                        name: district.Name,
                        code: district.Id,
                        wards: district.Wards.map(ward => ({
                            name: ward.Name,
                            code: ward.Id
                        }))
                    }))
                }));
            });
    }
    
    // Try external API first, fallback to local file
    loadProvincesFromAPI()
        .then(data => {
            console.log('Provinces data from external API:', data);
            populateProvinces(data);
        })
        .catch(error => {
            console.error('External API failed, using local data:', error);
            return loadProvincesFromLocal();
        })
        .then(data => {
            if (data) {
                console.log('Provinces data from local:', data);
                populateProvinces(data);
            }
        })
        .catch(error => {
            console.error('Both external API and local file failed:', error);
        });
    
    function populateProvinces(data) {
        // Store the data globally for use in district/ward loading
        vietnamData = data;
        
        const provinceSelect = document.getElementById('billing_city');
        console.log('Province select element:', provinceSelect);
        if (!provinceSelect) {
            console.error('Province select element not found!');
            return;
        }
        
        // Clear existing options except the first one
        while (provinceSelect.options.length > 1) {
            provinceSelect.remove(1);
        }
        
        data.forEach(province => {
            const option = document.createElement('option');
            option.value = province.name;
            option.textContent = province.name;
            option.dataset.code = province.code;
            provinceSelect.appendChild(option);
            console.log('Added province option:', province.name);
        });

        // Restore old province selection if exists
        if (oldCity) {
            provinceSelect.value = oldCity;
            provinceSelect.dispatchEvent(new Event('change')); // Trigger change event to load districts
        }
    }

    // Store the complete data globally
    let vietnamData = null;

    // Province change event
    document.getElementById('billing_city').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceCode = selectedOption.dataset.code;
        const districtSelect = document.getElementById('billing_district');
        const wardSelect = document.getElementById('billing_ward');

        // Reset districts and wards
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

        if (provinceCode && vietnamData) {
            districtSelect.disabled = false;
            // Find the selected province and load its districts
            const province = vietnamData.find(p => p.code === provinceCode);
            if (province && province.districts) {
                province.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.name;
                    option.textContent = district.name;
                    option.dataset.code = district.code;
                    districtSelect.appendChild(option);
                });

                // Restore old district selection if exists
                if (oldDistrict) {
                    districtSelect.value = oldDistrict;
                    districtSelect.dispatchEvent(new Event('change')); // Trigger change event to load wards
                }
            }
        } else {
            districtSelect.disabled = true;
            wardSelect.disabled = true;
        }
    });

    // District change event
    document.getElementById('billing_district').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const districtCode = selectedOption.dataset.code;
        const wardSelect = document.getElementById('billing_ward');

        // Reset wards
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

        if (districtCode && vietnamData) {
            wardSelect.disabled = false;
            // Find the selected district and load its wards
            for (const province of vietnamData) {
                const district = province.districts.find(d => d.code === districtCode);
                if (district && district.wards) {
                    district.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.name;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });

                    // Restore old ward selection if exists
                    if (oldWard) {
                        wardSelect.value = oldWard;
                    }
                    break;
                }
            }
        } else {
            wardSelect.disabled = true;
        }
    });
});

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
document.querySelector('.register-form').addEventListener('submit', function(e) {
    const button = this.querySelector('.btn-register-modern');
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
@endsection 