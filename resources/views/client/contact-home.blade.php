@extends('layouts.client')

@section('title', 'Liên hệ với chúng tôi')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">LIÊN HỆ VỚI CHÚNG TÔI</h3>
                    <p class="mb-0"><em>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</em></p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('client.contact.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="subject" class="form-label">HỌ VÀ TÊN*</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">ĐỊA CHỈ EMAIL*</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">NỘI DUNG TIN NHẮN*</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                GỬI TIN NHẮN
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="card mt-4 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fa fa-info-circle"></i> THÔNG TIN LIÊN HỆ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fa fa-map-marker text-danger"></i> Địa chỉ:</h6>
                            <p>123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh, Việt Nam</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fa fa-phone text-success"></i> Điện thoại:</h6>
                            <p>+84 123 456 789</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fa fa-envelope text-warning"></i> Email:</h6>
                            <p>info@winstar.com</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fa fa-clock-o text-info"></i> Giờ làm việc:</h6>
                            <p>Thứ 2 - Thứ 6: 8:00 - 18:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    border-radius: 25px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>
@endsection 