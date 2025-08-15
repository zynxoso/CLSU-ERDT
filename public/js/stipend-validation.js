/**
 * Stipend Validation System
 * Provides robust validation with SweetAlert2 notifications for stipend forms
 */

class StipendValidator {
    constructor() {
        this.initializeSweetAlert();
        this.setupValidationRules();
        this.bindEvents();
    }

    /**
     * Initialize SweetAlert2 with custom configuration
     */
    initializeSweetAlert() {
        // Ensure SweetAlert2 is available
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 is not loaded. Please include SweetAlert2 library.');
            return;
        }

        // Custom SweetAlert2 configuration for stipend validation
        this.swalConfig = {
            customClass: {
                popup: 'stipend-validation-popup',
                title: 'stipend-validation-title',
                content: 'stipend-validation-content',
                confirmButton: 'stipend-validation-confirm',
                cancelButton: 'stipend-validation-cancel'
            },
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: true
        };
    }

    /**
     * Setup validation rules for stipend operations
     */
    setupValidationRules() {
        this.validationRules = {
            frequency: {
                minDaysBetweenNotifications: 30,
                maxNotificationsPerMonth: 1
            },
            scholars: {
                minActiveScholars: 1,
                requiredStatus: 'Active'
            },
            notification: {
                maxRetries: 3,
                timeoutSeconds: 30
            }
        };
    }

    /**
     * Bind validation events
     */
    bindEvents() {
        // Listen for validation errors from server
        document.addEventListener('stipend-validation-error', (event) => {
            this.handleValidationError(event.detail);
        });

        // Listen for success events
        document.addEventListener('stipend-success', (event) => {
            this.handleSuccess(event.detail);
        });

        // Listen for warning events
        document.addEventListener('stipend-warning', (event) => {
            this.handleWarning(event.detail);
        });
    }

    /**
     * Validate stipend notification frequency
     */
    async validateNotificationFrequency(lastNotificationDate) {
        if (!lastNotificationDate) {
            return { valid: true };
        }

        const lastDate = new Date(lastNotificationDate);
        const now = new Date();
        const daysDifference = Math.floor((now - lastDate) / (1000 * 60 * 60 * 24));
        const daysRemaining = this.validationRules.frequency.minDaysBetweenNotifications - daysDifference;

        if (daysRemaining > 0) {
            const nextAllowedDate = new Date(lastDate);
            nextAllowedDate.setDate(nextAllowedDate.getDate() + this.validationRules.frequency.minDaysBetweenNotifications);

            return {
                valid: false,
                error: 'FREQUENCY_LIMIT_EXCEEDED',
                message: `Stipend notifications can only be sent once per month. Last notification was sent ${daysDifference} days ago.`,
                daysRemaining: daysRemaining,
                nextAllowedDate: nextAllowedDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                })
            };
        }

        return { valid: true };
    }

    /**
     * Validate active scholars count
     */
    async validateActiveScholars(activeScholarsCount) {
        if (activeScholarsCount < this.validationRules.scholars.minActiveScholars) {
            return {
                valid: false,
                error: 'NO_ACTIVE_SCHOLARS',
                message: 'No active scholars found. Please ensure there are scholars with "Active" status before sending notifications.'
            };
        }

        return { valid: true };
    }

    /**
     * Show validation error with detailed guidance
     */
    async showValidationError(error) {
        let config = {
            ...this.swalConfig,
            icon: 'error',
            title: 'Validation Error',
            confirmButtonText: 'I Understand',
            confirmButtonColor: '#dc2626'
        };

        switch (error.error) {
            case 'FREQUENCY_LIMIT_EXCEEDED':
                config.title = 'Notification Frequency Limit';
                config.icon = 'warning';
                config.html = `
                    <div class="text-left space-y-3">
                        <p class="text-gray-700">${error.message}</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <h4 class="font-semibold text-yellow-800 mb-2">📅 Next Available Date</h4>
                            <p class="text-yellow-700">You can send the next notification on <strong>${error.nextAllowedDate}</strong></p>
                            <p class="text-sm text-yellow-600 mt-1">(${error.daysRemaining} days remaining)</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <h4 class="font-semibold text-blue-800 mb-2">💡 Why This Limit Exists</h4>
                            <p class="text-blue-700 text-sm">This prevents spam and ensures scholars receive timely, meaningful notifications about their stipends.</p>
                        </div>
                    </div>
                `;
                config.confirmButtonColor = '#f59e0b';
                break;

            case 'NO_ACTIVE_SCHOLARS':
                config.title = 'No Active Recipients';
                config.html = `
                    <div class="text-left space-y-3">
                        <p class="text-gray-700">${error.message}</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <h4 class="font-semibold text-red-800 mb-2">🔍 What to Check</h4>
                            <ul class="text-red-700 text-sm space-y-1">
                                <li>• Verify scholars have "Active" status in the system</li>
                                <li>• Check if scholars have valid user accounts</li>
                                <li>• Ensure scholar profiles are properly configured</li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <h4 class="font-semibold text-blue-800 mb-2">🛠️ How to Fix</h4>
                            <p class="text-blue-700 text-sm">Go to Scholar Management to update scholar statuses or contact system administrator.</p>
                        </div>
                    </div>
                `;
                break;

            case 'NETWORK_ERROR':
                config.title = 'Connection Problem';
                config.html = `
                    <div class="text-left space-y-3">
                        <p class="text-gray-700">Unable to send notifications due to a network error.</p>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                            <h4 class="font-semibold text-orange-800 mb-2">🔄 What to Try</h4>
                            <ul class="text-orange-700 text-sm space-y-1">
                                <li>• Check your internet connection</li>
                                <li>• Refresh the page and try again</li>
                                <li>• Contact IT support if the problem persists</li>
                            </ul>
                        </div>
                    </div>
                `;
                config.confirmButtonColor = '#ea580c';
                break;

            default:
                config.html = `
                    <div class="text-left space-y-3">
                        <p class="text-gray-700">${error.message || 'An unexpected validation error occurred.'}</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">Please try again or contact support if the problem continues.</p>
                        </div>
                    </div>
                `;
        }

        return await Swal.fire(config);
    }

    /**
     * Show success message with details
     */
    async showSuccess(data) {
        const config = {
            ...this.swalConfig,
            icon: 'success',
            title: 'Notifications Sent Successfully!',
            html: `
                <div class="text-left space-y-3">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <h4 class="font-semibold text-green-800 mb-2">📊 Summary</h4>
                        <ul class="text-green-700 text-sm space-y-1">
                            <li>• <strong>${data.notificationsSent || 0}</strong> notifications sent</li>
                            <li>• <strong>${data.activeScholars || 0}</strong> active scholars notified</li>
                            <li>• Sent at <strong>${new Date().toLocaleString()}</strong></li>
                        </ul>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <h4 class="font-semibold text-blue-800 mb-2">📅 Next Notification</h4>
                        <p class="text-blue-700 text-sm">You can send the next stipend notification after <strong>${data.nextAllowedDate || 'one month from now'}</strong></p>
                    </div>
                </div>
            `,
            confirmButtonText: 'Great!',
            confirmButtonColor: '#16a34a',
            timer: 5000,
            timerProgressBar: true
        };

        return await Swal.fire(config);
    }

    /**
     * Show warning message
     */
    async showWarning(data) {
        const config = {
            ...this.swalConfig,
            icon: 'warning',
            title: data.title || 'Warning',
            text: data.message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#f59e0b'
        };

        return await Swal.fire(config);
    }

    /**
     * Show confirmation dialog before sending notifications
     */
    async showConfirmation(data) {
        const config = {
            ...this.swalConfig,
            icon: 'question',
            title: 'Send Stipend Notifications?',
            html: `
                <div class="text-left space-y-3">
                    <p class="text-gray-700">This will send stipend availability notifications to all active scholars.</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <h4 class="font-semibold text-blue-800 mb-2">📋 Details</h4>
                        <ul class="text-blue-700 text-sm space-y-1">
                            <li>• <strong>${data.activeScholars || 0}</strong> active scholars will be notified</li>
                            <li>• Notifications will be sent immediately</li>
                            <li>• Next notification allowed: <strong>${data.nextAllowedDate || 'in 30 days'}</strong></li>
                        </ul>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-yellow-700 text-sm">⚠️ This action cannot be undone. Scholars will receive the notification in their dashboard.</p>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Yes, Send Notifications',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            focusCancel: true
        };

        return await Swal.fire(config);
    }

    /**
     * Show loading state during notification sending
     */
    showLoading(message = 'Sending notifications...') {
        Swal.fire({
            ...this.swalConfig,
            title: message,
            html: `
                <div class="text-center space-y-3">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="text-gray-600 text-sm">Please wait while we process your request...</p>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }

    /**
     * Handle validation error events
     */
    handleValidationError(errorData) {
        this.showValidationError(errorData);
    }

    /**
     * Handle success events
     */
    handleSuccess(successData) {
        this.showSuccess(successData);
    }

    /**
     * Handle warning events
     */
    handleWarning(warningData) {
        this.showWarning(warningData);
    }

    /**
     * Validate form before submission
     */
    async validateBeforeSubmit(formData) {
        const errors = [];

        // Validate notification frequency
        if (formData.lastNotificationDate) {
            const frequencyValidation = await this.validateNotificationFrequency(formData.lastNotificationDate);
            if (!frequencyValidation.valid) {
                errors.push(frequencyValidation);
            }
        }

        // Validate active scholars
        if (formData.activeScholarsCount !== undefined) {
            const scholarsValidation = await this.validateActiveScholars(formData.activeScholarsCount);
            if (!scholarsValidation.valid) {
                errors.push(scholarsValidation);
            }
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }
}

// Initialize the validator when the DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.stipendValidator = new StipendValidator();
    console.log('Stipend Validation System initialized');
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = StipendValidator;
}