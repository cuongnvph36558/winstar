@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách liên hệ đã phản hồi</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('contacts.index') }}" class="btn btn-warning btn-xs">
                            <i class="fa fa-clock-o"></i> Chưa phản hồi
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

                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('contacts.replied') }}" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Tìm kiếm theo tiêu đề, nội dung, phản hồi hoặc người gửi..." 
                                           value="{{ request('search') }}">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="text-muted">Tổng: {{ $contacts->total() }} liên hệ</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="15%">Người gửi</th>
                                    <th width="20%">Chủ đề</th>
                                    <th width="15%">Trạng thái</th>
                                    <th width="15%">Ngày gửi</th>
                                    <th width="15%">Ngày phản hồi</th>
                                    <th width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $index => $contact)
                                <tr>
                                    <td>{{ $index + 1 + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                                    <td>
                                        @if($contact->user)
                                            <strong>{{ $contact->user->name }}</strong>
                                            <br><small class="text-muted">{{ $contact->user->email }}</small>
                                        @else
                                            <span class="text-muted">Khách</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($contact->subject, 40) }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($contact->message, 60) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Đã phản hồi</span>
                                    </td>
                                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $contact->updated_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('contacts.show', $contact->id) }}" 
                                           class="btn btn-info btn-xs" title="Xem chi tiết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('contacts.destroy', $contact->id) }}" 
                                              method="POST" style="display:inline-block;" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" title="Xóa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> Không có liên hệ nào đã phản hồi.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        @if($contacts->count() > 0)
                        <div class="row mt-3">
                            <div class="col-md-12 text-right">
                                {{ $contacts->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
