@extends('layouts.client')

@section('title', 'Thông báo')

@section('content')
<!-- Breadcrumb Section -->
<section class="breadcrumb-section py-20 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('client.home') }}" class="text-decoration-none">
                                <i class="fa fa-home mr-5"></i>Trang chủ
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fa fa-bell mr-5"></i>Thông báo
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Notifications Section -->
<section class="notifications-section py-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="notifications-header mb-30">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="section-title">
                            <i class="fa fa-bell text-primary mr-10"></i>
                            Thông báo của tôi
                        </h2>
                        @if($notifications->where('read_at', null)->count() > 0)
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                                <i class="fa fa-check-double mr-5"></i>Đánh dấu tất cả đã đọc
                            </button>
                        @endif
                    </div>
                </div>

                <div class="notifications-content">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ $notification->isRead() ? 'read' : 'unread' }}" id="notification-{{ $notification->id }}">
                            <div class="notification-icon">
                                @switch($notification->type)
                                    @case('return_exchange')
                                        <i class="fa fa-exchange text-warning"></i>
                                        @break
                                    @default
                                        <i class="fa fa-bell text-primary"></i>
                                @endswitch
                            </div>
                            <div class="notification-content">
                                <div class="notification-header">
                                    <h5 class="notification-title">{{ $notification->title }}</h5>
                                    <div class="notification-meta">
                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                        @if(!$notification->isRead())
                                            <span class="unread-badge">Mới</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="notification-message">
                                    {{ $notification->message }}
                                </div>
                                @if($notification->data && isset($notification->data['order_id']))
                                    <div class="notification-actions mt-15">
                                        <a href="{{ route('client.order.show', $notification->data['order_id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye mr-5"></i>Xem đơn hàng
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @if(!$notification->isRead())
                                <div class="notification-actions">
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="markAsRead({{ $notification->id }})">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-notifications">
                            <div class="text-center py-50">
                                <i class="fa fa-bell-slash fa-3x text-muted mb-20"></i>
                                <h4 class="text-muted">Chưa có thông báo nào</h4>
                                <p class="text-muted">Khi có thông báo mới, chúng sẽ xuất hiện ở đây.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="text-center mt-30">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.notifications-section {
    background: #f8f9fa;
    min-height: 100vh;
}

.notifications-header {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.notification-item {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: flex-start;
    gap: 20px;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.notification-item.unread {
    border-left-color: #007bff;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.notification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.notification-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.notification-content {
    flex: 1;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.notification-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-time {
    font-size: 0.9rem;
    color: #6c757d;
}

.unread-badge {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
}

.notification-message {
    color: #495057;
    line-height: 1.6;
}

.notification-actions {
    flex-shrink: 0;
}

.empty-notifications {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .notification-item {
        flex-direction: column;
        gap: 15px;
    }
    
    .notification-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .notification-actions {
        align-self: flex-end;
    }
}
</style>

<script>
function markAsRead(notificationId) {
    fetch(`/order/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.getElementById(`notification-${notificationId}`);
            notification.classList.remove('unread');
            notification.classList.add('read');
            
            // Remove unread badge
            const badge = notification.querySelector('.unread-badge');
            if (badge) {
                badge.remove();
            }
            
            // Remove mark as read button
            const markButton = notification.querySelector('.notification-actions .btn');
            if (markButton) {
                markButton.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    fetch('/order/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to update all notifications
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection 
