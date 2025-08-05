@extends('layouts.client')

@section('title', 'Điểm Danh Tích Điểm')

@section('content')
<div class="attendance-container">
    <!-- Header -->
    <div class="attendance-header">
        <div class="container">
            <div class="header-content">
                <div class="breadcrumb-simple">
                    <a href="{{ route('client.home') }}">Trang chủ</a>
                    <span>/</span>
                    <span>Điểm danh tích điểm</span>
                </div>
                <h1 class="page-title">
                    <i class="fa fa-clock-o"></i>
                    Điểm Danh Tích Điểm
                </h1>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Today's Attendance Card -->
        <div class="today-attendance-card">
            <div class="card-header">
                <h2><i class="fa fa-calendar-check-o"></i> Điểm Danh Hôm Nay</h2>
                <div class="current-time" id="current-time"></div>
            </div>
            
            <div class="attendance-status">
                <div class="status-info">
                    <div class="date-info">
                        <i class="fa fa-calendar"></i>
                        <span>{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="time-info">
                        <i class="fa fa-clock-o"></i>
                        <span id="live-time"></span>
                    </div>
                </div>

                <div class="attendance-actions">
                    @if($todayStatus['can_check_in'])
                        <form action="{{ route('client.attendance.check-in') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-check-in" onclick="return confirm('Xác nhận điểm danh vào?')">
                                <i class="fa fa-sign-in"></i>
                                Điểm Danh Vào
                            </button>
                        </form>
                    @elseif($todayStatus['can_check_out'])
                        <form action="{{ route('client.attendance.check-out') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-check-out" onclick="return confirm('Xác nhận điểm danh ra?')">
                                <i class="fa fa-sign-out"></i>
                                Điểm Danh Ra
                            </button>
                        </form>
                    @else
                        <div class="attendance-completed">
                            <i class="fa fa-check-circle"></i>
                            <span>Đã hoàn thành điểm danh hôm nay</span>
                        </div>
                    @endif
                </div>

                @if($todayStatus['has_checked_in'])
                    <div class="check-in-info">
                        <div class="info-item">
                            <i class="fa fa-sign-in text-success"></i>
                            <span>Điểm danh vào: {{ $todayStatus['check_in_time'] }}</span>
                        </div>
                    </div>
                @endif

                @if($todayStatus['has_checked_out'])
                    <div class="check-out-info">
                        <div class="info-item">
                            <i class="fa fa-sign-out text-info"></i>
                            <span>Điểm danh ra: {{ $todayStatus['check_out_time'] }}</span>
                        </div>

                        <div class="info-item">
                            <i class="fa fa-star text-warning"></i>
                            <span>Điểm tích được: {{ $todayStatus['points_earned'] }} điểm</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="stats-section">
            <div class="section-title">
                <h2>Thống Kê Tháng {{ now()->format('m/Y') }}</h2>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $monthlyStats['total_days'] }}</div>
                        <div class="stat-label">Ngày điểm danh</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $monthlyStats['completed_days'] }}</div>
                        <div class="stat-label">Ngày hoàn thành</div>
                    </div>
                </div>
                

                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $monthlyStats['total_points'] }}</div>
                        <div class="stat-label">Điểm tích được</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-percent"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $monthlyStats['attendance_rate'] }}%</div>
                        <div class="stat-label">Tỷ lệ điểm danh</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="history-section">
            <div class="section-title">
                <h2>Lịch Sử Điểm Danh</h2>
            </div>
            
            @if(count($attendanceHistory) > 0)
                <div class="history-list">
                    @foreach($attendanceHistory as $record)
                        <div class="history-item {{ $record['is_completed'] ? 'completed' : 'incomplete' }}">
                            <div class="history-date">
                                <div class="date-main">{{ $record['date'] }}</div>
                                <div class="date-status">
                                    @if($record['is_completed'])
                                        <i class="fa fa-check-circle text-success"></i>
                                        <span>Hoàn thành</span>
                                    @else
                                        <i class="fa fa-clock-o text-warning"></i>
                                        <span>Chưa hoàn thành</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="history-times">
                                @if($record['check_in_time'])
                                    <div class="time-item">
                                        <i class="fa fa-sign-in text-success"></i>
                                        <span>Vào: {{ $record['check_in_time'] }}</span>
                                    </div>
                                @endif
                                
                                @if($record['check_out_time'])
                                    <div class="time-item">
                                        <i class="fa fa-sign-out text-info"></i>
                                        <span>Ra: {{ $record['check_out_time'] }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="history-stats">
                                @if($record['points_earned'] > 0)
                                    <div class="stat-item">
                                        <i class="fa fa-star text-warning"></i>
                                        <span>{{ $record['points_earned'] }} điểm</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-message">
                    <i class="fa fa-calendar-times-o"></i>
                    <p>Chưa có lịch sử điểm danh nào</p>
                </div>
            @endif
        </div>

        <!-- Points Rules -->
        <div class="rules-section">
            <div class="section-title">
                <h2>Quy Tắc Tích Điểm</h2>
            </div>
            
            <div class="rules-grid">
                <div class="rule-card">
                    <div class="rule-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="rule-content">
                        <h3>Điểm Cơ Bản</h3>
                        <p>1 giờ làm việc = 10 điểm</p>
                    </div>
                </div>
                
                <div class="rule-card">
                    <div class="rule-icon">
                        <i class="fa fa-sun-o"></i>
                    </div>
                    <div class="rule-content">
                        <h3>Điểm Danh Sớm</h3>
                        <p>Điểm danh trước 9h sáng: +5 điểm</p>
                    </div>
                </div>
                
                <div class="rule-card">
                    <div class="rule-icon">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="rule-content">
                        <h3>Làm Việc Dài</h3>
                        <p>Làm việc ≥6 giờ: +5 điểm</p>
                        <p>Làm việc ≥8 giờ: +10 điểm</p>
                    </div>
                </div>
                
                <div class="rule-card">
                    <div class="rule-icon">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="rule-content">
                        <h3>Điểm Danh Đầy Đủ</h3>
                        <p>Phải điểm danh vào và ra để nhận điểm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cập nhật thời gian thực
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('vi-VN');
    const dateString = now.toLocaleDateString('vi-VN');
    
    document.getElementById('live-time').textContent = timeString;
    document.getElementById('current-time').textContent = dateString + ' ' + timeString;
}

// Cập nhật thời gian mỗi giây
setInterval(updateTime, 1000);
updateTime(); // Chạy ngay lập tức

// Auto refresh trang mỗi 5 phút để cập nhật trạng thái
setTimeout(function() {
    location.reload();
}, 5 * 60 * 1000);
</script>

<style>
/* Attendance Page Styles */
.attendance-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding-bottom: 50px;
}

/* Header */
.attendance-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 0;
    margin-bottom: 30px;
}

.header-content {
    text-align: center;
}

.breadcrumb-simple {
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.breadcrumb-simple a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-simple a:hover {
    color: white;
}

.breadcrumb-simple span {
    margin: 0 10px;
    color: rgba(255, 255, 255, 0.6);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.page-title i {
    color: #ffd700;
}

/* Today's Attendance Card */
.today-attendance-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.card-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.current-time {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 500;
}

.attendance-status {
    text-align: center;
}

.status-info {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 25px;
}

.date-info, .time-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.1rem;
    color: #495057;
}

.attendance-actions {
    margin-bottom: 25px;
}

.btn-check-in, .btn-check-out {
    padding: 15px 30px;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: white;
}

.btn-check-in {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-check-in:hover {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-check-out {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-check-out:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.attendance-completed {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 30px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
}

.check-in-info, .check-out-info {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #495057;
}

/* Statistics Section */
.stats-section {
    margin-bottom: 40px;
}

.section-title {
    text-align: center;
    margin-bottom: 30px;
}

.section-title h2 {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.5rem;
    color: white;
}

.stat-card:nth-child(1) .stat-icon { background: #28a745; }
.stat-card:nth-child(2) .stat-icon { background: #17a2b8; }
.stat-card:nth-child(3) .stat-icon { background: #ffc107; }
.stat-card:nth-child(4) .stat-icon { background: #dc3545; }
.stat-card:nth-child(5) .stat-icon { background: #6f42c1; }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

/* History Section */
.history-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.history-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.history-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    border-left: 5px solid #e9ecef;
}

.history-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.history-item.completed {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
}

.history-item.incomplete {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #fffbf0 0%, #fff3cd 100%);
}

.history-date {
    min-width: 120px;
}

.date-main {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

.date-status {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    margin-top: 5px;
}

.history-times {
    flex: 1;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.time-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    color: #495057;
}

.history-stats {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    color: #495057;
    font-weight: 600;
}

/* Rules Section */
.rules-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.rules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.rule-card {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.rule-card:hover {
    transform: translateY(-3px);
}

.rule-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}

.rule-card:nth-child(1) .rule-icon { background: #28a745; }
.rule-card:nth-child(2) .rule-icon { background: #ffc107; }
.rule-card:nth-child(3) .rule-icon { background: #17a2b8; }
.rule-card:nth-child(4) .rule-icon { background: #6f42c1; }

.rule-content h3 {
    margin: 0 0 10px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.rule-content p {
    margin: 5px 0;
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Empty Message */
.empty-message {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-message i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-message p {
    margin: 0;
    font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .history-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .history-times, .history-stats {
        width: 100%;
    }
    
    .rules-grid {
        grid-template-columns: 1fr;
    }
    
    .status-info {
        flex-direction: column;
        gap: 15px;
    }
    
    .check-in-info, .check-out-info {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 576px) {
    .attendance-header {
        padding: 30px 0;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .today-attendance-card {
        padding: 20px;
    }
    
    .card-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .btn-check-in, .btn-check-out {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection 