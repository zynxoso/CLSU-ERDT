class NotificationService {
    constructor() {
        this.isOnline = navigator.onLine;
        this.retryCount = 0;
        this.maxRetries = 3;
        
        // Listen for online/offline events
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.retryCount = 0;
        });
        
        window.addEventListener('offline', () => {
            this.isOnline = false;
        });
    }
    
    async updateNotificationCount(route) {
        if (!this.isOnline) {
            console.warn('Device is offline - skipping notification update');
            return null;
        }
        
        try {
            const response = await fetch(route, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                signal: AbortSignal.timeout(10000) // 10 second timeout
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            this.retryCount = 0; // Reset retry count on success
            return data.count;
        } catch (error) {
            this.retryCount++;
            
            if (this.retryCount >= this.maxRetries) {
                console.error('Max retries reached for notification updates');
                return null;
            }
            
            console.warn(`Failed to fetch notification count (attempt ${this.retryCount}/${this.maxRetries}):`, error.message);
            return null;
        }
    }
}

// Make it globally available
window.notificationService = new NotificationService();