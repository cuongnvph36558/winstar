{{-- Realtime Notifications Container --}}
<div id="realtime-notifications" class="realtime-notifications-container">
    {{-- Live Activity Feed --}}
    <div id="activity-feed" class="activity-feed" style="display: none;">
        <div class="activity-header">
            <span class="live-indicator"></span>
            <strong>Hoạt động realtime</strong>
            <button class="close-activity" onclick="toggleActivityFeed()">&times;</button>
        </div>
        <div class="activity-list" id="activity-list">
            {{-- Activities will be added here dynamically --}}
        </div>
    </div>
    
    {{-- Activity Toggle Button --}}
    <button id="activity-toggle" class="activity-toggle" onclick="toggleActivityFeed()" title="Xem hoạt động realtime">
        <i class="fa fa-bell"></i>
        <span class="activity-count" id="activity-count" style="display: none;">0</span>
    </button>
</div>

<style>
/* Realtime Notifications Styling */
.realtime-notifications-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.activity-toggle {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.activity-toggle:hover {
    background: #c0392b;
    transform: scale(1.1);
}

.activity-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.activity-feed {
    position: absolute;
    top: 60px;
    right: 0;
    width: 350px;
    max-height: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    overflow: hidden;
    animation: slideIn 0.3s ease;
    border: 1px solid #eee;
}

.activity-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.close-activity {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #666;
}

.activity-list {
    max-height: 300px;
    overflow-y: auto;
    padding: 0;
}

.activity-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    transition: background-color 0.2s ease;
}

.activity-item:hover {
    background: #f8f9fa;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    flex-shrink: 0;
}

.activity-icon.added {
    background: #28a745;
}

.activity-icon.removed {
    background: #6c757d;
}

.activity-content {
    flex: 1;
    font-size: 13px;
}

.activity-user {
    font-weight: 600;
    color: #333;
}

.activity-text {
    color: #666;
    margin: 2px 0;
}

.activity-time {
    color: #999;
    font-size: 11px;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile responsive */
@media (max-width: 480px) {
    .realtime-notifications-container {
        top: 10px;
        right: 10px;
    }
    
    .activity-feed {
        width: calc(100vw - 40px);
        right: -15px;
    }
}
</style>

<script>
// Activity Feed Management
let activityCount = 0;
let activityFeedVisible = false;

function toggleActivityFeed() {
    const feed = document.getElementById('activity-feed');
    const toggle = document.getElementById('activity-toggle');
    
    if (activityFeedVisible) {
        feed.style.display = 'none';
        activityFeedVisible = false;
    } else {
        feed.style.display = 'block';
        activityFeedVisible = true;
        // Reset count when opened
        resetActivityCount();
    }
}

function addActivityItem(data) {
    const list = document.getElementById('activity-list');
    const item = document.createElement('div');
    item.className = 'activity-item';
    
    const timeAgo = getTimeAgo(new Date(data.timestamp));
    
    item.innerHTML = `
        <div class="activity-icon ${data.action}">
            <i class="fa fa-heart"></i>
        </div>
        <div class="activity-content">
            <div class="activity-user">${data.user_name}</div>
            <div class="activity-text">${data.action === 'added' ? 'đã thích' : 'đã bỏ thích'} "${data.product_name}"</div>
            <div class="activity-time">${timeAgo}</div>
        </div>
    `;
    
    // Add to top of list
    list.insertBefore(item, list.firstChild);
    
    // Remove old items if more than 20
    while (list.children.length > 20) {
        list.removeChild(list.lastChild);
    }
    
    // Update count if feed is not visible
    if (!activityFeedVisible) {
        incrementActivityCount();
    }
}

function incrementActivityCount() {
    activityCount++;
    const countElement = document.getElementById('activity-count');
    countElement.textContent = activityCount;
    countElement.style.display = activityCount > 0 ? 'flex' : 'none';
}

function resetActivityCount() {
    activityCount = 0;
    const countElement = document.getElementById('activity-count');
    countElement.style.display = 'none';
}

function getTimeAgo(date) {
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return 'Vừa xong';
    if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
    if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
    return Math.floor(diff / 86400) + ' ngày trước';
}

// Close activity feed when clicking outside
document.addEventListener('click', function(event) {
    const container = document.getElementById('realtime-notifications');
    if (activityFeedVisible && !container.contains(event.target)) {
        toggleActivityFeed();
    }
});
</script> 