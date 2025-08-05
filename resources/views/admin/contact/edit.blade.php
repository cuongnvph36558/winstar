@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Phản hồi liên hệ</h5>
                </div>
                <div class="ibox-content">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Người gửi:</label>
                                <p class="form-control-static">{{ $contact->user->name ?? 'Khách' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Ngày gửi:</label>
                                <p class="form-control-static">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Chủ đề:</label>
                        <p class="form-control-static">{{ $contact->subject }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nội dung tin nhắn:</label>
                        <div class="alert alert-secondary">
                            {{ $contact->message }}
                        </div>
                    </div>

                    <hr>

                    <!-- Reply Form -->
                    <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="reply" class="font-weight-bold">Nội dung phản hồi: <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reply" name="reply" rows="6" 
                                      placeholder="Nhập nội dung phản hồi..." required>{{ old('reply', $contact->reply) }}</textarea>
                            <small class="form-text text-muted">Nội dung phản hồi phải có ít nhất 10 ký tự.</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-send"></i> Gửi phản hồi
                            </button>
                            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin liên hệ</h5>
                </div>
                <div class="ibox-content">
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

                    @if($contact->user)
                    <div class="form-group">
                        <label class="font-weight-bold">Email:</label>
                        <p>{{ $contact->user->email }}</p>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold">Thời gian:</label>
                        <p>{{ $contact->created_at->diffForHumans() }}</p>
                    </div>

                    @if($contact->reply)
                    <div class="form-group">
                        <label class="font-weight-bold">Phản hồi hiện tại:</label>
                        <div class="alert alert-info">
                            {{ $contact->reply }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-resize textarea
    $('#reply').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Form validation
    $('form').on('submit', function(e) {
        var reply = $('#reply').val().trim();
        if (reply.length < 10) {
            e.preventDefault();
            alert('Nội dung phản hồi phải có ít nhất 10 ký tự.');
            $('#reply').focus();
            return false;
        }
    });
});
</script>
@endpush
@endsection
