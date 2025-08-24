<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảm ơn bạn đã điểm danh</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #666;
            line-height: 1.8;
        }
        .attendance-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #333;
        }
        .points-info {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .points-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .points-value {
            font-size: 24px;
            font-weight: 700;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }
        .social-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                @if($type === 'check_in')
                    🎉 Cảm ơn bạn đã điểm danh vào!
                @else
                    🎯 Cảm ơn bạn đã điểm danh ra!
                @endif
            </h1>
        </div>

        <div class="content">
            <div class="greeting">
                Xin chào <strong>{{ $user->name }}</strong>! 👋
            </div>

            <div class="message">
                @if($type === 'check_in')
                    Cảm ơn bạn đã điểm danh vào hôm nay! Chúng tôi rất vui khi bạn đã bắt đầu một ngày làm việc mới.
                    <br><br>
                    Hãy làm việc hiệu quả và đừng quên điểm danh ra khi kết thúc ngày để nhận điểm thưởng nhé!
                @else
                    Cảm ơn bạn đã hoàn thành điểm danh hôm nay! Chúng tôi đánh giá cao sự chăm chỉ và đúng giờ của bạn.
                    <br><br>
                    Bạn có thể tích điểm ngay bây giờ để nhận thưởng cho ngày làm việc của mình!
                @endif
            </div>

            <div class="attendance-info">
                <div class="info-row">
                    <span class="info-label">Ngày:</span>
                    <span class="info-value">{{ $attendance->date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thời gian vào:</span>
                    <span class="info-value">{{ $attendance->check_in_time ? $attendance->check_in_time : 'Chưa điểm danh' }}</span>
                </div>
                @if($attendance->check_out_time)
                <div class="info-row">
                    <span class="info-label">Thời gian ra:</span>
                    <span class="info-value">{{ $attendance->check_out_time }}</span>
                </div>
                @endif
                @if($attendance->points_earned > 0)
                <div class="info-row">
                    <span class="info-label">Điểm có thể tích:</span>
                    <span class="info-value">{{ $attendance->points_earned }} điểm</span>
                </div>
                @endif
            </div>

            @if($type === 'check_out' && $attendance->points_earned > 0)
            <div class="points-info">
                <div class="points-title">🎁 Điểm Thưởng Của Bạn</div>
                <div class="points-value">{{ $attendance->points_earned }} điểm</div>
                <div style="margin-top: 10px; font-size: 14px;">
                    Hãy truy cập trang điểm tích lũy để tích điểm ngay!
                </div>
            </div>
            @endif

            <div class="message">
                <strong>Lưu ý:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Điểm danh đúng giờ giúp bạn nhận thêm điểm bonus</li>
                    <li>Làm việc từ 6-8 giờ sẽ được thưởng điểm đặc biệt</li>
                    <li>Điểm có thể đổi lấy mã giảm giá và quà tặng</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Trân trọng,<br><strong>{{ config('app.name') }}</strong></p>
            <div class="social-links">
                <a href="{{ route('client.home') }}">Trang chủ</a> |
                <a href="{{ route('client.points.index') }}">Điểm tích lũy</a>
            </div>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                Email này được gửi tự động. Vui lòng không trả lời email này.
            </p>
        </div>
    </div>
</body>
</html> 
