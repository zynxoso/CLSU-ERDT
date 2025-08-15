/**
 * Dashboard Loading Handler
 * Manages loading states when dashboard pages are loaded
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a dashboard page
    const isDashboardPage = window.location.pathname.includes('dashboard');
    
    if (isDashboardPage) {
        // Hide login loading when dashboard starts loading
        if (window.hideLoginLoading) {
            window.hideLoginLoading();
        }
        
        // Show dashboard loading
        const userRole = getUserRole();
        if (window.showLoading) {
            window.showLoading(getDashboardMessage(userRole), 'dashboard-init');
        }
        
        // Wait for all critical elements to be ready with faster timeout
        waitForDashboardReady().then(() => {
            // Hide dashboard loading immediately when ready
            if (window.hideLoading) {
                window.hideLoading('dashboard-init');
            }
        });
    }
});

/**
 * Get user role from meta tag or URL
 * @returns {string} - User role
 */
function getUserRole() {
    // Check meta tag first
    const metaRole = document.querySelector('meta[name="user-role"]');
    if (metaRole) {
        return metaRole.getAttribute('content');
    }
    
    // Check URL path
    const path = window.location.pathname;
    if (path.includes('scholar')) {
        return 'scholar';
    } else if (path.includes('admin')) {
        return 'admin';
    }
    
    return 'user';
}

/**
 * Get appropriate dashboard loading message
 * @param {string} userRole - User role
 * @returns {string} - Loading message
 */
function getDashboardMessage(userRole) {
    const messages = {
        'scholar': 'Loading Scholar Dashboard...',
        'admin': 'Loading Admin Dashboard...',
        'super_admin': 'Loading Super Admin Dashboard...',
        'user': 'Loading Dashboard...'
    };
    
    return messages[userRole] || messages['user'];
}

/**
 * Wait for dashboard to be ready
 * @returns {Promise} - Resolves when dashboard is ready
 */
function waitForDashboardReady() {
    return new Promise((resolve) => {
        let readyChecks = 0;
        const maxChecks = 10; // Maximum 1 second (10 * 100ms)
        
        const checkReady = () => {
            readyChecks++;
            
            // Check if critical elements are loaded
            const sidebar = document.querySelector('.sidebar, [class*="sidebar"], nav');
            const mainContent = document.querySelector('.main-content, main, [class*="content"]');
            const livewireComponents = document.querySelectorAll('[wire\\:id]');
            
            // Check if Alpine.js is ready
            const alpineReady = window.Alpine && window.Alpine.version;
            
            // Check if Livewire is ready
            const livewireReady = window.Livewire && window.Livewire.all().length > 0;
            
            const elementsReady = sidebar && mainContent;
            
            if ((elementsReady && alpineReady) || readyChecks >= maxChecks) {
                // Minimal delay for smooth transition
                setTimeout(resolve, 100);
            } else {
                setTimeout(checkReady, 100);
            }
        };
        
        checkReady();
    });
}

// Handle Livewire navigation
if (window.Livewire) {
    // Listen for Livewire navigation events
    document.addEventListener('livewire:navigate', function() {
        // Show loading for navigation
        if (window.showLoading) {
            window.showLoading('Navigating...', 'livewire-nav');
        }
    });
    
    document.addEventListener('livewire:navigated', function() {
        // Hide navigation loading
        if (window.hideLoading) {
            window.hideLoading('livewire-nav');
        }
        
        // Check if we navigated to a dashboard and handle accordingly
        const isDashboardPage = window.location.pathname.includes('dashboard');
        if (isDashboardPage) {
            waitForDashboardReady().then(() => {
                if (window.hideLoading) {
                    window.hideLoading('dashboard-init');
                }
            });
        }
    });
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { getUserRole, getDashboardMessage, waitForDashboardReady };
}