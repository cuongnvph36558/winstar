@extends('layouts.client')

@section('title', 'Yêu cầu đổi hoàn hàng của tôi')

@section('content')
<div class="points-container">
    <!-- Header -->
    <div class="points-header">
        <div class="container">
            <div class="header-content">
                <div class="breadcrumb-simple">
                    <a href="{{ route('client.home') }}">Trang chủ</a>
                    <span>/</span>
                    <span>Yêu cầu đổi hoàn hàng</span>
                </div>
                <h1 class="page-title">
                    <i class="fa fa-exchange"></i>
                    Yêu cầu đổi hoàn hàng của tôi
                </h1>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Section -->
        <div class="statistics-section">
            <div class="statistics-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $returns->where('return_status', 'requested')->count() }}</div>
                        <div class="stat-label">Chờ xử lý</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $returns->where('return_status', 'approved')->count() }}</div>
                        <div class="stat-label">Đã chấp thuận</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $returns->where('return_status', 'rejected')->count() }}</div>
                        <div class="stat-label">Đã từ chối</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $returns->where('return_status', 'completed')->count() }}</div>
                        <div class="stat-label">Hoàn thành</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Exchange Section -->
        <div class="return-exchange-section">
            <div class="section-title">
                <h2>Danh sách yêu cầu</h2>
                <p>Quản lý các yêu cầu đổi hoàn hàng của bạn</p>
            </div>
            
            <div class="return-exchange-content">
                @forelse($returns as $return)
                    <div class="return-item">
                        <div class="return-header">
                            <div class="return-info">
                                <h5 class="return-title">
                                    <i class="fa fa-shopping-cart"></i>
                                    Đơn hàng #{{ $return->id }} - {{ $return->code_order }}
                                </h5>
                                <div class="return-meta">
                                    <span class="return-date">
                                        <i class="fa fa-calendar"></i>
                                        {{ $return->return_requested_at ? $return->return_requested_at->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                    <span class="return-amount">
                                        <i class="fa fa-money"></i>
                                        {{ number_format($return->total_amount) }}đ
                                    </span>
                                </div>
                            </div>
                            <div class="return-status">
                                @switch($return->return_status)
                                    @case('requested')
                                        <span class="status-badge status-pending">
                                            <i class="fa fa-clock-o"></i>Chờ xử lý
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="status-badge status-approved">
                                            <i class="fa fa-check-circle"></i>Đã chấp thuận
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="status-badge status-rejected">
                                            <i class="fa fa-times-circle"></i>Đã từ chối
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="status-badge status-completed">
                                            <i class="fa fa-flag-checkered"></i>Hoàn thành
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        <div class="return-details">
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Lý do</div>
                                        <div class="detail-value">{{ $return->return_reason }}</div>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fa fa-cog"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Phương thức</div>
                                        <div class="detail-value">
                                            @switch($return->return_method)
                                                @case('points')
                                                    <span class="method-badge method-points">
                                                        <i class="fa fa-star"></i>Đổi điểm
                                                    </span>
                                                    @break
                                                @case('exchange')
                                                    <span class="method-badge method-exchange">
                                                        <i class="fa fa-refresh"></i>Đổi hàng
                                                    </span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                                
                                @if($return->return_description)
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fa fa-comment"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Mô tả</div>
                                            <div class="detail-value">{{ Str::limit($return->return_description, 100) }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($return->return_amount)
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fa fa-{{ $return->return_method === 'points' ? 'star' : 'money' }}"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">
                                                {{ $return->return_method === 'points' ? 'Số điểm hoàn' : 'Số tiền hoàn' }}
                                            </div>
                                            <div class="detail-value amount-highlight">
                                                {{ number_format($return->return_amount) }}
                                                {{ $return->return_method === 'points' ? 'điểm' : 'đ' }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($return->admin_return_note)
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fa fa-sticky-note"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Ghi chú admin</div>
                                            <div class="detail-value">{{ $return->admin_return_note }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($return->return_processed_at)
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Ngày xử lý</div>
                                            <div class="detail-value">{{ $return->return_processed_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="return-actions">
                            <a href="{{ route('client.order.show', $return->id) }}" class="btn-view-order">
                                <i class="fa fa-eye"></i>
                                Xem chi tiết đơn hàng
                            </a>
                            @if($return->return_status === 'requested')
                                <form action="{{ route('client.return.cancel', $return->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-cancel-request" onclick="return confirm('Bạn có chắc muốn hủy yêu cầu này?')">
                                        <i class="fa fa-times"></i>
                                        Hủy yêu cầu
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fa fa-exchange"></i>
                        </div>
                        <div class="empty-content">
                            <h3>Chưa có yêu cầu đổi hoàn hàng nào</h3>
                            <p>Khi bạn gửi yêu cầu đổi hoàn hàng, chúng sẽ xuất hiện ở đây.</p>
                            <a href="{{ route('client.order.list') }}" class="btn-back">
                                <i class="fa fa-shopping-bag"></i>
                                Xem đơn hàng của tôi
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($returns->hasPages())
                <div class="pagination-wrapper">
                    {{ $returns->links() }}
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="help-section">
            <div class="section-title">
                <h2>Hướng Dẫn Sử Dụng</h2>
                <p>Thông tin hữu ích về quy trình đổi hoàn hàng</p>
            </div>
            
            <div class="help-grid">
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <div class="help-content">
                        <h4>Yêu cầu đổi hoàn hàng</h4>
                        <p>Khi bạn muốn đổi hoàn hàng, hãy vào trang chi tiết đơn hàng và chọn "Yêu cầu đổi hoàn hàng". Điền đầy đủ thông tin và lý do để được xử lý nhanh chóng.</p>
                    </div>
                </div>
                
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="help-content">
                        <h4>Thời gian xử lý</h4>
                        <ul>
                            <li>Yêu cầu sẽ được xem xét trong 1-3 ngày làm việc</li>
                            <li>Thời gian hoàn tiền: 3-7 ngày sau khi chấp thuận</li>
                            <li>Đổi hàng: 5-10 ngày tùy theo khoảng cách</li>
                            <li>Đổi điểm: Ngay lập tức sau khi chấp thuận</li>
                        </ul>
                    </div>
                </div>
                
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="help-content">
                        <h4>Điều kiện đổi hoàn</h4>
                        <ul>
                            <li>Sản phẩm phải còn nguyên vẹn, chưa sử dụng</li>
                            <li>Yêu cầu trong vòng 30 ngày kể từ ngày nhận hàng</li>
                            <li>Phải có lý do hợp lệ và rõ ràng</li>
                            <li>Không áp dụng cho hàng đã giảm giá đặc biệt</li>
                        </ul>
                    </div>
                </div>
                
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="help-content">
                        <h4>Hỗ trợ khách hàng</h4>
                        <p>Nếu bạn cần hỗ trợ thêm về quy trình đổi hoàn hàng, vui lòng liên hệ với chúng tôi qua:</p>
                        <ul>
                            <li>Hotline: 0567899999</li>
                            <li>Email: winstar@gmail.com</li>
                            <li>Chat online: Có sẵn 24/7</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Return Exchange Page Styles */
.statistics-section {
    margin-bottom: 40px;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid #e9ecef;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.return-exchange-section {
    margin-bottom: 60px;
}

.return-exchange-content {
    margin-top: 30px;
}

.return-item {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid #e9ecef;
    margin-bottom: 25px;
}

.return-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.return-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.return-title {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.return-meta {
    display: flex;
    gap: 20px;
    font-size: 14px;
    opacity: 0.9;
}

.return-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    background: rgba(255,255,255,0.2);
}

.status-pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.status-approved {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.status-rejected {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.status-completed {
    background: rgba(23, 162, 184, 0.2);
    color: #17a2b8;
}

.return-details {
    padding: 25px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 16px;
    flex-shrink: 0;
}

.detail-content {
    flex: 1;
}

.detail-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
    font-weight: 500;
}

.detail-value {
    font-size: 14px;
    color: #333;
    font-weight: 500;
}

.method-badge {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.method-points {
    background: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.method-exchange {
    background: #fff3e0;
    color: #f57c00;
    border: 1px solid #ffcc02;
}

.amount-highlight {
    font-weight: 700;
    color: #28a745;
    font-size: 16px;
}

.return-actions {
    padding: 20px 25px;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-view-order {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-view-order:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-cancel-request {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel-request:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 80px;
    color: #ddd;
    margin-bottom: 30px;
}

.empty-content h3 {
    color: #666;
    margin-bottom: 15px;
    font-size: 24px;
}

.empty-content p {
    color: #999;
    margin-bottom: 30px;
    font-size: 16px;
}

.btn-back {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.pagination-wrapper {
    text-align: center;
    margin-top: 40px;
}

.help-section {
    margin-bottom: 60px;
}

.help-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.help-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.help-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-bottom: 20px;
}

.help-content h4 {
    color: #333;
    margin-bottom: 15px;
    font-size: 18px;
    font-weight: 600;
}

.help-content p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.help-content ul {
    color: #666;
    line-height: 1.6;
    padding-left: 20px;
}

.help-content li {
    margin-bottom: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .statistics-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px;
        gap: 15px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .stat-number {
        font-size: 24px;
    }
    
    .return-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .return-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .return-actions {
        flex-direction: column;
    }
    
    .return-actions .btn-view-order,
    .return-actions .btn-cancel-request {
        width: 100%;
        justify-content: center;
    }
    
    .help-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 15px;
        gap: 12px;
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    
    .stat-number {
        font-size: 20px;
    }
    
    .help-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>
@endsection 
