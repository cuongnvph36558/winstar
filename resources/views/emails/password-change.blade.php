<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đổi mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">WINSTAR</div>
            <div class="title">Thông báo đổi mật khẩu</div>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $userName }}</strong>,</p>

            <p>Chúng tôi nhận được yêu cầu đổi mật khẩu cho tài khoản của bạn.</p>

            <div class="info-box">
                <strong>Thông tin đổi mật khẩu:</strong><br>
                • Thời gian: {{ $changeTime }}<br>
                • Tài khoản: {{ $userName }}<br>
                • Trạng thái: <span style="color: #28a745;">Thành công</span>
            </div>

            <p>Nếu bạn đã thực hiện thay đổi này, vui lòng bỏ qua email này.</p>

            <div class="warning">
                <strong>⚠️ Lưu ý bảo mật:</strong><br>
                • Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ ngay với chúng tôi<br>
                • Không chia sẻ mật khẩu với bất kỳ ai<br>
                • Sử dụng mật khẩu mạnh và khác biệt cho mỗi tài khoản
            </div>

            <p>Nếu bạn cần hỗ trợ, vui lòng liên hệ với chúng tôi:</p>
            <ul>
                <li>Email: support@winstar.com</li>
                <li>Hotline: 1900-xxxx</li>
            </ul>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Winstar. Tất cả quyền được bảo lưu.</p>
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html> 