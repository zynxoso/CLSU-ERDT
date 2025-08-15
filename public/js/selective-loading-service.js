/**
 * Selective Loading Service
 * Only shows loading for login, navigation, and static pages
 * Excludes CRUD operations
 */
class SelectiveLoadingService {
    constructor() {
        this.loadingQueue = new Set();
        this.loadingStartTimes = new Map();
        this.minDisplayTime = 500; // Minimum time to show loading (ms)
        this.isPageLoading = false;
        
        this.init();
    }
    
    init() {
        // Handle page navigation loading
        this.setupNavigationLoading();
        
        // Handle login form loading
        this.setupLoginLoading();
        
        // Handle static page navigation
        this.setupStaticPageLoading();
        
        // Make service globally available
        window.selectiveLoading = this;
    }
    
    setupNavigationLoading() {
        // Show loading on page navigation (not AJAX)
        window.addEventListener('beforeunload', () => {
            if (!this.isPageLoading) {
                this.show('Navigating...');
                this.isPageLoading = true;
            }
        });
        
        // Hide loading when page loads
        window.addEventListener('load', () => {
            setTimeout(() => {
                this.hideAll();
                this.isPageLoading = false;
            }, 300);
        });
    }
    
    setupLoginLoading() {
        // Handle login forms
        document.addEventListener('submit', (event) => {
            const form = event.target;
            
            // Check if it's a login form
            if (this.isLoginForm(form)) {
                this.show('Authenticating...');
            }
        });
    }
    
    setupStaticPageLoading() {
        // Handle navigation to static pages (home, about, history)
        document.addEventListener('click', (event) => {
            const link = event.target.closest('a');
            
            if (link && this.isStaticPageLink(link)) {
                this.show('Loading page...');
            }
        });
    }
    
    isLoginForm(form) {
        // Check if form is a login form
        const action = form.action || '';
        const hasPasswordField = form.querySelector('input[type="password"]');
        const hasLoginClass = form.classList.contains('login-form');
        
        return hasPasswordField && (action.includes('login') || hasLoginClass);
    }
    
    isStaticPageLink(link) {
        const href = link.href || '';
        const staticPages = ['/', '/about', '/history', '/home'];
        
        // Check if link goes to static pages
        return staticPages.some(page => {
            return href.endsWith(page) || href.includes(page);
        });
    }
    
    show(message = 'Loading...', id = 'default') {
        this.loadingQueue.add(id);
        this.loadingStartTimes.set(id, Date.now());
        
        // Dispatch custom event to show loading
        window.dispatchEvent(new CustomEvent('show-loading', {
            detail: { message, id }
        }));
    }
    
    hide(id = 'default') {
        if (!this.loadingQueue.has(id)) return;
        
        const startTime = this.loadingStartTimes.get(id);
        const elapsed = Date.now() - startTime;
        const remainingTime = Math.max(0, this.minDisplayTime - elapsed);
        
        setTimeout(() => {
            this.loadingQueue.delete(id);
            this.loadingStartTimes.delete(id);
            
            // Only hide if no other loading operations are active
            if (this.loadingQueue.size === 0) {
                window.dispatchEvent(new CustomEvent('hide-loading', {
                    detail: { id }
                }));
            }
        }, remainingTime);
    }
    
    hideAll() {
        this.loadingQueue.clear();
        this.loadingStartTimes.clear();
        
        window.dispatchEvent(new CustomEvent('hide-loading', {
            detail: { id: 'all' }
        }));
    }
    
    trackPromise(promise, message = 'Loading...', id = 'default') {
        this.show(message, id);
        
        return promise.finally(() => {
            this.hide(id);
        });
    }
}

// Initialize the service when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new SelectiveLoadingService();
    });
} else {
    new SelectiveLoadingService();
}

// Alpine.js integration if available
if (typeof Alpine !== 'undefined') {
    document.addEventListener('alpine:init', () => {
        if (!window.selectiveLoading) {
            new SelectiveLoadingService();
        }
    });
}