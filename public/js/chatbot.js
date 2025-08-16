// Chatbot JavaScript
class Chatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.messages = [];
        this.suggestions = [
            'Xin chào',
            'Tôi cần hỗ trợ',
            'Thông tin sản phẩm',
            'Đặt hàng như thế nào?',
            'Chính sách đổi trả',
            'Liên hệ tư vấn'
        ];
        
        this.responses = {
            'xin chào': 'Xin chào! Tôi là trợ lý ảo của Winstar. Tôi có thể giúp bạn tìm hiểu về sản phẩm, đặt hàng, hoặc hỗ trợ khác. Bạn cần gì không?',
            'tôi cần hỗ trợ': 'Tôi rất sẵn lòng hỗ trợ bạn! Bạn có thể cho tôi biết cụ thể vấn đề bạn đang gặp phải không?',
            'thông tin sản phẩm': 'Chúng tôi có nhiều sản phẩm chất lượng cao. Bạn quan tâm đến sản phẩm nào cụ thể? Tôi có thể giúp bạn tìm hiểu chi tiết.',
            'đặt hàng như thế nào': 'Để đặt hàng, bạn có thể:\n1. Chọn sản phẩm từ danh mục\n2. Thêm vào giỏ hàng\n3. Điền thông tin giao hàng\n4. Chọn phương thức thanh toán\n5. Xác nhận đơn hàng\nBạn cần hỗ trợ thêm gì không?',
            'chính sách đổi trả': 'Chúng tôi có chính sách đổi trả trong vòng 30 ngày kể từ ngày nhận hàng. Sản phẩm phải còn nguyên vẹn và đầy đủ phụ kiện. Bạn có cần tư vấn thêm không?',
            'liên hệ tư vấn': 'Bạn có thể liên hệ với chúng tôi qua:\n📞 Hotline: 1900-xxxx\n📧 Email: support@winstar.com\n💬 Chat trực tuyến (như hiện tại)\n⏰ Giờ làm việc: 8h-22h hàng ngày'
        };
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadChatHistory();
        this.showSuggestions();
    }
    
    bindEvents() {
        // Toggle chatbot
        const toggleBtn = document.getElementById('chatWidgetToggle');
        const closeBtn = document.getElementById('closeChatWidget');
        const chatWidget = document.getElementById('chatWidget');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleChat());
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeChat());
        }
        
        // Send message
        const sendBtn = document.getElementById('widgetSendBtn');
        const messageInput = document.getElementById('widgetMessageInput');
        
        if (sendBtn) {
            sendBtn.addEventListener('click', () => this.sendMessage());
        }
        
        if (messageInput) {
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.sendMessage();
                }
            });
        }
        
        // Suggestion buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('suggestion-btn')) {
                const text = e.target.textContent;
                this.addMessage(text, 'user');
                this.processMessage(text);
            }
        });
    }
    
    async loadChatHistory() {
        try {
            const response = await fetch('/chat/history', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.messages.length > 0) {
                    this.messages = data.messages;
                    this.displayChatHistory();
                }
            }
        } catch (error) {
            console.error('Error loading chat history:', error);
        }
    }
    
    displayChatHistory() {
        const messagesContainer = document.getElementById('chatWidgetMessages');
        if (!messagesContainer) return;
        
        // Clear existing messages except welcome message
        const welcomeMessage = messagesContainer.querySelector('.welcome-message');
        messagesContainer.innerHTML = '';
        if (welcomeMessage) {
            messagesContainer.appendChild(welcomeMessage);
        }
        
        // Display chat history
        this.messages.forEach(msg => {
            this.addMessageToDOM(msg.message, msg.sender, msg.timestamp);
        });
        
        this.scrollToBottom();
    }
    
    toggleChat() {
        const chatWidget = document.getElementById('chatWidget');
        const toggleBtn = document.getElementById('chatWidgetToggle');
        
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }
    
    openChat() {
        const chatWidget = document.getElementById('chatWidget');
        const toggleBtn = document.getElementById('chatWidgetToggle');
        
        if (chatWidget && toggleBtn) {
            chatWidget.classList.add('active');
            toggleBtn.classList.add('hidden');
            this.isOpen = true;
            
            // Mark messages as read when opening chat
            this.markMessagesAsRead();
            
            // Focus on input
            setTimeout(() => {
                const messageInput = document.getElementById('widgetMessageInput');
                if (messageInput) {
                    messageInput.focus();
                }
            }, 300);
        }
    }
    
    closeChat() {
        const chatWidget = document.getElementById('chatWidget');
        const toggleBtn = document.getElementById('chatWidgetToggle');
        
        if (chatWidget && toggleBtn) {
            chatWidget.classList.remove('active');
            toggleBtn.classList.remove('hidden');
            this.isOpen = false;
        }
    }
    
    async sendMessage() {
        const messageInput = document.getElementById('widgetMessageInput');
        const message = messageInput.value.trim();
        
        if (message) {
            this.addMessage(message, 'user');
            messageInput.value = '';
            await this.sendMessageToServer(message);
        }
    }
    
    async sendMessageToServer(message) {
        try {
            const response = await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message }),
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.addMessage(data.response, 'bot');
                    this.showSuggestions();
                }
            } else {
                // Fallback to local processing if server fails
                this.processMessage(message);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            // Fallback to local processing
            this.processMessage(message);
        }
    }
    
    addMessage(text, sender) {
        const time = new Date().toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        this.addMessageToDOM(text, sender, time);
        
        // Store message
        this.messages.push({
            text: text,
            sender: sender,
            timestamp: time,
            date: new Date().toLocaleDateString('vi-VN')
        });
    }
    
    addMessageToDOM(text, sender, time) {
        const messagesContainer = document.getElementById('chatWidgetMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        messageDiv.innerHTML = `
            <div class="message-content">
                ${this.formatMessage(text)}
                <div class="message-time">${time}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }
    
    formatMessage(text) {
        // Convert line breaks to <br> tags
        return text.replace(/\n/g, '<br>');
    }
    
    processMessage(message) {
        const lowerMessage = message.toLowerCase();
        
        // Show typing indicator
        this.showTypingIndicator();
        
        // Simulate processing delay
        setTimeout(() => {
            this.hideTypingIndicator();
            
            // Find matching response
            let response = this.getResponse(lowerMessage);
            
            if (!response) {
                response = 'Xin lỗi, tôi chưa hiểu rõ câu hỏi của bạn. Bạn có thể thử hỏi lại hoặc chọn một trong các tùy chọn bên dưới.';
            }
            
            this.addMessage(response, 'bot');
            this.showSuggestions();
        }, 1000 + Math.random() * 1000); // Random delay between 1-2 seconds
    }
    
    getResponse(message) {
        // Check for exact matches first
        for (const [key, response] of Object.entries(this.responses)) {
            if (message.includes(key)) {
                return response;
            }
        }
        
        // Check for partial matches
        const words = message.split(' ');
        for (const word of words) {
            for (const [key, response] of Object.entries(this.responses)) {
                if (key.includes(word) || word.includes(key)) {
                    return response;
                }
            }
        }
        
        return null;
    }
    
    showTypingIndicator() {
        const messagesContainer = document.getElementById('chatWidgetMessages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot typing-indicator-container';
        typingDiv.id = 'typingIndicator';
        
        typingDiv.innerHTML = `
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        
        messagesContainer.appendChild(typingDiv);
        this.scrollToBottom();
        this.isTyping = true;
    }
    
    hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
        this.isTyping = false;
    }
    
    showSuggestions() {
        const messagesContainer = document.getElementById('chatWidgetMessages');
        
        // Remove existing suggestions
        const existingSuggestions = messagesContainer.querySelector('.suggestion-buttons');
        if (existingSuggestions) {
            existingSuggestions.remove();
        }
        
        // Add new suggestions
        const suggestionsDiv = document.createElement('div');
        suggestionsDiv.className = 'suggestion-buttons';
        
        const shuffledSuggestions = this.shuffleArray([...this.suggestions]);
        const selectedSuggestions = shuffledSuggestions.slice(0, 4); // Show only 4 suggestions
        
        selectedSuggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'suggestion-btn';
            btn.textContent = suggestion;
            suggestionsDiv.appendChild(btn);
        });
        
        messagesContainer.appendChild(suggestionsDiv);
        this.scrollToBottom();
    }
    
    shuffleArray(array) {
        const shuffled = [...array];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        return shuffled;
    }
    
    scrollToBottom() {
        const messagesContainer = document.getElementById('chatWidgetMessages');
        if (messagesContainer) {
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);
        }
    }
    
    async markMessagesAsRead() {
        try {
            const unreadMessages = this.messages.filter(msg => msg.sender === 'bot' && !msg.is_read);
            if (unreadMessages.length > 0) {
                const messageIds = unreadMessages.map(msg => msg.id);
                
                await fetch('/chat/mark-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message_ids: messageIds }),
                });
                
                // Update local messages
                unreadMessages.forEach(msg => msg.is_read = true);
            }
        } catch (error) {
            console.error('Error marking messages as read:', error);
        }
    }
    
    async updateUnreadCount() {
        try {
            const response = await fetch('/chat/unread-count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            });
            
            if (response.ok) {
                const data = await response.json();
                const badge = document.getElementById('widgetNotificationBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        } catch (error) {
            console.error('Error updating unread count:', error);
        }
    }
    
    async clearChat() {
        try {
            const response = await fetch('/chat/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            });
            
            if (response.ok) {
                const messagesContainer = document.getElementById('chatWidgetMessages');
                if (messagesContainer) {
                    // Keep only welcome message
                    const welcomeMessage = messagesContainer.querySelector('.welcome-message');
                    messagesContainer.innerHTML = '';
                    if (welcomeMessage) {
                        messagesContainer.appendChild(welcomeMessage);
                    }
                }
                this.messages = [];
                this.showSuggestions();
            }
        } catch (error) {
            console.error('Error clearing chat:', error);
        }
    }
    
    // Method to add custom responses
    addResponse(trigger, response) {
        this.responses[trigger.toLowerCase()] = response;
    }
    
    // Method to get chat history
    getChatHistory() {
        return this.messages;
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.chatbot = new Chatbot();
    
    // Add some custom responses
    chatbot.addResponse('cảm ơn', 'Không có gì! Tôi rất vui được giúp đỡ bạn. Nếu bạn cần hỗ trợ thêm, đừng ngại liên hệ với tôi nhé!');
    chatbot.addResponse('tạm biệt', 'Tạm biệt! Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Chúc bạn một ngày tốt lành!');
    chatbot.addResponse('giá cả', 'Chúng tôi cam kết cung cấp sản phẩm chất lượng với giá cả cạnh tranh nhất. Bạn có thể xem chi tiết giá trên trang sản phẩm hoặc liên hệ tư vấn để được báo giá cụ thể.');
    chatbot.addResponse('giao hàng', 'Chúng tôi giao hàng toàn quốc với thời gian từ 1-3 ngày làm việc. Phí giao hàng tùy thuộc vào địa chỉ và phương thức giao hàng bạn chọn.');
    
    // Update unread count periodically
    setInterval(() => {
        chatbot.updateUnreadCount();
    }, 10000);
    
    // Auto-open chat after 30 seconds if user hasn't interacted
    setTimeout(() => {
        if (!chatbot.isOpen && !localStorage.getItem('chatbotOpened')) {
            chatbot.openChat();
            localStorage.setItem('chatbotOpened', 'true');
        }
    }, 30000);
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Chatbot;
} 