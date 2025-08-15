// Chart Dashboard JavaScript
console.log('Chart dashboard script loaded');

// Khởi tạo biểu đồ ngay khi script load
if (typeof Chart !== 'undefined') {
    console.log('Chart library available, initializing...');
    initRevenueChart();
} else {
    console.error('Chart library not available');
}

$(document).ready(function() {
    // Khởi tạo các tương tác
    initDashboardInteractions();
});

function initRevenueChart() {
    console.log('initRevenueChart function called');
    const ctx = document.getElementById('revenueChart');
    console.log('Canvas element:', ctx);
    if (!ctx) {
        console.error('Revenue chart canvas not found');
        return;
    }

    // Khởi tạo biểu đồ trạng thái đơn hàng
    initOrderStatusChart();
    
    // Cập nhật thống kê
    updateOrderStats();

    // Chỉ sử dụng dữ liệu thực từ server
    let labels = [];
    let revenueData = [];
    let isDailyData = false;

    // Kiểm tra xem có dữ liệu từ server không
    if (typeof chartData !== 'undefined' && chartData.monthlyRevenue && chartData.monthlyRevenue.length > 0) {
        const data = chartData.monthlyRevenue;
        
        // Kiểm tra xem có phải dữ liệu theo ngày không (có field 'date')
        if (data.length > 0 && data[0].date) {
            isDailyData = true;
        }
        
        // Luôn xử lý dữ liệu theo ngày
        if (data.length > 0 && data[0].date) {
            isDailyData = true;
            labels = data.map(item => {
                const date = new Date(item.date);
                return date.getDate() + '/' + (date.getMonth() + 1);
            });
            revenueData = data.map(item => parseFloat(item.revenue));
        } else if (data.length > 0 && data[0].month) {
            // Fallback cho dữ liệu theo tháng (nếu có)
            isDailyData = false;
            const sortedData = data.sort((a, b) => a.month.localeCompare(b.month));
            const last6Months = sortedData.slice(-6);
            
            labels = last6Months.map(item => {
                const date = new Date(item.month + '-01');
                return 'T' + (date.getMonth() + 1);
            });
            
            revenueData = last6Months.map(item => parseFloat(item.revenue));
        }
        
        console.log('Using real data:', { labels, revenueData, isDailyData });
    } else {
        console.log('No real data available - showing empty chart');
        // Hiển thị thông báo không có dữ liệu
        const chartContainer = ctx.parentElement;
        if (chartContainer) {
            const notice = document.createElement('div');
            notice.className = 'alert alert-warning mt-2';
            notice.innerHTML = '<i class="fa fa-exclamation-triangle"></i> Chưa có dữ liệu doanh thu. Biểu đồ sẽ hiển thị khi có đơn hàng.';
            chartContainer.appendChild(notice);
        }
        return; // Không tạo biểu đồ nếu không có dữ liệu
    }

    try {
        console.log('Creating chart with data:', { labels, revenueData, isDailyData });
        
        // Tạo gradient cho background
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.3)');
        gradient.addColorStop(0.5, 'rgba(102, 126, 234, 0.1)');
        gradient.addColorStop(1, 'rgba(102, 126, 234, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: revenueData,
                    borderColor: '#667eea',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.6,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 8,
                    pointHoverRadius: 12,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#667eea',
                    pointHoverBorderWidth: 4,
                    // Thêm shadow effect
                    shadowColor: 'rgba(102, 126, 234, 0.3)',
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowOffsetY: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: 'bold',
                                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 2,
                        cornerRadius: 12,
                        displayColors: false,
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            title: function(context) {
                                if (isDailyData) {
                                    // Lấy ngày đầy đủ từ data
                                    const dataIndex = context[0].dataIndex;
                                    const originalDate = chartData.monthlyRevenue[dataIndex].date;
                                    const date = new Date(originalDate);
                                    const dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                                    const dayName = dayNames[date.getDay()];
                                    return '📅 ' + dayName + ', ' + date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
                                } else {
                                    return '📅 Tháng ' + context[0].label;
                                }
                            },
                            label: function(context) {
                                return '💰 Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VND';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.08)',
                            drawBorder: false,
                            lineWidth: 1
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M VND';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'K VND';
                                }
                                return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                            },
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#666',
                            padding: 8
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#666',
                            padding: 8
                        },
                        border: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart',
                    onProgress: function(animation) {
                        const chart = animation.chart;
                        const ctx = chart.ctx;
                        const dataset = chart.data.datasets[0];
                        const meta = chart.getDatasetMeta(0);
                        
                        // Animate gradient
                        if (meta.data.length > 0) {
                            const gradient = ctx.createLinearGradient(0, 0, 0, chart.height);
                            gradient.addColorStop(0, 'rgba(102, 126, 234, ' + (0.3 * animation.currentStep / animation.numSteps) + ')');
                            gradient.addColorStop(0.5, 'rgba(102, 126, 234, ' + (0.1 * animation.currentStep / animation.numSteps) + ')');
                            gradient.addColorStop(1, 'rgba(102, 126, 234, ' + (0.05 * animation.currentStep / animation.numSteps) + ')');
                            dataset.backgroundColor = gradient;
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 12,
                        radius: 8
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error creating chart:', error);
    }
}

function initDashboardInteractions() {
    // Xử lý hover effects cho các card
    $('.dashboard-card').hover(
        function() {
            $(this).addClass('card-hover');
        },
        function() {
            $(this).removeClass('card-hover');
        }
    );

    // Xử lý click vào các item trong list
    $('.list-group-item').click(function() {
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
    });

    // Xử lý filter form
    $('#filter_type').change(function() {
        const filterType = $(this).val();

        if (filterType === 'custom') {
            $('#filter_value_div').hide();
            $('#start_date_div, #end_date_div').show();
            $('#filter_value').prop('required', false);
            $('#start_date, #end_date').prop('required', true);
        } else if (filterType) {
            $('#filter_value_div').show();
            $('#start_date_div, #end_date_div').hide();
            $('#filter_value').prop('required', true);
            $('#start_date, #end_date').prop('required', false);
        } else {
            $('#filter_value_div, #start_date_div, #end_date_div').hide();
            $('#filter_value, #start_date, #end_date').prop('required', false);
        }
    });

    // Validate form trước khi submit
    $('form').submit(function(e) {
        const filterType = $('#filter_type').val();

        if (filterType === 'custom') {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (!startDate || !endDate) {
                e.preventDefault();
                showAlert('Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!', 'warning');
                return false;
            }

            if (new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                showAlert('Ngày bắt đầu không thể lớn hơn ngày kết thúc!', 'danger');
                return false;
            }
        } else if (filterType && !$('#filter_value').val()) {
            e.preventDefault();
            showAlert('Vui lòng nhập giá trị cho loại lọc đã chọn!', 'warning');
            return false;
        }
    });

    // Auto refresh data mỗi 5 phút
    setInterval(function() {
        if (window.location.pathname.includes('/admin/statistics')) {
            refreshStatisticsData();
        }
    }, 300000); // 5 phút

    // Debug: Log chart data
    console.log('Chart data available:', typeof chartData !== 'undefined');
    if (typeof chartData !== 'undefined') {
        console.log('Monthly revenue data:', chartData.monthlyRevenue);
        console.log('Paid revenue data:', chartData.paidRevenue);
    }
}

function refreshStatisticsData() {
    // Hiển thị loading
    showLoading();

    // Reload trang để lấy dữ liệu mới
    setTimeout(function() {
        window.location.reload();
    }, 1000);
}

function showLoading() {
    const loading = $('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="sr-only">Đang tải...</span></div></div>');
    loading.css({
        position: 'fixed',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        backgroundColor: 'rgba(255, 255, 255, 0.8)',
        zIndex: 9999,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center'
    });
    $('body').append(loading);
}

function showAlert(message, type = 'info') {
    const alert = $(`
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'times-circle' : 'info-circle'}"></i>
            ${message}
        </div>
    `);

    alert.css({
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: 9999,
        minWidth: '300px',
        maxWidth: '500px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
        border: 'none',
        borderRadius: '8px'
    });

    $('body').append(alert);

    // Auto remove sau 5 giây
    setTimeout(function() {
        alert.fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}

// Khởi tạo biểu đồ trạng thái đơn hàng
function initOrderStatusChart() {
    const ctx = document.getElementById('orderStatusChart');
    if (!ctx) {
        console.error('Order status chart canvas not found');
        return;
    }

    // Dữ liệu mẫu - có thể thay thế bằng dữ liệu thực từ server
    const data = {
        labels: ['Hoàn thành', 'Chờ xử lý', 'Đang xử lý', 'Đang giao hàng', 'Đã hủy'],
        datasets: [{
            data: [65, 15, 10, 8, 2],
            backgroundColor: [
                '#28a745',
                '#ffc107', 
                '#17a2b8',
                '#007bff',
                '#dc3545'
            ],
            borderColor: [
                '#28a745',
                '#ffc107',
                '#17a2b8', 
                '#007bff',
                '#dc3545'
            ],
            borderWidth: 2,
            hoverBorderWidth: 4
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 2,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
}

// Cập nhật thống kê đơn hàng
function updateOrderStats() {
    // Dữ liệu mẫu - có thể thay thế bằng dữ liệu thực từ server
    const stats = {
        completed: 65,
        pending: 15,
        processing: 10
    };

    // Cập nhật DOM
    document.getElementById('completedOrders').textContent = stats.completed;
    document.getElementById('pendingOrders').textContent = stats.pending;
    document.getElementById('processingOrders').textContent = stats.processing;

    // Thêm animation
    animateNumbers();
}

// Animation cho số liệu
function animateNumbers() {
    const elements = document.querySelectorAll('.stat-value');
    
    elements.forEach(element => {
        const finalValue = parseInt(element.textContent);
        let currentValue = 0;
        const increment = finalValue / 50; // 50 steps
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            element.textContent = Math.floor(currentValue);
        }, 20);
    });
}

// Export functions để sử dụng ở nơi khác
window.DashboardUtils = {
    showAlert: showAlert,
    showLoading: showLoading,
    refreshStatisticsData: refreshStatisticsData,
    initOrderStatusChart: initOrderStatusChart,
    updateOrderStats: updateOrderStats
};
