@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách liên hệ</h5>
                      <div class="ibox-tools">
                        <a href="{{ route('contacts.replied') }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i>Danh sách đã phản hồi
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Người gửi</th>
                                    <th>Chủ đề</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày gửi</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $index => $contact)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contact->user->name ?? 'Khách' }}</td>
                                    <td>{{ $contact->subject }}</td>
                                      <td>
                                    <span
                                        class="badge bg-{{ $contact->status === 'resolved' ? 'success' : ($contact->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>

                                </td>
                                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('contacts.show', $contact->id) }}">
                                            <button type="button"
                                                class="btn btn-secondary btn-sm btn-warning me-1">Chi tiết</button>
                                        </a>
                                        <a href="{{ route('contacts.edit', $contact->id) }}"><button
                                                class="btn btn-sm btn-success me-1">Phản hổi</button></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có liên hệ nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- Phân trang --}}
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
