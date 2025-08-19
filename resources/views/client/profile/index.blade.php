@extends('layouts.client')

@section('title', 'Tài khoản của tôi')

@section('styles')
<style>
.profile-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    min-height: 40vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.profile-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 20px;
}

.profile-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out;
}

.profile-hero p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.profile-content {
    padding: 40px 0;
    background: #f8f9fa;
}

.profile-content .container {
    max-width: 1200px;
}

.profile-main {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: -30px;
    position: relative;
    z-index: 3;
    animation: fadeInUp 1s ease-out 0.4s both;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 25px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
}

.profile-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.profile-card-header {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 20px;
    text-align: center;
    position: relative;
}

.profile-card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.profile-card-header h4 {
    position: relative;
    z-index: 2;
    margin: 0;
    font-weight: 600;
}

.profile-card-body {
    padding: 25px;
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 10px;
    padding: 12px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.btn-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: #000;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.alert {
    border-radius: 10px;
    border: none;
    margin-bottom: 20px;
}

.alert-success {
    background: linear-gradient(45deg, #d1e7dd, #badbcc);
    color: #0f5132;
}

.alert-danger {
    background: linear-gradient(45deg, #f8d7da, #f5c2c7);
    color: #842029;
}

.sidebar-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 25px;
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
}

.sidebar-card h5 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.user-info {
    text-align: center;
    padding: 20px 0;
}

.user-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(45deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 2rem;
    font-weight: bold;
}

.user-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.user-email {
    color: #666;
    font-size: 0.9rem;
}

.quick-stats {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #667eea;
}

.stat-label {
    font-size: 0.8rem;
    color: #666;
    text-transform: uppercase;
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

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.text-danger {
    color: #dc3545 !important;
}

.invalid-feedback {
    font-size: 0.875rem;
    color: #dc3545;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

hr {
    border: 1px solid #e9ecef;
    opacity: 0.5;
    margin: 25px 0;
}

.permissions-list {
    max-height: 120px;
    overflow-y: auto;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    border-radius: 6px;
}

.admin-status {
    padding-top: 10px;
    border-top: 1px solid #e9ecef;
}



.user-role {
    text-align: center;
}

.role-item {
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 8px;
    background: #f8f9fa;
}

.role-item:last-child {
    margin-bottom: 0;
}

.fs-6 {
    font-size: 0.875rem !important;
}

    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .form-control {
        padding: 0 15px !important;
    }

    .form-select {
        padding: 0 15px !important;
    }

    .form-label {
        margin-bottom: 8px !important;
        font-weight: 600;
        color: #333;
    }

    .form-control, .form-select {
        margin-bottom: 20px !important;
    }

    .row {
        margin-bottom: 15px !important;
    }

    .col-md-4, .col-md-6, .col-md-12 {
        padding: 0 10px !important;
    }

    .card-body {
        padding: 30px !important;
    }

    .btn {
        margin-top: 10px !important;
    }

    /* Avatar Styles */
    .avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-upload-container {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
    }

    .current-avatar {
        flex-shrink: 0;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        background: linear-gradient(45deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        border: 3px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .avatar-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
    }

    .avatar-upload-controls {
        flex: 1;
    }

    .avatar-upload-controls .form-control {
        margin-bottom: 10px;
    }

    /* Avatar Edit Button */
    .user-avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: 2px solid white;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 10;
        pointer-events: auto;
    }

    .avatar-edit-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .avatar-edit-btn i {
        font-size: 14px;
    }


</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let vietnamData = [];
    let currentCity = '{{ old("city", $user->city) }}';
    let currentDistrict = '{{ old("district", $user->district) }}';
    let currentWard = '{{ old("ward", $user->ward) }}';

    // Load dữ liệu từ file JSON
    fetch('{{ asset("client/assets/js/vietnam-provinces.json") }}')
        .then(response => response.json())
        .then(data => {
            vietnamData = data;
            loadCities();
        })
        .catch(error => {
            console.error('Error loading Vietnam data:', error);
        });

    function loadCities() {
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        
        vietnamData.forEach(province => {
            const option = document.createElement('option');
            option.value = province.Name;
            option.textContent = province.Name;
            if (province.Name === currentCity) {
                option.selected = true;
            }
            citySelect.appendChild(option);
        });

        if (currentCity) {
            loadDistricts(currentCity);
        }
    }

    function loadDistricts(cityName) {
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

        const province = vietnamData.find(p => p.Name === cityName);
        if (province && province.Districts) {
            province.Districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.Name;
                option.textContent = district.Name;
                if (district.Name === currentDistrict) {
                    option.selected = true;
                }
                districtSelect.appendChild(option);
            });

            if (currentDistrict) {
                loadWards(cityName, currentDistrict);
            }
        }
    }

    function loadWards(cityName, districtName) {
        const wardSelect = document.getElementById('ward');
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

        const province = vietnamData.find(p => p.Name === cityName);
        if (province && province.Districts) {
            const district = province.Districts.find(d => d.Name === districtName);
            if (district && district.Wards) {
                district.Wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.Name;
                    option.textContent = ward.Name;
                    if (ward.Name === currentWard) {
                        option.selected = true;
                    }
                    wardSelect.appendChild(option);
                });
            }
        }
    }

    // Event listeners
    document.getElementById('city').addEventListener('change', function() {
        const selectedCity = this.value;
        currentCity = selectedCity;
        currentDistrict = '';
        currentWard = '';
        
        if (selectedCity) {
            loadDistricts(selectedCity);
        } else {
            document.getElementById('district').innerHTML = '<option value="">Chọn quận/huyện</option>';
            document.getElementById('ward').innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    });

    document.getElementById('district').addEventListener('change', function() {
        const selectedDistrict = this.value;
        currentDistrict = selectedDistrict;
        currentWard = '';
        
        if (selectedDistrict && currentCity) {
            loadWards(currentCity, selectedDistrict);
        } else {
            document.getElementById('ward').innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    });



    // Create a simple custom modal
    window.testModal = function() {
        console.log('🧪 Creating custom modal');
        
        // Remove any existing custom modals
        const existingModal = document.getElementById('custom-avatar-modal');
        if (existingModal) {
            existingModal.remove();
            console.log('🗑️ Removed existing modal');
        }
        
        // Create modal HTML
        const modalHTML = `
            <div id="custom-avatar-modal" style="
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                background: rgba(0,0,0,0.7) !important;
                z-index: 999999 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                backdrop-filter: blur(5px);
            ">
                <div style="
                    background: white !important;
                    border-radius: 15px !important;
                    padding: 30px !important;
                    max-width: 500px !important;
                    width: 90% !important;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.5) !important;
                    position: relative !important;
                    z-index: 1000000 !important;
                ">
                    <div style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                        border-bottom: 1px solid #eee;
                    ">
                        <h5 style="margin: 0; color: #333;">
                            <i class="fa fa-camera" style="margin-right: 10px;"></i>
                            Thay đổi ảnh đại diện
                        </h5>
                        <button onclick="closeCustomModal()" style="
                            background: none;
                            border: none;
                            font-size: 20px;
                            cursor: pointer;
                            color: #666;
                        ">&times;</button>
                    </div>
                    
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="
                            width: 120px;
                            height: 120px;
                            border-radius: 50%;
                            background: linear-gradient(45deg, #667eea, #764ba2);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 3rem;
                            font-weight: bold;
                            margin: 0 auto 20px;
                            border: 4px solid white;
                            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
                        " id="custom-avatar-preview">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">
                                Chọn ảnh mới
                            </label>
                            <input type="file" id="custom-avatar-input" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="
                                width: 100%;
                                padding: 10px;
                                border: 2px dashed #ddd;
                                border-radius: 8px;
                                background: #f8f9fa;
                            ">
                            <small style="color: #666; font-size: 12px;">
                                Định dạng: JPG, PNG, GIF, WEBP - Tối đa: 2MB
                            </small>
                        </div>
                    </div>
                    
                    <div style="
                        display: flex;
                        justify-content: flex-end;
                        gap: 10px;
                        padding-top: 20px;
                        border-top: 1px solid #eee;
                    ">
                        <button onclick="closeCustomModal()" style="
                            padding: 10px 20px;
                            border: 1px solid #ddd;
                            background: white;
                            border-radius: 8px;
                            cursor: pointer;
                        ">Hủy</button>
                        <button onclick="saveCustomAvatar()" style="
                            padding: 10px 20px;
                            background: linear-gradient(45deg, #667eea, #764ba2);
                            color: white;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                        ">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        console.log('✅ Modal added to body');
        
        // Add event listeners
        const modal = document.getElementById('custom-avatar-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target.id === 'custom-avatar-modal') {
                    closeCustomModal();
                }
            });
            console.log('✅ Modal click listener added');
        }
        
        // File input change
        const fileInput = document.getElementById('custom-avatar-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                console.log('📁 File selected:', e.target.files[0]);
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('custom-avatar-preview');
                        if (preview) {
                            preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                            console.log('✅ Avatar preview updated');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
            console.log('✅ File input listener added');
        }
        
        // Add ESC key listener
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCustomModal();
            }
        });
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        console.log('✅ Modal created successfully');
    };
    
    // Close custom modal
    window.closeCustomModal = function() {
        console.log('🔒 Closing custom modal...');
        const modal = document.getElementById('custom-avatar-modal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
            console.log('✅ Modal closed successfully');
        } else {
            console.log('❌ Modal not found');
        }
    };
    
    // Save custom avatar
    window.saveCustomAvatar = function() {
        console.log('💾 Saving custom avatar...');
        const fileInput = document.getElementById('custom-avatar-input');
        if (fileInput && fileInput.files.length > 0) {
            console.log('📁 File found:', fileInput.files[0].name);
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("updateProfileRelaxed") }}';
            form.enctype = 'multipart/form-data';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            
            const fileField = document.createElement('input');
            fileField.type = 'file';
            fileField.name = 'avatar';
            fileField.files = fileInput.files;
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(fileField);
            
            document.body.appendChild(form);
            console.log('📤 Submitting form...');
            form.submit();
        } else {
            console.log('❌ No file selected');
            alert('Vui lòng chọn một ảnh!');
        }
    };

    // Debug modal functionality
    // console.log('🔧 Checking modal functionality...');
    
    // Update sidebar avatar after successful upload
    var successMessage = '{{ session("success") }}';
    if (successMessage) {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }
});
</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="profile-hero">
    <div class="profile-hero-content">
        <h1>Tài khoản của tôi</h1>
        <p>Quản lý thông tin cá nhân và cập nhật hồ sơ của bạn</p>
    </div>
</section>

<!-- Content Section -->
<section class="profile-content">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="sidebar-card">
                    <div class="user-info">
                        <div class="user-avatar-container">
                            <div class="user-avatar">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-image">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                                                <button class="avatar-edit-btn" onclick="testModal()" style="cursor: pointer;">
                        <i class="fa fa-camera"></i>
                    </button>
                        </div>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                        
                        <!-- Hiển thị vai trò chính -->
                        @if($user->isAdmin())
                            <div class="user-role mt-2">
                                <span class="badge bg-warning text-dark fs-6">⚡ Admin</span>
                            </div>
                        @elseif($user->roles->count() > 0)
                            <div class="user-role mt-2">
                                <span class="badge bg-primary fs-6">👤 {{ $user->roles->first()->name }}</span>
                            </div>
                        @else
                            <div class="user-role mt-2">
                                <span class="badge bg-secondary fs-6">👤 User</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="quick-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $user->orders->count() }}</div>
                            <div class="stat-label">Đơn hàng</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $user->getCurrentPoints() }}</div>
                            <div class="stat-label">Điểm</div>
                        </div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <h5><i class="fa fa-info-circle me-2"></i>Thông tin nhanh</h5>
                    <div class="mb-3">
                        <strong>Điện thoại:</strong><br>
                        <span class="text-muted">
                            {{ $user->getDisplayPhone() }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Địa chỉ:</strong><br>
                        <span class="text-muted">
                            @if($user->address || $user->city || $user->district || $user->ward)
                                {{ $user->address }}{{ $user->ward ? ', ' . $user->ward : '' }}{{ $user->district ? ', ' . $user->district : '' }}{{ $user->city ? ', ' . $user->city : '' }}
                            @else
                                Chưa cập nhật
                            @endif
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>VIP Level:</strong><br>
                        <span class="badge bg-warning text-dark">{{ $user->getVipLevel() }}</span>
                    </div>
                </div>

                <!-- Phân quyền -->
                <div class="sidebar-card">
                    <h5><i class="fa fa-shield me-2"></i>Phân quyền</h5>
                    
                    <!-- Vai trò chính -->
                    <div class="mb-3">
                        <strong>Vai trò chính:</strong><br>
                        @if($user->isAdmin())
                            <div class="role-item">
                                <span class="badge bg-warning text-dark fs-6">⚡ Admin</span>
                                <small class="text-muted d-block mt-1">Có quyền quản lý hệ thống</small>
                            </div>
                        @elseif($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <div class="role-item">
                                    <span class="badge bg-primary fs-6">👤 {{ $role->name }}</span>
                                    @if($role->description)
                                        <small class="text-muted d-block mt-1">{{ $role->description }}</small>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="role-item">
                                <span class="badge bg-secondary fs-6">👤 User</span>
                                <small class="text-muted d-block mt-1">Người dùng thông thường</small>
                            </div>
                        @endif
                    </div>

                    <!-- Quyền hạn -->
                    <div class="mb-3">
                        <strong>Quyền hạn:</strong><br>
                        @php
                            $permissions = $user->getAllPermissions();
                        @endphp
                        @if($permissions->count() > 0)
                            <div class="permissions-list">
                                @foreach($permissions->take(5) as $permission)
                                    <span class="badge bg-success me-1 mb-1">{{ $permission->name }}</span>
                                @endforeach
                                @if($permissions->count() > 5)
                                    <span class="badge bg-secondary me-1 mb-1">+{{ $permissions->count() - 5 }} quyền khác</span>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">Chưa có quyền hạn đặc biệt</span>
                        @endif
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="admin-status">
                        <strong>Thông tin:</strong><br>
                        <small class="text-muted">
                            @if($user->isAdmin())
                                • Có quyền truy cập admin panel<br>
                                • Có thể quản lý users và content<br>
                                • Có quyền xem thống kê hệ thống
                            @else
                                • Chỉ có quyền truy cập client area<br>
                                • Có thể đặt hàng và quản lý profile
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="profile-main">
                    <!-- Thông tin cá nhân -->
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h4><i class="fa fa-user me-2"></i>Thông tin cá nhân</h4>
                        </div>
                        <div class="profile-card-body">
                            <form method="post" action="{{ route('updateProfileRelaxed') }}" enctype="multipart/form-data">
                                @method('put')
                                @csrf



                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" 
                                               value="{{ old('phone', $user->getRealPhone()) }}"
                                               placeholder="Nhập số điện thoại">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <!-- Địa chỉ chi tiết -->
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-map-marker me-2"></i>Địa chỉ chi tiết
                                </h5>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Địa chỉ đường/phố</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                               id="address" name="address" value="{{ old('address', $user->address) }}" 
                                               placeholder="Ví dụ: 123 Nguyễn Huệ">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="city" class="form-label">Tỉnh/Thành phố</label>
                                        <select class="form-control @error('city') is-invalid @enderror" 
                                                id="city" name="city">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                        </select>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="district" class="form-label">Quận/Huyện</label>
                                        <select class="form-control @error('district') is-invalid @enderror" 
                                                id="district" name="district">
                                            <option value="">Chọn quận/huyện</option>
                                        </select>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="ward" class="form-label">Phường/Xã</label>
                                        <select class="form-control @error('ward') is-invalid @enderror" 
                                                id="ward" name="ward">
                                            <option value="">Chọn phường/xã</option>
                                        </select>
                                        @error('ward')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-save me-2"></i>Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                                        <!-- Đổi mật khẩu -->
                    @if(Auth::user()->isGoogleUser())
                        <!-- Google User - Không hiển thị form đổi mật khẩu -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h4><i class="fa fa-google me-2"></i>Tài khoản Google</h4>
                            </div>
                            <div class="profile-card-body">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i>
                                    <strong>Tài khoản Google:</strong> Bạn đang sử dụng tài khoản Google để đăng nhập. 
                                    Để thay đổi mật khẩu, vui lòng truy cập 
                                    <a href="https://myaccount.google.com/security" target="_blank" class="alert-link">Google Account Settings</a>.
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Normal User - Hiển thị form đổi mật khẩu -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h4><i class="fa fa-lock me-2"></i>Đổi mật khẩu</h4>
                            </div>
                            <div class="profile-card-body">
                                <form method="post" action="{{ route('updatePassword') }}">
                                    @method('put')
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" name="current_password" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button class="btn btn-warning" type="submit">
                                            <i class="fa fa-key me-2"></i>Đổi mật khẩu
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                 </div>


             </div>
                 </div>
    </div>
</section>


@endsection