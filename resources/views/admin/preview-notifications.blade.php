@extends('layouts.admin')

@section('title', 'Preview Admin Notifications')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>🧪 Preview Admin Notifications</h5>
                </div>
                <div class="ibox-content">
                    
                    <!-- System Status -->
                    <div class="alert alert-info">
                        <h6>📊 System Status</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <span class="badge badge-success" id="echo-status">Checking...</span>
                                <small>Echo Connection</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge badge-success" id="sweetalert-status">Checking...</span>
                                <small>SweetAlert2</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge badge-info" id="user-status">Checking...</span>
                                <small>Admin Auth</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge badge-warning" id="websocket-status">Checking...</span>
                                <small>WebSocket</small>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Notifications -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6>🔔 Manual Notifications</h6>
                            <div class="btn-group" role="group">
                                <button class="btn btn-success" onclick="testManualNotification('success', 'Thành công!', 'Đây là thông báo thành công')">
                                    ✅ Success
                                </button>
                                <button class="btn btn-info" onclick="testManualNotification('info', 'Thông tin', 'Đây là thông báo thông tin')">
                                    ℹ️ Info
                                </button>
                                <button class="btn btn-warning" onclick="testManualNotification('warning', 'Cảnh báo', 'Đây là thông báo cảnh báo')">
                                    ⚠️ Warning
                                </button>
                                <button class="btn btn-danger" onclick="testManualNotification('error', 'Lỗi!', 'Đây là thông báo lỗi')">
                                    ❌ Error
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status Notifications -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6>📦 Order Status Notifications</h6>
                            <div class="btn-group" role="group">
                                <button class="btn btn-warning btn-sm" onclick="testOrderNotification('pending')">
                                    ⏳ Pending
                                </button>
                                <button class="btn btn-info btn-sm" onclick="testOrderNotification('processing')">
                                    ⚙️ Processing
                                </button>
                                <button class="btn btn-primary btn-sm" onclick="testOrderNotification('shipping')">
                                    🚚 Shipping
                                </button>
                                <button class="btn btn-success btn-sm" onclick="testOrderNotification('completed')">
                                    ✅ Completed
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="testOrderNotification('cancelled')">
                                    ❌ Cancelled
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Real Broadcast Test -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6>📡 Real Broadcast Test</h6>
                            <div class="btn-group" role="group">
                                <button class="btn btn-dark" onclick="testRealBroadcast('order')">
                                    📦 Test Order Broadcast
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Console Logs -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6>📋 Console Logs</h6>
                            <button class="btn btn-sm btn-outline-secondary mb-2" onclick="clearLogs()">Clear Logs</button>
                            <div id="console-logs" style="height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; font-family: monospace; font-size: 12px; border: 1px solid #dee2e6; border-radius: 5px;">
                                <div class="text-muted">Console logs will appear here...</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Override console.log to show in our log area
const originalLog = console.log;
const originalError = console.error;
const originalWarn = console.warn;

function addLog(message, type = 'info') {
    const logsDiv = document.getElementById('console-logs');
    const logEntry = document.createElement('div');
    logEntry.className = `log-entry ${type}`;
    logEntry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
    logsDiv.appendChild(logEntry);
    logsDiv.scrollTop = logsDiv.scrollHeight;
}

console.log = function(...args) {
    originalLog.apply(console, args);
    addLog(args.join(' '), 'info');
};

console.error = function(...args) {
    originalError.apply(console, args);
    addLog(args.join(' '), 'error');
};

console.warn = function(...args) {
    originalWarn.apply(console, args);
    addLog(args.join(' '), 'warning');
};

function clearLogs() {
    document.getElementById('console-logs').innerHTML = '<div class="text-muted">Console logs cleared...</div>';
}

// Check system status
function checkSystemStatus() {
    // Check SweetAlert2
    const sweetalertStatus = document.getElementById('sweetalert-status');
    if (typeof Swal !== 'undefined') {
        sweetalertStatus.textContent = 'Available';
        sweetalertStatus.className = 'badge badge-success';
    } else {
        sweetalertStatus.textContent = 'Not Available';
        sweetalertStatus.className = 'badge badge-danger';
    }

    // Check Echo
    const echoStatus = document.getElementById('echo-status');
    if (typeof window.Echo !== 'undefined') {
        echoStatus.textContent = 'Available';
        echoStatus.className = 'badge badge-success';
    } else {
        echoStatus.textContent = 'Not Available';
        echoStatus.className = 'badge badge-danger';
    }

    // Check User Auth
    const userStatus = document.getElementById('user-status');
    if (window.currentUserId) {
        userStatus.textContent = 'Authenticated';
        userStatus.className = 'badge badge-success';
    } else {
        userStatus.textContent = 'Guest';
        userStatus.className = 'badge badge-warning';
    }

    // Check WebSocket
    const websocketStatus = document.getElementById('websocket-status');
    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        const state = window.Echo.connector.pusher.connection.state;
        websocketStatus.textContent = state;
        websocketStatus.className = state === 'connected' ? 'badge badge-success' : 'badge badge-warning';
    } else {
        websocketStatus.textContent = 'Not Connected';
        websocketStatus.className = 'badge badge-danger';
    }
}

// Test manual notifications
function testManualNotification(type, title, message) {
    try {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                timer: 4000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timerProgressBar: true
            });
            console.log(`✅ Manual ${type} notification shown: ${title} - ${message}`);
        } else {
            console.error('❌ SweetAlert2 not available');
            alert(`${title}: ${message}`);
        }
    } catch (error) {
        console.error('❌ Error showing manual notification:', error);
        alert(`${title}: ${message}`);
    }
}

// Test order notifications
function testOrderNotification(status) {
    const statusTexts = {
        'pending': 'Chờ xử lý',
        'processing': 'Đang xử lý',
        'shipping': 'Đang giao hàng',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy',
        'failed': 'Thất bại'
    };

    const data = {
        user_id: window.currentUserId || 1,
        user_name: '{{ auth()->user()->name ?? "Admin User" }}',
        order_code: 'ORD' + Math.floor(Math.random() * 10000),
        new_status: status,
        status_text: statusTexts[status],
        updated_at: new Date().toISOString(),
        message: `Đơn hàng đã chuyển sang trạng thái ${statusTexts[status]}`
    };

    // Show toast
    if (window.RealtimeNotifications && window.RealtimeNotifications.showToast) {
        window.RealtimeNotifications.showToast(
            status === 'completed' ? 'success' : 
            status === 'cancelled' || status === 'failed' ? 'error' : 'info',
            'Cập nhật đơn hàng',
            data.message
        );
    }

    console.log(`📦 Order ${status} notification:`, data);
}

// Test real broadcast
function testRealBroadcast(type) {
    fetch('/test-order-broadcast', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(`📡 ${type} broadcast result:`, data);
        if (data.success) {
            testManualNotification('success', 'Broadcast Sent', `${type} broadcast sent successfully`);
        } else {
            testManualNotification('error', 'Broadcast Failed', data.message);
        }
    })
    .catch(error => {
        console.error(`❌ ${type} broadcast failed:`, error);
        testManualNotification('error', 'Broadcast Error', error.message);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== ADMIN PREVIEW NOTIFICATIONS PAGE LOADED ===');
    checkSystemStatus();
    
    // Check status every 5 seconds
    setInterval(checkSystemStatus, 5000);
    
    console.log('✅ Admin preview page initialized');
});
</script>

<style>
.log-entry {
    margin-bottom: 5px;
    padding: 2px 0;
}

.log-entry.success { color: #28a745; }
.log-entry.error { color: #dc3545; }
.log-entry.warning { color: #ffc107; }
.log-entry.info { color: #17a2b8; }

.badge {
    display: inline-block;
    margin-right: 10px;
    font-size: 12px;
}
</style>
@endsection 