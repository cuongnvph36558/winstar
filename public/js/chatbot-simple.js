// Simple Chatbot for Testing
console.log('Chatbot script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing chatbot...');
    
    // Check if elements exist
    const toggleBtn = document.getElementById('chatWidgetToggle');
    const chatWidget = document.getElementById('chatWidget');
    const closeBtn = document.getElementById('closeChatWidget');
    const sendBtn = document.getElementById('widgetSendBtn');
    const messageInput = document.getElementById('widgetMessageInput');
    const messagesContainer = document.getElementById('chatWidgetMessages');
    
    console.log('Elements found:', {
        toggleBtn: !!toggleBtn,
        chatWidget: !!chatWidget,
        closeBtn: !!closeBtn,
        sendBtn: !!sendBtn,
        messageInput: !!messageInput,
        messagesContainer: !!messagesContainer
    });
    
    if (!toggleBtn || !chatWidget) {
        console.error('Required chatbot elements not found!');
        return;
    }
    
    // Simple chatbot functionality
    let isOpen = false;
    
    // Toggle chat
    toggleBtn.addEventListener('click', function() {
        console.log('Toggle button clicked');
        if (isOpen) {
            chatWidget.classList.remove('active');
            toggleBtn.classList.remove('hidden');
            isOpen = false;
        } else {
            chatWidget.classList.add('active');
            toggleBtn.classList.add('hidden');
            isOpen = true;
            
            // Focus on input
            setTimeout(() => {
                if (messageInput) {
                    messageInput.focus();
                }
            }, 300);
        }
    });
    
    // Close chat
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            console.log('Close button clicked');
            chatWidget.classList.remove('active');
            toggleBtn.classList.remove('hidden');
            isOpen = false;
        });
    }
    
    // Send message
    if (sendBtn && messageInput) {
        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    function sendMessage() {
        const message = messageInput.value.trim();
        if (message) {
            console.log('Sending message:', message);
            
            // Add user message
            addMessage(message, 'user');
            
            // Clear input
            messageInput.value = '';
            
            // Simulate bot response
            setTimeout(() => {
                const response = getBotResponse(message);
                addMessage(response, 'bot');
            }, 1000);
        }
    }
    
    function addMessage(text, sender) {
        if (!messagesContainer) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const time = new Date().toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        messageDiv.innerHTML = `
            <div class="message-content">
                ${text.replace(/\n/g, '<br>')}
                <div class="message-time">${time}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }
    
    function getBotResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        if (lowerMessage.includes('xin chào') || lowerMessage.includes('hello')) {
            return 'Xin chào! Tôi là trợ lý ảo của Winstar. Tôi có thể giúp bạn tìm hiểu về sản phẩm, đặt hàng, hoặc hỗ trợ khác. Bạn cần gì không?';
        }
        
        if (lowerMessage.includes('hỗ trợ') || lowerMessage.includes('giúp')) {
            return 'Tôi rất sẵn lòng hỗ trợ bạn! Bạn có thể cho tôi biết cụ thể vấn đề bạn đang gặp phải không?';
        }
        
        if (lowerMessage.includes('sản phẩm')) {
            return 'Chúng tôi có nhiều sản phẩm chất lượng cao. Bạn quan tâm đến sản phẩm nào cụ thể? Tôi có thể giúp bạn tìm hiểu chi tiết.';
        }
        
        if (lowerMessage.includes('đặt hàng')) {
            return 'Để đặt hàng, bạn có thể:\n1. Chọn sản phẩm từ danh mục\n2. Thêm vào giỏ hàng\n3. Điền thông tin giao hàng\n4. Chọn phương thức thanh toán\n5. Xác nhận đơn hàng';
        }
        
        return 'Xin lỗi, tôi chưa hiểu rõ câu hỏi của bạn. Bạn có thể thử hỏi lại hoặc chọn một trong các tùy chọn bên dưới.';
    }
    
    function scrollToBottom() {
        if (messagesContainer) {
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);
        }
    }
    
    console.log('Chatbot initialized successfully!');
    
    // Make chatbot globally accessible
    window.simpleChatbot = {
        open: function() {
            chatWidget.classList.add('active');
            toggleBtn.classList.add('hidden');
            isOpen = true;
        },
        close: function() {
            chatWidget.classList.remove('active');
            toggleBtn.classList.remove('hidden');
            isOpen = false;
        },
        isOpen: function() {
            return isOpen;
        }
    };
}); 