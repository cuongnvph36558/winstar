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
                    <p><strong>Người gửi:</strong> {{ $contact->user->name ?? 'Khách' }}</p>
                    <p><strong>Chủ đề:</strong> {{ $contact->subject }}</p>
                    <p><strong>Nội dung:</strong> {{ $contact->message }}</p>
                    <p><strong>Trạng thái:</strong> {{ $contact->status }}</p>
                    <hr>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="reply">Nội dung phản hồi</label>
                            <textarea name="reply" id="reply" rows="5" class="form-control" required></textarea>
                            @error('reply')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Gửi phản hồi</button>
                        <a href="{{ route('contacts.index') }}" class="btn btn-secondary mt-2">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
