document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('visitorChartCanvas').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: window.chartLabels || [],
            datasets: [{
                label: 'Visitors per Month',
                data: window.chartData || [],
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: 'rgba(78, 115, 223, 0.9)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
