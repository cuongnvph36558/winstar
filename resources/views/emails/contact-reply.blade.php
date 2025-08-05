<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi liên hệ</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .reply-box {
            background-color: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .info {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Phản hồi liên hệ</h1>
            <p>Xin chào {{ $user_name }},</p>
        </div>

        <div class="content">
            <p>Chúng tôi đã nhận được phản hồi cho tin nhắn của bạn. Dưới đây là chi tiết:</p>

            <div class="info">
                <strong>Tiêu đề:</strong> {{ $contact_subject }}<br>
                <strong>Thời gian gửi:</strong> {{ $reply_date }}
            </div>

            <h3>📝 Tin nhắn của bạn:</h3>
            <div class="message-box">
                {{ $contact_message }}
            </div>

            <h3>💬 Phản hồi từ chúng tôi:</h3>
            <div class="reply-box">
                {{ $admin_reply }}
            </div>

            <p>Nếu bạn có thêm câu hỏi hoặc cần hỗ trợ thêm, vui lòng không ngần ngại liên hệ lại với chúng tôi.</p>

            <div style="text-align: center;">
                <a href="{{ $contact_url }}" class="btn">Xem chi tiết trên website</a>
            </div>
        </div>

        <div class="footer">
            <p>Trân trọng,<br>
            <strong>Đội ngũ hỗ trợ Winstar</strong></p>
            
            <p style="font-size: 12px; color: #999;">
                Email này được gửi tự động. Vui lòng không trả lời email này.<br>
                Nếu bạn có câu hỏi, vui lòng sử dụng form liên hệ trên website.
            </p>
        </div>
    </div>
</body>
</html> 