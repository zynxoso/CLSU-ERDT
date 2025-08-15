/**
 * Reports JavaScript Module
 * Handles all report-related functionality including printing, filtering, and interactions
 */

class ReportsManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupPrintHandlers();
        this.setupTableInteractions();
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Print button handlers
        document.addEventListener('click', (e) => {
            if (e.target.matches('.reports-print-btn, .reports-print-btn *')) {
                e.preventDefault();
                this.handlePrint();
            }
        });

        // Back button handlers with hover effects
        document.addEventListener('mouseover', (e) => {
            if (e.target.matches('.reports-back-link')) {
                e.target.style.color = 'rgb(21 128 61)';
            }
        });

        document.addEventListener('mouseout', (e) => {
            if (e.target.matches('.reports-back-link')) {
                e.target.style.color = 'rgb(34 197 94)';
            }
        });

        // Print button hover effects
        document.addEventListener('mouseover', (e) => {
            if (e.target.matches('.reports-print-btn')) {
                e.target.style.backgroundColor = 'rgb(21 128 61)';
            }
        });

        document.addEventListener('mouseout', (e) => {
            if (e.target.matches('.reports-print-btn')) {
                e.target.style.backgroundColor = 'rgb(34 197 94)';
            }
        });
    }

    /**
     * Setup print functionality
     */
    setupPrintHandlers() {
        // Add print styles if not already present
        if (!document.querySelector('#reports-print-styles')) {
            const printStyles = document.createElement('style');
            printStyles.id = 'reports-print-styles';
            printStyles.textContent = `
                @media print {
                    .print\\:hidden { display: none !important; }
                    .print\\:block { display: block !important; }
                    .print\\:shadow-none { box-shadow: none !important; }
                    .print\\:border-0 { border: none !important; }
                    .print\\:bg-white { background-color: white !important; }
                    .print\\:mt-8 { margin-top: 2rem !important; }
                    
                    body { 
                        font-size: 12px;
                        line-height: 1.4;
                        color: #000;
                    }
                    
                    .reports-card {
                        box-shadow: none !important;
                        border: none !important;
                    }
                    
                    .reports-table {
                        font-size: 11px;
                    }
                    
                    .reports-table th,
                    .reports-table td {
                        padding: 6px 8px;
                        border: 1px solid #ccc;
                    }
                    
                    .reports-status-badge {
                        border: 1px solid #ccc;
                        background-color: #f5f5f5 !important;
                        color: #000 !important;
                    }
                }
            `;
            document.head.appendChild(printStyles);
        }
    }

    /**
     * Handle print functionality
     */
    handlePrint() {
        // Prepare page for printing
        this.preparePrintView();
        
        // Trigger print
        window.print();
        
        // Restore view after print dialog
        setTimeout(() => {
            this.restoreNormalView();
        }, 100);
    }

    /**
     * Prepare the page for printing
     */
    preparePrintView() {
        // Hide navigation and other non-essential elements
        const elementsToHide = document.querySelectorAll('.print\\:hidden, [class*="print:hidden"]');
        elementsToHide.forEach(el => {
            el.style.display = 'none';
        });

        // Show print-only elements
        const elementsToShow = document.querySelectorAll('.print\\:block, [class*="print:block"]');
        elementsToShow.forEach(el => {
            el.style.display = 'block';
        });

        // Optimize table layout for printing
        const tables = document.querySelectorAll('.reports-table');
        tables.forEach(table => {
            table.style.fontSize = '11px';
            table.style.pageBreakInside = 'auto';
        });
    }

    /**
     * Restore normal view after printing
     */
    restoreNormalView() {
        // This will be called after print dialog closes
        // Most styles will be restored automatically by CSS
        // but we can add any specific restoration logic here if needed
    }

    /**
     * Setup table interactions
     */
    setupTableInteractions() {
        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('.reports-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = 'rgb(249 250 251)';
            });
            
            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = '';
            });
        });
    }

    /**
     * Format currency values
     */
    static formatCurrency(amount) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(amount);
    }

    /**
     * Format dates consistently
     */
    static formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    /**
     * Generate status badge HTML
     */
    static createStatusBadge(status) {
        const statusClass = status.toLowerCase().replace(/[^a-z0-9]/g, '-');
        return `<span class="reports-status-badge ${statusClass}">${status}</span>`;
    }

    /**
     * Export table data to CSV
     */
    exportToCSV(tableSelector, filename = 'report.csv') {
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error('Table not found:', tableSelector);
            return;
        }

        const rows = Array.from(table.querySelectorAll('tr'));
        const csvContent = rows.map(row => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            return cells.map(cell => {
                // Clean cell content and escape quotes
                const content = cell.textContent.trim().replace(/"/g, '""');
                return `"${content}"`;
            }).join(',');
        }).join('\n');

        // Create and trigger download
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    /**
     * Show loading state
     */
    showLoading(element) {
        if (element) {
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            element.disabled = true;
        }
    }

    /**
     * Hide loading state
     */
    hideLoading(element, originalText) {
        if (element) {
            element.innerHTML = originalText;
            element.disabled = false;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.reportsManager = new ReportsManager();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ReportsManager;
}