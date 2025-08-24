// Enhanced Chart with Real Data
// Enhanced chart script loaded

// Khởi tạo biểu đồ khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    // Document ready, initializing enhanced chart...
    initEnhancedChart();
});

function initEnhancedChart() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) {
        // Revenue chart canvas not found
        return;
    }

    // Creating enhanced chart...

    // Lấy dữ liệu từ server nếu có
    let chartData = null;
    if (typeof window.chartData !== 'undefined') {
        chartData = window.chartData;
        // Found chart data from server
    }

    // Tạo dữ liệu cho biểu đồ
    let labels = [];
    let revenueData = [];
    let isDailyData = false;

    if (chartData && chartData.monthlyRevenue && chartData.monthlyRevenue.length > 0) {
        const data = chartData.monthlyRevenue;
        // Processing server data
        
        if (data.length > 0 && data[0].date) {
            isDailyData = true;
            labels = data.map(item => {
                const date = new Date(item.date);
                return date.getDate() + '/' + (date.getMonth() + 1);
            });
            revenueData = data.map(item => parseFloat(item.revenue));
        }
    } else {
        // Fallback data nếu không có dữ liệu từ server
        // Using fallback data
        labels = ['3/8', '4/8', '16/8'];
        revenueData = [271564, 332240000, 19072999];
        isDailyData = true;
    }

    // Final chart data

    // Tạo gradient
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(102, 126, 234, 0.3)');
    gradient.addColorStop(0.5, 'rgba(102, 126, 234, 0.1)');
    gradient.addColorStop(1, 'rgba(102, 126, 234, 0.05)');

    try {
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
                    pointHoverBorderWidth: 4
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
                                    return '📅 Ngày ' + context[0].label;
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
                    easing: 'easeInOutQuart'
                },
                elements: {
                    point: {
                        hoverRadius: 12,
                        radius: 8
                    }
                }
            }
        });
        // Enhanced chart created successfully
        
        // Khởi tạo biểu đồ trạng thái đơn hàng
        initOrderStatusChart();
        
        // Cập nhật thống kê
        updateOrderStats();
        
    } catch (error) {
        // Error creating enhanced chart
    }
}

// Khởi tạo biểu đồ trạng thái đơn hàng
function initOrderStatusChart() {
    const ctx = document.getElementById('orderStatusChart');
    if (!ctx) {
        // Order status chart canvas not found
        return;
    }

    // Dữ liệu mẫu - có thể thay thế bằng dữ liệu thực từ server
    const data = {
        labels: ['Hoàn thành', 'Chờ xử lý', 'Đang xử lý', 'Đang giao hàng', 'Đã hủy'],
        datasets: [{
            data: [24, 13, 2, 3, 3],
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
    const stats = {
        completed: 24,
        pending: 13,
        processing: 2
    };

    // Cập nhật DOM
    const completedEl = document.getElementById('completedOrders');
    const pendingEl = document.getElementById('pendingOrders');
    const processingEl = document.getElementById('processingOrders');
    
    if (completedEl) completedEl.textContent = stats.completed;
    if (pendingEl) pendingEl.textContent = stats.pending;
    if (processingEl) processingEl.textContent = stats.processing;

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