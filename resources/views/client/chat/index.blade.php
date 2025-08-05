@extends('layouts.client')

@section('title', 'Chat')

@section('content')
<div class="chat-container">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="chat-users-panel">
                    <div class="panel-header">
                        <h4><i class="fa fa-users"></i> Danh sách chat</h4>
                    </div>
                    <div class="users-list" id="usersList">
                        @foreach($users as $user)
                        <div class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="user-avatar">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="avatar-placeholder">{{ substr($user->name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-status">
                                    <span class="status-dot online"></span>
                                    <span class="status-text">Online</span>
                                </div>
                            </div>
                            <div class="unread-badge" id="unread-{{ $user->id }}" style="display: none;">
                                <span class="badge">0</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="chat-messages-panel">
                    <div class="chat-header" id="chatHeader" style="display: none;">
                        <div class="chat-user-info">
                            <div class="user-avatar">
                                <img src="" alt="" id="chatUserAvatar">
                            </div>
                            <div class="user-details">
                                <h5 id="chatUserName"></h5>
                                <span class="user-status">Online</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-messages" id="chatMessages">
                        <div class="no-chat-selected">
                            <i class="fa fa-comments"></i>
                            <h4>Chọn người dùng để bắt đầu chat</h4>
                            <p>Chọn một người dùng từ danh sách bên trái để bắt đầu cuộc trò chuyện</p>
                        </div>
                    </div>
                    
                    <div class="chat-input" id="chatInput" style="display: none;">
                        <div class="input-group">
                            <input type="text" class="form-control" id="messageInput" placeholder="Nhập tin nhắn...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="sendMessageBtn">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Widget -->
<div class="chat-widget" id="chatWidget">
    <div class="chat-widget-header">
        <h5><i class="fa fa-comments"></i> Chat Support</h5>
        <button class="btn-close" id="closeChatWidget">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="chat-widget-messages" id="chatWidgetMessages">
        <div class="welcome-message">
            <p>Xin chào! Bạn cần hỗ trợ gì không?</p>
        </div>
    </div>
    <div class="chat-widget-input">
        <input type="text" id="widgetMessageInput" placeholder="Nhập tin nhắn...">
        <button id="widgetSendBtn">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>
</div>

<div class="chat-widget-toggle" id="chatWidgetToggle">
    <i class="fa fa-comments"></i>
    <span class="notification-badge" id="widgetNotificationBadge" style="display: none;">0</span>
</div>
@endsection

@section('styles')
<style>
.chat-container {
    padding: 20px 0;
    min-height: 600px;
}

.chat-users-panel, .chat-messages-panel {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 600px;
    display: flex;
    flex-direction: column;
}

.panel-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.panel-header h4 {
    margin: 0;
    color: #333;
    font-size: 16px;
}

.users-list {
    flex: 1;
    overflow-y: auto;
    padding: 0;
}

.user-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.user-item:hover {
    background-color: #f8f9fa;
}

.user-item.active {
    background-color: #e3f2fd;
    border-left: 4px solid #2196f3;
}

.user-avatar {
    margin-right: 15px;
}

.user-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2196f3;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}

.user-info {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
}

.user-status {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #666;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 5px;
}

.status-dot.online {
    background: #4caf50;
}

.unread-badge {
    margin-left: 10px;
}

.unread-badge .badge {
    background: #f44336;
    color: white;
    border-radius: 50%;
    padding: 4px 8px;
    font-size: 10px;
    min-width: 20px;
}

.chat-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.chat-user-info {
    display: flex;
    align-items: center;
}

.chat-user-info .user-avatar {
    margin-right: 15px;
}

.chat-user-info .user-avatar img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.no-chat-selected {
    text-align: center;
    color: #666;
    padding: 50px 20px;
}

.no-chat-selected i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 20px;
}

.no-chat-selected h4 {
    margin-bottom: 10px;
    color: #333;
}

.message-item {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-end;
}

.message-item.sent {
    justify-content: flex-end;
}

.message-item.received {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 18px;
    position: relative;
    word-wrap: break-word;
}

.message-item.sent .message-bubble {
    background: #2196f3;
    color: white;
    border-bottom-right-radius: 5px;
}

.message-item.received .message-bubble {
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
    border-bottom-left-radius: 5px;
}

.message-time {
    font-size: 11px;
    margin-top: 5px;
    opacity: 0.7;
}

.chat-input {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    background: white;
    border-radius: 0 0 8px 8px;
}

.chat-input .input-group {
    display: flex;
}

.chat-input .form-control {
    border: 1px solid #ddd;
    border-radius: 20px 0 0 20px;
    padding: 10px 15px;
}

.chat-input .btn {
    border-radius: 0 20px 20px 0;
    padding: 10px 15px;
}

/* Chat Widget Styles */
.chat-widget {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 300px;
    height: 400px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    display: none;
    flex-direction: column;
    z-index: 1000;
}

.chat-widget.show {
    display: flex;
}

.chat-widget-header {
    background: #2196f3;
    color: white;
    padding: 15px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-widget-header h5 {
    margin: 0;
    font-size: 14px;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 16px;
}

.chat-widget-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f8f9fa;
}

.welcome-message {
    text-align: center;
    color: #666;
    font-size: 14px;
}

.chat-widget-input {
    padding: 15px;
    display: flex;
    gap: 10px;
    background: white;
    border-radius: 0 0 10px 10px;
}

.chat-widget-input input {
    flex: 1;
    border: 1px solid #ddd;
    border-radius: 20px;
    padding: 8px 15px;
    font-size: 14px;
}

.chat-widget-input button {
    background: #2196f3;
    color: white;
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-widget-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: #2196f3;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    z-index: 999;
    transition: transform 0.2s;
}

.chat-widget-toggle:hover {
    transform: scale(1.1);
}

.chat-widget-toggle i {
    font-size: 24px;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #f44336;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

@media (max-width: 768px) {
    .chat-container .col-md-4 {
        display: none;
    }
    
    .chat-container .col-md-8 {
        width: 100%;
    }
    
    .chat-widget {
        width: 100%;
        height: 100%;
        bottom: 0;
        right: 0;
        border-radius: 0;
    }
    
    .chat-widget-header {
        border-radius: 0;
    }
    
    .chat-widget-input {
        border-radius: 0;
    }
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentChatUser = null;
    let messagePolling = null;

    // xử lý click vào user để bắt đầu chat
    $('.user-item').click(function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        const userAvatar = $(this).find('img').attr('src') || '';
        
        // cập nhật ui
        $('.user-item').removeClass('active');
        $(this).addClass('active');
        
        // hiển thị chat header
        $('#chatUserName').text(userName);
        $('#chatUserAvatar').attr('src', userAvatar);
        $('#chatHeader').show();
        $('#chatInput').show();
        
        // ẩn no-chat-selected
        $('.no-chat-selected').hide();
        
        currentChatUser = userId;
        
        // load tin nhắn
        loadMessages(userId);
        
        // bắt đầu polling tin nhắn mới
        startMessagePolling(userId);
    });

    // gửi tin nhắn
    $('#sendMessageBtn').click(sendMessage);
    $('#messageInput').keypress(function(e) {
        if (e.which == 13) {
            sendMessage();
        }
    });

    function sendMessage() {
        const content = $('#messageInput').val().trim();
        if (!content || !currentChatUser) return;

        $.ajax({
            url: '{{ route("client.chat.send") }}',
            method: 'POST',
            data: {
                receiver_id: currentChatUser,
                content: content,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#messageInput').val('');
                appendMessage(response, 'sent');
            },
            error: function() {
                alert('có lỗi xảy ra khi gửi tin nhắn');
            }
        });
    }

    function loadMessages(userId) {
        $.ajax({
            url: `/chat/messages/${userId}`,
            method: 'GET',
            success: function(messages) {
                displayMessages(messages);
            }
        });
    }

    function displayMessages(messages) {
        const chatMessages = $('#chatMessages');
        chatMessages.empty();
        
        messages.forEach(function(message) {
            const isSent = message.sender_id == {{ Auth::id() }};
            appendMessage(message, isSent ? 'sent' : 'received');
        });
        
        scrollToBottom();
    }

    function appendMessage(message, type) {
        const messageHtml = `
            <div class="message-item ${type}">
                <div class="message-bubble">
                    <div class="message-content">${message.content}</div>
                    <div class="message-time">${formatTime(message.created_at)}</div>
                </div>
            </div>
        `;
        
        $('#chatMessages').append(messageHtml);
        scrollToBottom();
    }

    function scrollToBottom() {
        const chatMessages = $('#chatMessages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }

    function formatTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleTimeString('vi-VN', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }

    function startMessagePolling(userId) {
        if (messagePolling) {
            clearInterval(messagePolling);
        }
        
        messagePolling = setInterval(function() {
            if (currentChatUser == userId) {
                loadMessages(userId);
            }
        }, 3000);
    }

    // chat widget functionality
    $('#chatWidgetToggle').click(function() {
        $('#chatWidget').toggleClass('show');
    });

    $('#closeChatWidget').click(function() {
        $('#chatWidget').removeClass('show');
    });

    $('#widgetSendBtn').click(sendWidgetMessage);
    $('#widgetMessageInput').keypress(function(e) {
        if (e.which == 13) {
            sendWidgetMessage();
        }
    });

    function sendWidgetMessage() {
        const content = $('#widgetMessageInput').val().trim();
        if (!content) return;

        // thêm tin nhắn của user vào widget
        const messageHtml = `
            <div style="text-align: right; margin-bottom: 10px;">
                <div style="background: #2196f3; color: white; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%;">
                    ${content}
                </div>
            </div>
        `;
        
        $('#chatWidgetMessages').append(messageHtml);
        $('#widgetMessageInput').val('');
        
        // scroll xuống dưới
        const widgetMessages = $('#chatWidgetMessages');
        widgetMessages.scrollTop(widgetMessages[0].scrollHeight);
        
        // gửi tin nhắn đến chatbot
        $.ajax({
            url: '{{ route("client.chatbot.process") }}',
            method: 'POST',
            data: {
                message: content,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // hiển thị phản hồi từ chatbot
                let responseHtml = '';
                
                if (response.type === 'text') {
                    responseHtml = `
                        <div style="text-align: left; margin-bottom: 10px;">
                            <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                ${response.content.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                } else if (response.type === 'product_list') {
                    responseHtml = `
                        <div style="text-align: left; margin-bottom: 10px;">
                            <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                ${response.content.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                } else if (response.type === 'category_list') {
                    responseHtml = `
                        <div style="text-align: left; margin-bottom: 10px;">
                            <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                ${response.content.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                }
                
                $('#chatWidgetMessages').append(responseHtml);
                widgetMessages.scrollTop(widgetMessages[0].scrollHeight);
                
                // hiển thị suggestions nếu có
                if (response.suggestions && response.suggestions.length > 0) {
                    let suggestionsHtml = '<div style="text-align: left; margin-bottom: 10px;">';
                    suggestionsHtml += '<div style="background: #f8f9fa; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">';
                    suggestionsHtml += '<div style="font-size: 12px; color: #666; margin-bottom: 5px;">Gợi ý:</div>';
                    
                    response.suggestions.forEach(function(suggestion) {
                        suggestionsHtml += `<button class="suggestion-btn" style="background: #e3f2fd; border: 1px solid #2196f3; color: #2196f3; padding: 4px 8px; margin: 2px; border-radius: 12px; font-size: 11px; cursor: pointer;">${suggestion}</button>`;
                    });
                    
                    suggestionsHtml += '</div></div>';
                    $('#chatWidgetMessages').append(suggestionsHtml);
                    widgetMessages.scrollTop(widgetMessages[0].scrollHeight);
                }
            },
            error: function() {
                // phản hồi mặc định nếu có lỗi
                const responseHtml = `
                    <div style="text-align: left; margin-bottom: 10px;">
                        <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                            xin lỗi, tôi đang gặp sự cố. bạn có thể thử lại sau hoặc liên hệ trực tiếp với chúng tôi.
                        </div>
                    </div>
                `;
                
                $('#chatWidgetMessages').append(responseHtml);
                widgetMessages.scrollTop(widgetMessages[0].scrollHeight);
            }
        });
    }

    // cập nhật số tin nhắn chưa đọc
    function updateUnreadCount() {
        $.ajax({
            url: '{{ route("client.chat.unread-count") }}',
            method: 'GET',
            success: function(response) {
                if (response.count > 0) {
                    $('#widgetNotificationBadge').text(response.count).show();
                } else {
                    $('#widgetNotificationBadge').hide();
                }
            }
        });
    }

    // xử lý click vào suggestion buttons
    $(document).on('click', '.suggestion-btn', function() {
        const suggestionText = $(this).text();
        $('#widgetMessageInput').val(suggestionText);
        sendWidgetMessage();
    });

    // cập nhật số tin nhắn chưa đọc mỗi 10 giây
    setInterval(updateUnreadCount, 10000);
    updateUnreadCount();
});
</script>
@endsection 