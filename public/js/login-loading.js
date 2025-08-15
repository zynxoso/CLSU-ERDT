/**
 * Login Loading Handler
 * Manages loading states during the login process
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Find login forms on the page
    const loginForms = document.querySelectorAll('form[action*="login"]');
    
    loginForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Get user role from meta tag or form data
            const userRole = getUserRoleFromForm(form);
            
            // Show login loading immediately
            if (window.showLoginLoading) {
                window.showLoginLoading(userRole);
            }
            
            // Set a timeout to hide loading if something goes wrong
            setTimeout(() => {
                if (window.hideLoginLoading) {
                    window.hideLoginLoading();
                }
            }, 10000); // 10 seconds timeout
        });
    });
    
    // Handle page visibility change to hide loading if user switches tabs
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            // If page becomes visible again and we're still on login page,
            // hide the loading (probably an error occurred)
            if (window.location.pathname.includes('login') && window.hideLoginLoading) {
                setTimeout(() => {
                    window.hideLoginLoading();
                }, 1000);
            }
        }
    });
});

/**
 * Determine user role from form or page context
 * @param {HTMLFormElement} form - The login form
 * @returns {string} - User role
 */
function getUserRoleFromForm(form) {
    // Check if this is scholar login page
    if (window.location.pathname.includes('scholar-login')) {
        return 'scholar';
    }
    
    // Check if this is admin login page
    if (window.location.pathname.includes('login') && !window.location.pathname.includes('scholar')) {
        return 'admin';
    }
    
    // Check for hidden input or data attribute
    const roleInput = form.querySelector('input[name="role"]');
    if (roleInput) {
        return roleInput.value;
    }
    
    // Check form action URL
    const action = form.getAttribute('action');
    if (action && action.includes('scholar')) {
        return 'scholar';
    }
    
    // Default to admin for regular login
    return 'admin';
}

// Handle successful login redirect
window.addEventListener('beforeunload', function() {
    // If we're navigating away from login page, keep the loading
    // It will be hidden by the dashboard page
    if (window.location.pathname.includes('login')) {
        // Don't hide loading here, let the dashboard handle it
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { getUserRoleFromForm };
}