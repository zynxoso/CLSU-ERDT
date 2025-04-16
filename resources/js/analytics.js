document.addEventListener('DOMContentLoaded', function() {
    // Initialize cards hover effect
    const cards = document.querySelectorAll('.analytics-dashboard__kpi-card, .analytics-dashboard__chart-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 12px 24px rgba(0, 0, 0, 0.2)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Initialize timeframe dropdown
    const timeframeBtn = document.getElementById('timeframe-btn');
    const timeframeDropdown = document.getElementById('timeframe-dropdown');

    if (timeframeBtn && timeframeDropdown) {
        initializeTimeframeDropdown(timeframeBtn, timeframeDropdown);
    }
});
