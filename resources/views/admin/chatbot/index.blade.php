@extends('layouts.admin')

@section('title', 'Bảng điều khiển Chatbot')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Chatbot</li>
                    </ol>
                </div>
                <h4 class="page-title">Chatbot Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Messages">Tổng tin nhắn</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($stats['total_messages']) }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="fa fa-comments font-20 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Users">Người dùng</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($stats['total_users']) }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="fa fa-users font-20 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Today Messages">Hôm nay</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($stats['today_messages']) }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded">
                                <i class="fa fa-calendar font-20 text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Unread Messages">Chưa đọc</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($stats['unread_messages']) }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="fa fa-envelope font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Messages -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Tin nhắn gần đây</h4>
                        <a href="{{ route('admin.chatbot.conversations') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Tin nhắn</th>
                                    <th>Loại</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMessages as $message)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <span class="avatar-title bg-soft-primary rounded-circle">
                                                    {{ substr($message->user->name ?? 'Unknown', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h5 class="font-14 mb-0">{{ $message->user->name ?? 'Unknown' }}</h5>
                                                <small class="text-muted">{{ $message->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ Str::limit($message->message, 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($message->sender === 'user')
                                            <span class="badge bg-primary">User</span>
                                        @else
                                            <span class="badge bg-success">Bot</span>
                                        @endif
                                    </td>
                                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($message->sender === 'bot' && !$message->is_read)
                                            <span class="badge bg-warning">Chưa đọc</span>
                                        @else
                                            <span class="badge bg-success">Đã đọc</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có tin nhắn nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Người dùng tích cực</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Số tin nhắn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <span class="avatar-title bg-soft-primary rounded-circle">
                                                    {{ substr($user->user->name ?? 'Unknown', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h5 class="font-14 mb-0">{{ $user->user->name ?? 'Unknown' }}</h5>
                                                <small class="text-muted">{{ $user->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->message_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Thao tác nhanh</h4>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.chatbot.analytics') }}" class="btn btn-outline-primary">
                            <i class="fa fa-chart-bar me-2"></i>Xem thống kê
                        </a>
                        <a href="{{ route('admin.chatbot.settings') }}" class="btn btn-outline-info">
                            <i class="fa fa-cog me-2"></i>Cài đặt
                        </a>
                        <form action="{{ route('admin.chatbot.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fa fa-check me-2"></i>Đánh dấu tất cả đã đọc
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh stats every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@endpush 