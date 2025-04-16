// Toggle timeframe dropdown
document.addEventListener('DOMContentLoaded', function() {
    const timeframeBtn = document.getElementById('timeframe-btn');
    const timeframeDropdown = document.getElementById('timeframe-dropdown');
    
    if (timeframeBtn && timeframeDropdown) {
        timeframeBtn.addEventListener('click', function() {
            timeframeDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!timeframeBtn.contains(event.target) && !timeframeDropdown.contains(event.target)) {
                timeframeDropdown.classList.add('hidden');
            }
        });
        
        // Handle timeframe selection
        const timeframeOptions = timeframeDropdown.querySelectorAll('a');
        timeframeOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const value = this.getAttribute('data-value');
                const text = this.textContent;
                
                // Update button text
                timeframeBtn.querySelector('span').textContent = text;
                
                // Hide dropdown
                timeframeDropdown.classList.add('hidden');
                
                // Determine if we're on scholar or admin page
                const isAdmin = window.location.pathname.includes('/admin/');
                
                // Refresh analytics data
                refreshAnalyticsData(value, isAdmin);
            });
        });
    }
});

// Function to refresh analytics data
function refreshAnalyticsData(timeframe, isAdmin = false) {
    const endpoint = isAdmin ? '/api/admin/analytics' : '/api/scholar/analytics';
    
    // Show loading state
    document.querySelectorAll('.analytics-dashboard__kpi-card-value').forEach(el => {
        el.innerHTML = '<div class="animate-pulse h-8 bg-slate-700 rounded w-3/4 mx-auto"></div>';
    });
    
    document.querySelectorAll('.analytics-dashboard__chart-card-body').forEach(el => {
        el.innerHTML = '<div class="animate-pulse h-full bg-slate-700 rounded"></div>';
    });
    
    // Fetch new data
    fetch(`${endpoint}?timeframe=${timeframe}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update KPI cards
            updateKPICards(data, isAdmin);
            
            // Update charts
            if (isAdmin) {
                updateAdminCharts(data);
            } else {
                updateScholarCharts(data);
            }
        })
        .catch(error => {
            console.error('Error fetching analytics data:', error);
            
            // Show error message
            document.querySelectorAll('.analytics-dashboard__kpi-card-value').forEach(el => {
                el.textContent = 'Error loading data';
            });
            
            document.querySelectorAll('.analytics-dashboard__chart-card-body').forEach(el => {
                el.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-red-500">Error loading chart data</p></div>';
            });
        });
}

// Update KPI cards based on data
function updateKPICards(data, isAdmin) {
    if (isAdmin) {
        // Admin KPIs
        updateKPIValue('Total Scholars', data.totalScholars, data.scholarsGrowth);
        updateKPIValue('Total Disbursed', formatCurrency(data.totalDisbursed), data.disbursedGrowth);
        updateKPIValue('Pending Requests', data.pendingRequests, data.pendingGrowth, data.pendingGrowth < 0);
        updateKPIValue('Completion Rate', `${data.completionRate}%`, data.completionGrowth);
    } else {
        // Scholar KPIs
        updateKPIValue('Total Fund Requests', data.totalRequests, data.requestsGrowth);
        updateKPIValue('Approved Funds', formatCurrency(data.approvedAmount), data.approvedGrowth);
        updateKPIValue('Document Submissions', data.documentCount, data.documentGrowth);
        updateKPIValue('Manuscript Progress', `${data.manuscriptProgress}%`, data.manuscriptGrowth);
    }
}

// Helper function to update a specific KPI card
function updateKPIValue(title, value, growth, isPositiveDown = false) {
    const cards = document.querySelectorAll('.analytics-dashboard__kpi-card');
    
    for (const card of cards) {
        const cardTitle = card.querySelector('.analytics-dashboard__kpi-card-title');
        
        if (cardTitle && cardTitle.textContent === title) {
            const valueEl = card.querySelector('.analytics-dashboard__kpi-card-value');
            const trendEl = card.querySelector('.analytics-dashboard__kpi-card-trend--up, .analytics-dashboard__kpi-card-trend--down');
            
            if (valueEl) valueEl.textContent = value;
            
            if (trendEl) {
                // Determine if trend is positive or negative
                const isPositive = growth > 0;
                const shouldBeUp = isPositiveDown ? !isPositive : isPositive;
                
                // Update trend icon and class
                trendEl.innerHTML = `<i class="fas fa-arrow-${shouldBeUp ? 'up' : 'down'} mr-1"></i> ${Math.abs(growth)}%`;
                trendEl.className = `analytics-dashboard__kpi-card-trend--${shouldBeUp ? 'up' : 'down'} text-sm`;
            }
            
            break;
        }
    }
}

// Format currency
function formatCurrency(value) {
    return '₱' + new Intl.NumberFormat().format(value);
}

// Update scholar charts
function updateScholarCharts(data) {
    if (data.fundingData) {
        const ctx = document.getElementById('fundingChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.fundingData.labels,
                    datasets: [{
                        label: 'Requested Amount',
                        data: data.fundingData.requested,
                        backgroundColor: 'rgba(99, 102, 241, 0.5)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1
                    }, {
                        label: 'Disbursed Amount',
                        data: data.fundingData.disbursed,
                        backgroundColor: 'rgba(34, 197, 94, 0.5)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    if (data.documentData) {
        const ctx = document.getElementById('documentChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.documentData.labels,
                    datasets: [{
                        data: data.documentData.data,
                        backgroundColor: data.documentData.colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    if (data.activityData) {
        const ctx = document.getElementById('activityChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'line',
                data: data.activityData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    if (data.manuscriptData) {
        const ctx = document.getElementById('manuscriptChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.manuscriptData.labels,
                    datasets: [{
                        label: 'Progress (%)',
                        data: data.manuscriptData.data,
                        borderColor: data.manuscriptData.borderColor,
                        backgroundColor: data.manuscriptData.backgroundColor,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}

// Update admin charts
function updateAdminCharts(data) {
    if (data.disbursementData) {
        const ctx = document.getElementById('disbursementChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.disbursementData.labels,
                    datasets: [{
                        data: data.disbursementData.data,
                        backgroundColor: data.disbursementData.colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    if (data.scholarData) {
        const ctx = document.getElementById('scholarChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.scholarData.labels,
                    datasets: [{
                        data: data.scholarData.data,
                        backgroundColor: data.scholarData.colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    if (data.requestsData) {
        const ctx = document.getElementById('requestsChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'line',
                data: data.requestsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
    
    if (data.completionData) {
        const ctx = document.getElementById('completionChart');
        if (ctx) {
            if (ctx.chart) ctx.chart.destroy();
            ctx.chart = new Chart(ctx, {
                type: 'line',
                data: data.completionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}