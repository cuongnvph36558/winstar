@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chi tiết liên hệ</h5>
                </div>
                <div class="ibox-content">
                    <p><strong>Người gửi:</strong> {{ $contact->user->name ?? 'Khách' }}</p>
                    <p><strong>Chủ đề:</strong> {{ $contact->subject }}</p>
                    <p><strong>Trạng thái:</strong> 
                        @if($contact->status === 'resolved')
                            <span class="label label-success">Đã phản hồi</span>
                        @else
                            <span class="label label-warning">Chưa phản hồi</span>
                        @endif
                    </p>
                    <p><strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
                    
                    <hr>
                    <p><strong>Nội dung khách gửi:</strong></p>
                    <div class="alert alert-secondary">
                        {{ $contact->message }}
                    </div>

                    <hr>
                    <p><strong>Nội dung phản hồi:</strong></p>
                    @if($contact->reply)
                        <div class="alert alert-success">
                            {{ $contact->reply }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Chưa phản hồi
                        </div>
                    @endif

                    <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-secondary mt-3">← Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
