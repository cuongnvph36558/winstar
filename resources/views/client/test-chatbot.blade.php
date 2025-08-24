@extends('layouts.client')

@section('title', 'Kiểm tra Chatbot')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h1>Test Chatbot</h1>
                    <p>Trang này để test chatbot. Hãy nhìn vào góc phải dưới để thấy nút chat.</p>
                    <p>Nếu không thấy, hãy kiểm tra console để xem lỗi.</p>
                    
                    <div class="mt-4">
                        <button class="btn btn-primary" onclick="testChatbot()">Test Chatbot</button>
                        <button class="btn btn-success" onclick="openChatbot()">Mở Chatbot</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testChatbot() {
    if (typeof window.simpleChatbot !== 'undefined') {
        alert('Chatbot đã được load thành công!');
        // Chatbot object
    } else {
        alert('Chatbot chưa được load!');
        // Chatbot not found
    }
}

function openChatbot() {
    if (typeof window.simpleChatbot !== 'undefined') {
        window.simpleChatbot.open();
    } else {
        alert('Chatbot chưa được load!');
    }
}

// Debug: Log khi trang load
document.addEventListener('DOMContentLoaded', function() {
            // Page loaded
        // Chatbot object
    
    // Kiểm tra các elements
    const toggleBtn = document.getElementById('chatWidgetToggle');
    const chatWidget = document.getElementById('chatWidget');
    
            // Toggle button
        // Chat widget
});
</script>
@endsection 
