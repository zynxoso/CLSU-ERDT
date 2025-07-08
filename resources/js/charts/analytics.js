import Chart from 'chart.js/auto';

// Chart.js Global Configuration
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#94A3B8'; // slate-400
Chart.defaults.scale.grid.color = '#E2E8F0';
Chart.defaults.plugins.tooltip.backgroundColor = '#1E293B'; // slate-800

// Funding Distribution Chart
export const initFundingChart = (data) => {
    const ctx = document.getElementById('fundingChart');
    if (!ctx) return null;

    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Requested Amount',
                data: data.requested,
                backgroundColor: 'rgba(99, 102, 241, 0.5)', // indigo-500
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1
            }, {
                label: 'Disbursed Amount',
                data: data.disbursed,
                backgroundColor: 'rgba(34, 197, 94, 0.5)', // green-500
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    padding: 12,
                    boxPadding: 8
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => `₱${value.toLocaleString()}`
                    }
                }
            }
        }
    });
};
