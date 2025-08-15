/**
 * Session Timeout Management System
 * Shows a SweetAlert confirmation dialog when session expires due to inactivity
 */

class SessionTimeoutManager {
    constructor(options = {}) {
        this.sessionLifetime = options.sessionLifetime || 5; // minutes
        this.checkInterval = options.checkInterval || 60000; // check every minute
        this.lastActivity = Date.now();
        this.timeoutTimer = null;
        
        this.init();
    }

    init() {
        // Track user activity
        this.trackActivity();
        
        // Start monitoring session
        this.startMonitoring();
        
        // Get initial last activity from server if available
        this.syncWithServer();
    }

    trackActivity() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, { passive: true });
        });
    }

    updateActivity() {
        this.lastActivity = Date.now();
        
        // Clear existing timer
        if (this.timeoutTimer) {
            clearTimeout(this.timeoutTimer);
        }
        
        // Restart monitoring
        this.startMonitoring();
    }

    startMonitoring() {
        const sessionMs = this.sessionLifetime * 60 * 1000;
        
        // Set timeout timer
        this.timeoutTimer = setTimeout(() => {
            this.handleTimeout();
        }, sessionMs);
    }





    handleTimeout() {
        // Show session expired confirmation dialog
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Session Expired',
                text: 'Your session has expired due to inactivity. Click OK to logout.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'clsu-erdt-swal-clean'
                }
            }).then(() => {
                this.logout();
            });
        } else {
            this.logout();
        }
    }

    logout() {
        // Determine the appropriate logout route
        const isScholarContext = window.location.pathname.includes('/scholar') || 
                                window.location.hostname.includes('scholar') ||
                                document.querySelector('meta[name="user-type"]')?.getAttribute('content') === 'scholar';
        
        const logoutUrl = isScholarContext ? '/scholar/logout' : '/logout';
        
        // Create a form and submit it to logout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = logoutUrl;
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }

    syncWithServer() {
        // Get server-side last activity if available
        fetch('/session-status', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.last_activity) {
                this.lastActivity = data.last_activity * 1000; // Convert to milliseconds
            }
        })
        .catch(error => {
            console.warn('Could not sync with server session status:', error);
        });
    }

    destroy() {
        if (this.timeoutTimer) {
            clearTimeout(this.timeoutTimer);
        }
    }
}

// Initialize session timeout manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if user is authenticated
    const isAuthenticated = document.querySelector('meta[name="authenticated"]')?.getAttribute('content') === 'true' ||
                           document.body.classList.contains('authenticated') ||
                           window.location.pathname !== '/login' && window.location.pathname !== '/scholar/login';
    
    if (isAuthenticated && typeof Swal !== 'undefined') {
        // Get session lifetime from meta tag or use default
        const sessionLifetime = parseInt(document.querySelector('meta[name="session-lifetime"]')?.getAttribute('content')) || 120;
        
        window.sessionTimeoutManager = new SessionTimeoutManager({
            sessionLifetime: sessionLifetime,
            checkInterval: 60000 // Check every minute
        });
        
        console.log('Session timeout manager initialized');
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SessionTimeoutManager;
}