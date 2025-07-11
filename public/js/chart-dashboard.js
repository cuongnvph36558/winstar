document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('filter_type');
    const valueInput = document.getElementById('filter_value');

    function updateInputType() {
        const type = typeSelect.value;
        if (type === 'day') {
            valueInput.type = 'date';
            if (!valueInput.value || valueInput.value.length !== 10) {
                valueInput.value = new Date().toISOString().slice(0, 10);
            }
        } else if (type === 'week') {
            valueInput.type = 'week';
            if (!valueInput.value || !/^\d{4}-W\d{2}$/.test(valueInput.value)) {
                const today = new Date();
                const year = today.getFullYear();
                const week = getWeekNumber(today);
                valueInput.value = `${year}-W${week.toString().padStart(2, '0')}`;
            }
        } else if (type === 'month') {
            valueInput.type = 'month';
            if (!valueInput.value || valueInput.value.length !== 7) {
                valueInput.value = new Date().toISOString().slice(0, 7);
            }
        }
    }

    function getWeekNumber(d) {
        d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }

    typeSelect.addEventListener('change', updateInputType);
    updateInputType();

    // Render Chart.js if canvas exists
    const chartCanvas = document.getElementById('revenueChart');
    if (chartCanvas && window.Chart && typeof revenueChartData !== 'undefined') {
        const ctx = chartCanvas.getContext('2d');
        new Chart(ctx, revenueChartData);
    }
});
