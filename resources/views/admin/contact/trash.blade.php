@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách liên hệ đã phản hồi</h5>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Người gửi</th>
                                <th>Chủ đề</th>
                                <th>Ngày gửi</th>
                                <th>Nội dung phản hồi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $index => $contact)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contact->user->name ?? 'Khách' }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $contact->reply }}</td>
                                    <td>
                                        <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-sm btn-info">Chi tiết</a>

                                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa liên hệ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có liên hệ đã phản hồi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
