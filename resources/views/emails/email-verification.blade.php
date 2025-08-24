<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đăng ký tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .verification-code {
            background: #fff;
            border: 2px dashed #667eea;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Chào mừng bạn đến với Winstar!</h1>
        <p>Xin chào {{ $userName }}, cảm ơn bạn đã đăng ký tài khoản</p>
    </div>
    
    <div class="content">
        <h2>Xác nhận đăng ký tài khoản</h2>
        <p>Để hoàn tất quá trình đăng ký, vui lòng sử dụng mã xác nhận dưới đây:</p>
        
        <div class="verification-code">
            <div class="code">{{ $verificationCode }}</div>
            <p><strong>Mã xác nhận của bạn</strong></p>
        </div>
        
        <p><strong>Lưu ý quan trọng:</strong></p>
        <ul>
            <li>Mã xác nhận này có hiệu lực trong 15 phút</li>
            <li>Không chia sẻ mã này với bất kỳ ai</li>
            <li>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này</li>
        </ul>
        
        <p>Nếu bạn gặp vấn đề, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại hỗ trợ.</p>
        
        <p>Trân trọng,<br>
        <strong>Đội ngũ Winstar</strong></p>
    </div>
    
    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
        <p>&copy; {{ date('Y') }} Winstar. Tất cả quyền được bảo lưu.</p>
    </div>
</body>
</html> 
