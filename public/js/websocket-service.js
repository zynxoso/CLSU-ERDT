/**
 * WebSocket Service
 * Handles real-time bidirectional communication via WebSockets
 */
class WebSocketService {
    constructor() {
        this.socket = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 1000; // Start with 1 second
        this.isConnected = false;
        this.listeners = new Map();
        this.userId = null;
        this.heartbeatInterval = null;
        this.heartbeatTimeout = null;
        this.messageQueue = [];
        
        // Bind methods to preserve context
        this.handleOpen = this.handleOpen.bind(this);
        this.handleMessage = this.handleMessage.bind(this);
        this.handleError = this.handleError.bind(this);
        this.handleClose = this.handleClose.bind(this);
        this.sendHeartbeat = this.sendHeartbeat.bind(this);
    }

    /**
     * Initialize WebSocket connection
     * @param {string} userId - User ID for personalized events
     * @param {string} wsUrl - WebSocket URL (optional)
     */
    connect(userId, wsUrl = null) {
        if (this.isConnected) {
            console.log('WebSocket already connected');
            return;
        }

        this.userId = userId;
        this.createConnection(wsUrl);
    }

    /**
     * Create WebSocket connection
     * @param {string} wsUrl - WebSocket URL
     */
    createConnection(wsUrl = null) {
        try {
            // Default WebSocket URL
            const url = wsUrl || `ws://localhost:6001/app/your-app-key?protocol=7&client=js&version=7.0.3&flash=false`;
            
            this.socket = new WebSocket(url);
            
            this.socket.onopen = this.handleOpen;
            this.socket.onmessage = this.handleMessage;
            this.socket.onerror = this.handleError;
            this.socket.onclose = this.handleClose;
            
        } catch (error) {
            console.error('Failed to create WebSocket connection:', error);
            this.scheduleReconnect();
        }
    }

    /**
     * Handle connection open
     */
    handleOpen() {
        console.log('WebSocket connection established');
        this.isConnected = true;
        this.reconnectAttempts = 0;
        this.reconnectDelay = 1000;
        
        // Start heartbeat
        this.startHeartbeat();
        
        // Subscribe to user-specific channel
        this.subscribe(`private-user.${this.userId}`);
        this.subscribe('system-notifications');
        this.subscribe('capacity-updates');
        
        // Send queued messages
        this.flushMessageQueue();
        
        // Notify listeners
        this.emit('connected');
        
        // Show connection status
        this.showConnectionStatus('Connected to real-time updates', 'success');
    }

    /**
     * Handle incoming messages
     * @param {MessageEvent} event 
     */
    handleMessage(event) {
        try {
            const data = JSON.parse(event.data);
            console.log('WebSocket message received:', data);
            
            // Handle different message types
            switch (data.event) {
                case 'pusher:connection_established':
                    this.handleConnectionEstablished(data);
                    break;
                case 'pusher_internal:subscription_succeeded':
                    this.handleSubscriptionSucceeded(data);
                    break;
                case 'pusher:pong':
                    this.handlePong();
                    break;
                default:
                    this.handleCustomEvent(data);
                    break;
            }
            
            // Emit to registered listeners
            this.emit('message', data);
            
        } catch (error) {
            console.error('Failed to parse WebSocket message:', error);
        }
    }

    /**
     * Handle connection errors
     * @param {Event} event 
     */
    handleError(event) {
        console.error('WebSocket connection error:', event);
        this.isConnected = false;
        this.stopHeartbeat();
        
        this.emit('error', event);
    }

    /**
     * Handle connection close
     * @param {CloseEvent} event 
     */
    handleClose(event) {
        console.log('WebSocket connection closed:', event.code, event.reason);
        this.isConnected = false;
        this.stopHeartbeat();
        
        this.emit('disconnected', event);
        
        // Attempt to reconnect unless it was a clean close
        if (event.code !== 1000) {
            this.scheduleReconnect();
        }
    }

    /**
     * Handle connection established
     * @param {Object} data 
     */
    handleConnectionEstablished(data) {
        console.log('Pusher connection established:', data);
        this.socketId = data.data ? JSON.parse(data.data).socket_id : null;
    }

    /**
     * Handle subscription succeeded
     * @param {Object} data 
     */
    handleSubscriptionSucceeded(data) {
        console.log('Subscription succeeded:', data.channel);
        this.emit('subscribed', data.channel);
    }

    /**
     * Handle custom events
     * @param {Object} data 
     */
    handleCustomEvent(data) {
        switch (data.event) {
            case 'notification':
                this.handleNotification(data);
                break;
            case 'system-alert':
                this.handleSystemAlert(data);
                break;
            case 'capacity-update':
                this.handleCapacityUpdate(data);
                break;
            case 'health-check':
                this.handleHealthCheck(data);
                break;
            case 'user-activity':
                this.handleUserActivity(data);
                break;
            default:
                console.log('Unknown event:', data.event);
                break;
        }
    }

    /**
     * Handle notification events
     * @param {Object} data 
     */
    handleNotification(data) {
        try {
            const notification = data.data;
            
            // Update Livewire components
            if (window.Livewire) {
                window.Livewire.dispatch('notification-received', notification);
            }
            
            // Show toast notification
            this.showToastNotification(notification);
            
            // Emit to listeners
            this.emit('notification', notification);
            
        } catch (error) {
            console.error('Failed to handle notification:', error);
        }
    }

    /**
     * Handle system alert events
     * @param {Object} data 
     */
    handleSystemAlert(data) {
        try {
            const alert = data.data;
            
            // Show system alert
            this.showSystemAlert(alert);
            
            // Emit to listeners
            this.emit('system-alert', alert);
            
        } catch (error) {
            console.error('Failed to handle system alert:', error);
        }
    }

    /**
     * Handle capacity update events
     * @param {Object} data 
     */
    handleCapacityUpdate(data) {
        try {
            const capacityData = data.data;
            
            // Update dashboard if present
            if (window.Livewire) {
                window.Livewire.dispatch('capacity-updated', capacityData);
            }
            
            // Emit to listeners
            this.emit('capacity-update', capacityData);
            
        } catch (error) {
            console.error('Failed to handle capacity update:', error);
        }
    }

    /**
     * Handle health check events
     * @param {Object} data 
     */
    handleHealthCheck(data) {
        try {
            const healthData = data.data;
            
            // Update dashboard health status
            if (window.Livewire) {
                window.Livewire.dispatch('health-updated', healthData);
            }
            
            // Emit to listeners
            this.emit('health-check', healthData);
            
        } catch (error) {
            console.error('Failed to handle health check:', error);
        }
    }

    /**
     * Handle user activity events
     * @param {Object} data 
     */
    handleUserActivity(data) {
        try {
            const activityData = data.data;
            
            // Update user activity indicators
            if (window.Livewire) {
                window.Livewire.dispatch('user-activity-updated', activityData);
            }
            
            // Emit to listeners
            this.emit('user-activity', activityData);
            
        } catch (error) {
            console.error('Failed to handle user activity:', error);
        }
    }

    /**
     * Subscribe to a channel
     * @param {string} channel 
     */
    subscribe(channel) {
        if (!this.isConnected) {
            console.warn('Cannot subscribe: WebSocket not connected');
            return;
        }

        const message = {
            event: 'pusher:subscribe',
            data: {
                channel: channel
            }
        };

        this.send(message);
    }

    /**
     * Unsubscribe from a channel
     * @param {string} channel 
     */
    unsubscribe(channel) {
        if (!this.isConnected) {
            console.warn('Cannot unsubscribe: WebSocket not connected');
            return;
        }

        const message = {
            event: 'pusher:unsubscribe',
            data: {
                channel: channel
            }
        };

        this.send(message);
    }

    /**
     * Send message via WebSocket
     * @param {Object} message 
     */
    send(message) {
        if (!this.isConnected) {
            // Queue message for later
            this.messageQueue.push(message);
            return;
        }

        try {
            this.socket.send(JSON.stringify(message));
        } catch (error) {
            console.error('Failed to send WebSocket message:', error);
        }
    }

    /**
     * Send queued messages
     */
    flushMessageQueue() {
        while (this.messageQueue.length > 0) {
            const message = this.messageQueue.shift();
            this.send(message);
        }
    }

    /**
     * Start heartbeat mechanism
     */
    startHeartbeat() {
        this.heartbeatInterval = setInterval(this.sendHeartbeat, 30000); // Every 30 seconds
    }

    /**
     * Stop heartbeat mechanism
     */
    stopHeartbeat() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = null;
        }
        if (this.heartbeatTimeout) {
            clearTimeout(this.heartbeatTimeout);
            this.heartbeatTimeout = null;
        }
    }

    /**
     * Send heartbeat ping
     */
    sendHeartbeat() {
        if (!this.isConnected) return;

        this.send({ event: 'pusher:ping', data: {} });
        
        // Set timeout for pong response
        this.heartbeatTimeout = setTimeout(() => {
            console.warn('Heartbeat timeout - connection may be lost');
            this.socket.close();
        }, 10000); // 10 second timeout
    }

    /**
     * Handle heartbeat pong
     */
    handlePong() {
        if (this.heartbeatTimeout) {
            clearTimeout(this.heartbeatTimeout);
            this.heartbeatTimeout = null;
        }
    }

    /**
     * Schedule reconnection attempt
     */
    scheduleReconnect() {
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.error('Max reconnection attempts reached');
            this.showConnectionStatus('Real-time updates unavailable', 'error');
            return;
        }

        this.reconnectAttempts++;
        console.log(`Scheduling reconnect attempt ${this.reconnectAttempts} in ${this.reconnectDelay}ms`);
        
        setTimeout(() => {
            if (!this.isConnected) {
                this.createConnection();
            }
        }, this.reconnectDelay);
        
        // Exponential backoff
        this.reconnectDelay = Math.min(this.reconnectDelay * 2, 30000);
    }

    /**
     * Add event listener
     * @param {string} event 
     * @param {Function} callback 
     */
    on(event, callback) {
        if (!this.listeners.has(event)) {
            this.listeners.set(event, []);
        }
        this.listeners.get(event).push(callback);
    }

    /**
     * Remove event listener
     * @param {string} event 
     * @param {Function} callback 
     */
    off(event, callback) {
        if (this.listeners.has(event)) {
            const callbacks = this.listeners.get(event);
            const index = callbacks.indexOf(callback);
            if (index > -1) {
                callbacks.splice(index, 1);
            }
        }
    }

    /**
     * Emit event to listeners
     * @param {string} event 
     * @param {*} data 
     */
    emit(event, data) {
        if (this.listeners.has(event)) {
            this.listeners.get(event).forEach(callback => {
                try {
                    callback(data);
                } catch (error) {
                    console.error('Error in WebSocket event listener:', error);
                }
            });
        }
    }

    /**
     * Show toast notification
     * @param {Object} notification 
     */
    showToastNotification(notification) {
        if (window.toast) {
            window.toast(notification.message, notification.type || 'info');
        } else if (window.Swal) {
            const Toast = window.Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: notification.type || 'info',
                title: notification.message
            });
        }
    }

    /**
     * Show system alert
     * @param {Object} alert 
     */
    showSystemAlert(alert) {
        if (window.Swal) {
            window.Swal.fire({
                icon: alert.type || 'warning',
                title: alert.title || 'System Alert',
                text: alert.message,
                confirmButtonText: 'OK'
            });
        }
    }

    /**
     * Show connection status
     * @param {string} message 
     * @param {string} type 
     */
    showConnectionStatus(message, type) {
        // Create or update connection status indicator
        let statusEl = document.getElementById('ws-status');
        if (!statusEl) {
            statusEl = document.createElement('div');
            statusEl.id = 'ws-status';
            statusEl.className = 'fixed top-20 right-4 px-4 py-2 rounded-lg text-white text-sm z-40';
            document.body.appendChild(statusEl);
        }

        statusEl.textContent = message;
        statusEl.className = `fixed top-20 right-4 px-4 py-2 rounded-lg text-white text-sm z-40 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'warning' ? 'bg-yellow-500' : 'bg-red-500'
        }`;

        // Auto-hide success messages
        if (type === 'success') {
            setTimeout(() => {
                if (statusEl && statusEl.parentNode) {
                    statusEl.parentNode.removeChild(statusEl);
                }
            }, 3000);
        }
    }

    /**
     * Disconnect WebSocket
     */
    disconnect() {
        if (this.socket) {
            this.socket.close(1000, 'Client disconnect');
            this.socket = null;
        }
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.stopHeartbeat();
        console.log('WebSocket disconnected');
    }

    /**
     * Get connection status
     * @returns {boolean}
     */
    getConnectionStatus() {
        return this.isConnected;
    }

    /**
     * Get socket ID
     * @returns {string|null}
     */
    getSocketId() {
        return this.socketId;
    }
}

// Initialize WebSocket service when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.webSocketService = new WebSocketService();
    });
} else {
    window.webSocketService = new WebSocketService();
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WebSocketService;
}