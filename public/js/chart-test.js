// Simple Chart Test
// Chart test script loaded

// Khởi tạo biểu đồ khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    // DOM loaded, creating test chart...
    createTestChart();
});

function createTestChart() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) {
        // Canvas not found
        return;
    }

    // Creating test chart...

    // Test data
    const data = {
        labels: ['3/8', '4/8', '16/8'],
        datasets: [{
            label: 'Doanh thu (VND)',
            data: [271564, 332240000, 19072999],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    try {
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VND';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                            }
                        }
                    }
                }
            }
        });
        // Test chart created successfully
    } catch (error) {
        // Error creating test chart
    }
} 