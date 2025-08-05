@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chi tiết liên hệ</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-success btn-xs">
                            <i class="fa fa-reply"></i> Phản hồi
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Người gửi:</label>
                                <p class="form-control-static">
                                    @if($contact->user)
                                        <strong>{{ $contact->user->name }}</strong>
                                        <br><small class="text-muted">{{ $contact->user->email }}</small>
                                    @else
                                        <span class="text-muted">Khách</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Trạng thái:</label>
                                <p>
                                    @if($contact->status === 'resolved')
                                        <span class="badge bg-success">Đã phản hồi</span>
                                    @else
                                        <span class="badge bg-warning">Chưa phản hồi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Ngày gửi:</label>
                                <p class="form-control-static">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Thời gian:</label>
                                <p class="form-control-static">{{ $contact->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Chủ đề:</label>
                        <div class="alert alert-primary">
                            <strong>{{ $contact->subject }}</strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nội dung tin nhắn:</label>
                        <div class="alert alert-secondary">
                            <div style="white-space: pre-wrap;">{{ $contact->message }}</div>
                        </div>
                    </div>

                    @if($contact->reply)
                    <hr>
                    <div class="form-group">
                        <label class="font-weight-bold">Nội dung phản hồi:</label>
                        <div class="alert alert-success">
                            <div style="white-space: pre-wrap;">{{ $contact->reply }}</div>
                        </div>
                        <small class="text-muted">Phản hồi lúc: {{ $contact->updated_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @endif

                    <hr>
                    <div class="form-group">
                        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        @if(!$contact->reply)
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-success">
                            <i class="fa fa-reply"></i> Phản hồi ngay
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin bổ sung</h5>
                </div>
                <div class="ibox-content">
                    @if($contact->user)
                    <div class="form-group">
                        <label class="font-weight-bold">Thông tin người dùng:</label>
                        <ul class="list-unstyled">
                            <li><strong>Tên:</strong> {{ $contact->user->name }}</li>
                            <li><strong>Email:</strong> {{ $contact->user->email }}</li>
                            <li><strong>Đăng ký:</strong> {{ $contact->user->created_at->format('d/m/Y') }}</li>
                        </ul>
                    </div>
                    @else
                    <div class="form-group">
                        <label class="font-weight-bold">Khách:</label>
                        <p class="text-muted">Người dùng chưa đăng nhập</p>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold">Thống kê:</label>
                        <ul class="list-unstyled">
                            <li><strong>ID liên hệ:</strong> #{{ $contact->id }}</li>
                            <li><strong>Độ dài tin nhắn:</strong> {{ strlen($contact->message) }} ký tự</li>
                            @if($contact->reply)
                            <li><strong>Độ dài phản hồi:</strong> {{ strlen($contact->reply) }} ký tự</li>
                            @endif
                        </ul>
                    </div>

                    @if($contact->reply)
                    <div class="form-group">
                        <label class="font-weight-bold">Thời gian phản hồi:</label>
                        <p>{{ $contact->updated_at->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thao tác nhanh</h5>
                </div>
                <div class="ibox-content">
                    <div class="btn-group-vertical btn-block">
                        @if(!$contact->reply)
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-reply"></i> Phản hồi
                        </a>
                        @endif
                        <a href="{{ route('contacts.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-list"></i> Danh sách chưa phản hồi
                        </a>
                        <a href="{{ route('contacts.replied') }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-check"></i> Danh sách đã phản hồi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
