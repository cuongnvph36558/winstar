@extends('layouts.client')

@section('title', 'Yêu cầu đổi hoàn hàng - Đơn hàng #' . $order->id)

@section('content')
<!-- Hero Section -->
<section class="hero-section py-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="hero-content">
                    <div class="hero-icon mb-20">
                        <i class="fa fa-exchange-alt"></i>
                    </div>
                    <h1 class="hero-title mb-15">Yêu cầu đổi hoàn hàng</h1>
                    <p class="hero-subtitle">Đơn hàng #{{ $order->id }} - {{ $order->code_order ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Return Form Section -->
<section class="return-form-section py-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="return-form-container">
                    <!-- Order Summary Card -->
                    <div class="order-summary-card mb-40">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fa fa-info-circle"></i>
                                Thông tin đơn hàng
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="order-info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Ngày đặt</span>
                                        <span class="info-value">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Tổng tiền</span>
                                        <span class="info-value">{{ $order->total_amount ? number_format($order->total_amount) : 0 }}đ</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fa fa-shopping-cart"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Số sản phẩm</span>
                                        <span class="info-value">{{ $order->orderItems ? $order->orderItems->count() : 0 }} sản phẩm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Form -->
                    <div class="return-form-card">
                        <form action="{{ route('client.return.request', $order->id) }}" method="POST" id="returnForm" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Return Method -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4 class="section-title">
                                        <i class="fa fa-cog"></i>
                                        Phương thức đổi hoàn hàng
                                    </h4>
                                </div>
                                <div class="form-group">
                                    <select name="return_method" id="return_method" class="form-control @error('return_method') is-invalid @enderror" required>
                                        <option value="">Chọn phương thức</option>
                                        <option value="points" {{ old('return_method') == 'points' ? 'selected' : '' }}>Đổi điểm</option>
                                        <option value="exchange" {{ old('return_method') == 'exchange' ? 'selected' : '' }}>Đổi hàng</option>
                                    </select>
                                    @error('return_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Return Reason -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4 class="section-title">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        Lý do đổi hoàn hàng
                                    </h4>
                                </div>
                                <div class="form-group">
                                    <select name="return_reason" id="return_reason" class="form-control @error('return_reason') is-invalid @enderror" required>
                                        <option value="">Chọn lý do</option>
                                        <option value="Sản phẩm bị lỗi" {{ old('return_reason') == 'Sản phẩm bị lỗi' ? 'selected' : '' }}>Sản phẩm bị lỗi</option>
                                        <option value="Sản phẩm không đúng mô tả" {{ old('return_reason') == 'Sản phẩm không đúng mô tả' ? 'selected' : '' }}>Sản phẩm không đúng mô tả</option>
                                        <option value="Sản phẩm bị hư hỏng khi vận chuyển" {{ old('return_reason') == 'Sản phẩm bị hư hỏng khi vận chuyển' ? 'selected' : '' }}>Sản phẩm bị hư hỏng khi vận chuyển</option>
                                        <option value="Không vừa ý với sản phẩm" {{ old('return_reason') == 'Không vừa ý với sản phẩm' ? 'selected' : '' }}>Không vừa ý với sản phẩm</option>
                                        <option value="Đặt nhầm sản phẩm" {{ old('return_reason') == 'Đặt nhầm sản phẩm' ? 'selected' : '' }}>Đặt nhầm sản phẩm</option>
                                        <option value="Lý do khác" {{ old('return_reason') == 'Lý do khác' ? 'selected' : '' }}>Lý do khác</option>
                                    </select>
                                    @error('return_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Return Description -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4 class="section-title">
                                        <i class="fa fa-comment"></i>
                                        Mô tả chi tiết
                                    </h4>
                                </div>
                                <div class="form-group">
                                    <textarea name="return_description" id="return_description" rows="5" 
                                        class="form-control @error('return_description') is-invalid @enderror" 
                                        placeholder="Vui lòng mô tả chi tiết về vấn đề bạn gặp phải...">{{ old('return_description') }}</textarea>
                                    @error('return_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Media Upload Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4 class="section-title">
                                        <i class="fa fa-camera"></i>
                                        Bằng chứng đổi hoàn hàng
                                    </h4>
                                </div>
                                
                                <div class="upload-grid">
                                    <!-- Video Upload -->
                                    <div class="upload-item">
                                        <div class="upload-header">
                                            <h5>Video bóc hàng <span class="required-badge">Bắt buộc</span></h5>
                                        </div>
                                        <div class="upload-body">
                                            <input type="file" name="return_video" id="return_video" 
                                                   class="form-control @error('return_video') is-invalid @enderror" 
                                                   accept="video/*" required>
                                            @error('return_video')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="preview-container" id="video-preview">
                                            <div class="preview-placeholder">
                                                <i class="fa fa-video-camera"></i>
                                                <p>Chưa có video</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Image Upload -->
                                    <div class="upload-item">
                                        <div class="upload-header">
                                            <h5>Ảnh đơn hàng <span class="required-badge">Bắt buộc</span></h5>
                                        </div>
                                        <div class="upload-body">
                                            <input type="file" name="return_order_image" id="return_order_image" 
                                                   class="form-control @error('return_order_image') is-invalid @enderror" 
                                                   accept="image/*" required>
                                            @error('return_order_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="preview-container" id="order-image-preview">
                                            <div class="preview-placeholder">
                                                <i class="fa fa-image"></i>
                                                <p>Chưa có ảnh</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Image Upload -->
                                    <div class="upload-item">
                                        <div class="upload-header">
                                            <h5>Ảnh sản phẩm <span class="required-badge">Bắt buộc</span></h5>
                                        </div>
                                        <div class="upload-body">
                                            <input type="file" name="return_product_image" id="return_product_image" 
                                                   class="form-control @error('return_product_image') is-invalid @enderror" 
                                                   accept="image/*" required>
                                            @error('return_product_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="preview-container" id="product-image-preview">
                                            <div class="preview-placeholder">
                                                <i class="fa fa-cube"></i>
                                                <p>Chưa có ảnh</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-section">
                                <div class="terms-section">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="agree_terms" required>
                                        <label class="custom-control-label" for="agree_terms">
                                            Tôi đồng ý với <a href="#" class="terms-link">điều khoản đổi hoàn hàng</a>
                                            <span class="required-badge">Bắt buộc</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-submit">
                                    <i class="fa fa-paper-plane"></i>
                                    Gửi yêu cầu đổi hoàn hàng
                                </button>
                                <a href="{{ route('client.order.show', $order->id) }}" class="btn btn-outline-secondary btn-cancel">
                                    <i class="fa fa-arrow-left"></i>
                                    Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.hero-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.hero-icon i {
    font-size: 2rem;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

/* Main Container */
.return-form-section {
    background: #f8f9fa;
    min-height: 100vh;
}

.return-form-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Order Summary Card */
.order-summary-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
}

.card-title {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.card-title i {
    margin-right: 12px;
}

.card-body {
    padding: 30px;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.info-icon i {
    color: white;
    font-size: 1.2rem;
}

.info-content {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.info-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

/* Return Form Card */
.return-form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Form Sections */
.form-section {
    padding: 40px;
    border-bottom: 1px solid #f1f3f4;
}

.form-section:last-child {
    border-bottom: none;
}

.section-header {
    margin-bottom: 25px;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.section-title i {
    color: #667eea;
    margin-right: 12px;
}

/* Form Controls */
.form-control {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 15px 20px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    background: white;
}

/* Upload Grid */
.upload-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.upload-item {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.upload-item:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.upload-header h5 {
    margin: 0 0 15px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.required-badge {
    background: #dc3545;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
}

.preview-container {
    min-height: 100px;
    border-radius: 10px;
    overflow: hidden;
    background: white;
    border: 1px solid #e9ecef;
    margin-top: 15px;
}

.preview-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100px;
    color: #adb5bd;
}

.preview-placeholder i {
    font-size: 1.5rem;
    margin-bottom: 8px;
}

.preview-placeholder p {
    margin: 0;
    font-size: 0.9rem;
}

.preview-container img,
.preview-container video {
    width: 100%;
    height: 100px;
    object-fit: cover;
}

/* Terms Section */
.terms-section {
    text-align: center;
}

.custom-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.custom-control-input {
    width: 20px;
    height: 20px;
    accent-color: #667eea;
}

.custom-control-label {
    font-size: 1rem;
    color: #495057;
    cursor: pointer;
}

.terms-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.terms-link:hover {
    text-decoration: underline;
}

/* Form Actions */
.form-actions {
    padding: 40px;
    text-align: center;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 15px 30px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin: 0 10px;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-cancel {
    background: white;
    color: #6c757d;
    border: 2px solid #6c757d;
}

.btn-cancel:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .form-section {
        padding: 25px;
    }
    
    .upload-grid {
        grid-template-columns: 1fr;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        padding: 25px;
    }
    
    .btn {
        width: 100%;
        margin: 10px 0;
        justify-content: center;
    }
    
    .card-body {
        padding: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('returnForm');
    const submitBtn = form.querySelector('.btn-submit');
    
    // File preview handlers
    const videoInput = document.getElementById('return_video');
    const orderImageInput = document.getElementById('return_order_image');
    const productImageInput = document.getElementById('return_product_image');
    
    // Video preview
    videoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('video-preview');
        
        if (file) {
            if (file.size > 50 * 1024 * 1024) {
                alert('Video quá lớn! Vui lòng chọn video nhỏ hơn 50MB.');
                this.value = '';
                return;
            }
            
            if (!file.type.startsWith('video/')) {
                alert('Vui lòng chọn file video hợp lệ!');
                this.value = '';
                return;
            }
            
            const video = document.createElement('video');
            video.controls = true;
            video.style.width = '100%';
            video.style.height = '100px';
            video.style.objectFit = 'cover';
            
            const source = document.createElement('source');
            source.src = URL.createObjectURL(file);
            source.type = file.type;
            
            video.appendChild(source);
            preview.innerHTML = '';
            preview.appendChild(video);
        } else {
            preview.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fa fa-video-camera"></i>
                    <p>Chưa có video</p>
                </div>
            `;
        }
    });
    
    // Order image preview
    orderImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('order-image-preview');
        
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Ảnh quá lớn! Vui lòng chọn ảnh nhỏ hơn 5MB.');
                this.value = '';
                return;
            }
            
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn file ảnh hợp lệ!');
                this.value = '';
                return;
            }
            
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.width = '100%';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            
            preview.innerHTML = '';
            preview.appendChild(img);
        } else {
            preview.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fa fa-image"></i>
                    <p>Chưa có ảnh</p>
                </div>
            `;
        }
    });
    
    // Product image preview
    productImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('product-image-preview');
        
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Ảnh quá lớn! Vui lòng chọn ảnh nhỏ hơn 5MB.');
                this.value = '';
                return;
            }
            
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn file ảnh hợp lệ!');
                this.value = '';
                return;
            }
            
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.width = '100%';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            
            preview.innerHTML = '';
            preview.appendChild(img);
        } else {
            preview.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fa fa-cube"></i>
                    <p>Chưa có ảnh</p>
                </div>
            `;
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const returnMethod = document.getElementById('return_method').value;
        const returnReason = document.getElementById('return_reason').value;
        const agreeTerms = document.getElementById('agree_terms').checked;
        const video = document.getElementById('return_video').files[0];
        const orderImage = document.getElementById('return_order_image').files[0];
        const productImage = document.getElementById('return_product_image').files[0];
        
        if (!returnMethod || !returnReason || !agreeTerms) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc và đồng ý với điều khoản!');
            return false;
        }
        
        if (!video || !orderImage || !productImage) {
            e.preventDefault();
            alert('Vui lòng upload đầy đủ video và hình ảnh theo yêu cầu!');
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang gửi...';
    });
});
</script>
@endsection 