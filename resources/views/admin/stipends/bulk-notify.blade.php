@extends('layouts.app')

@section('title', 'Send Stipend Notification')

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="space-y-4 sm:space-y-6">
            <!-- Simple Header Card -->
            <div class="bg-white rounded-xl shadow-lg border" style="border-color: rgb(224 224 224);">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b" style="background: linear-gradient(135deg, #4A90E2 0%, #1565C0 100%); border-color: rgb(224 224 224);" class="rounded-t-xl">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-start space-x-3 sm:space-x-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(255,255,255,0.2);">
                                <i class="fas fa-money-bill-wave text-lg sm:text-xl" style="color: rgb(255 255 255);"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h1 class="text-xl sm:text-2xl font-bold text-white break-words">Stipend Available Notification</h1>
                                <p class="text-blue-100 mt-1 text-sm sm:text-base">Notify scholars that their stipends are now available</p>
                            </div>
                        </div>
                        <div class="w-full lg:w-auto">
                            <a href="{{ route('admin.stipends.index') }}" 
                               class="px-4 py-2.5 rounded-lg inline-flex items-center justify-center text-sm font-medium transition-all duration-200 hover:shadow-md w-full lg:w-auto"
                               style="background-color: rgba(255,255,255,0.2); color: rgb(255 255 255); border: 1px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-arrow-left mr-2"></i> 
                                <span class="hidden sm:inline">Back to Stipends</span>
                                <span class="sm:hidden">Back</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Send Stipend Available Notification -->
            <div class="bg-white rounded-xl shadow-lg border" style="border-color: rgb(224 224 224);">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); border-color: rgb(224 224 224);" class="rounded-t-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(255,255,255,0.2);">
                            <i class="fas fa-bell text-sm sm:text-lg" style="color: rgb(255 255 255);"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-white break-words">Send Stipend Available Notification</h2>
                    </div>
                </div>
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 flex-shrink-0"></i>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm sm:text-base font-medium text-blue-800">Notification Details</h3>
                                <p class="text-sm sm:text-base mt-1 break-words" style="color: #4A90E2;">
                                    This will send a notification to all active scholars informing them that their stipends are now available for collection or disbursement.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Preview -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                        <h3 class="text-sm sm:text-base font-medium text-gray-700 mb-2 sm:mb-3">Notification Message:</h3>
                        <div class="text-sm sm:text-base text-gray-600 space-y-2">
                            <p class="font-medium break-words">Subject: Your Stipend is Now Available</p>
                            <p>Dear Scholar,</p>
                            <p class="break-words">We are pleased to inform you that your stipend is now available. Please contact the ERDT office for collection details.</p>
                            <p>Best regards,<br>CLSU-ERDT Team</p>
                        </div>
                    </div>

                    <!-- Send Button -->
                     <div class="pt-2 sm:pt-4">
                         <button type="button" id="sendNotificationBtn" onclick="sendStipendNotification()"
                                 class="w-full px-4 sm:px-6 py-3 sm:py-3.5 rounded-xl font-semibold text-sm sm:text-base transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5" 
                                 style="background: linear-gradient(135deg, #4CAF50 0%, #43A047 100%); color: rgb(255 255 255);">
                             <i class="fas fa-paper-plane mr-2"></i> 
                             <span class="hidden sm:inline">Send Notification to All Scholars</span>
                             <span class="sm:hidden">Send to All Scholars</span>
                         </button>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-4 sm:p-6">
            <div class="flex items-start space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Success</h3>
                </div>
            </div>
            <div class="mb-4 sm:mb-6">
                <p class="text-sm sm:text-base text-gray-600 break-words">Notifications sent successfully!</p>
            </div>
            <div class="flex justify-end">
                <button onclick="closeSuccessModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm sm:text-base font-medium">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-4 sm:p-6">
            <div class="flex items-start space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Error</h3>
                </div>
            </div>
            <div class="mb-4 sm:mb-6">
                <p id="errorMessage" class="text-sm sm:text-base text-gray-600 break-words"></p>
            </div>
            <div class="flex justify-end">
                <button onclick="closeErrorModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm sm:text-base font-medium">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Stipend Validation System -->
<script src="{{ asset('js/stipend-validation.js') }}"></script>

<script>
// Enhanced bulk notification manager with validation
class BulkNotificationManager {
    constructor() {
        this.validator = null;
        this.isProcessing = false;
        this.elements = {};
        this.init();
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.waitForValidator();
        this.validatePageAccess();
    }

    waitForValidator() {
        if (window.stipendValidator) {
            this.validator = window.stipendValidator;
        } else {
            setTimeout(() => this.waitForValidator(), 100);
        }
    }

    cacheElements() {
        this.elements = {
            sendBtn: document.getElementById('sendNotificationBtn'),
            successModal: document.getElementById('successModal'),
            errorModal: document.getElementById('errorModal'),
            errorMessage: document.getElementById('errorMessage')
        };
    }

    bindEvents() {
        // Enhanced send button handler
        if (this.elements.sendBtn) {
            this.elements.sendBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleSendNotifications();
            });
        }

        // Modal close events
        this.bindModalEvents();

        // Listen for validation events
        document.addEventListener('stipend-validation-error', (e) => {
            this.handleValidationError(e.detail);
        });
    }

    bindModalEvents() {
        const modals = ['successModal', 'errorModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.closeModal(modalId);
                    }
                });
            }
        });
    }

    async validatePageAccess() {
        // Validate that user should be on this page
        try {
            const response = await fetch('{{ route("admin.stipends.stats") }}');
            if (!response.ok) throw new Error('Unable to verify access');
            
            const data = await response.json();
            
            if (!data.can_send_notification && data.last_notification_date !== 'Never') {
                // User shouldn't be here - redirect with validation message
                if (this.validator) {
                    await this.validator.showValidationError({
                        error: 'FREQUENCY_LIMIT',
                        message: `You cannot send notifications yet. Next notification allowed: ${data.next_allowed_date}`
                    });
                }
                
                setTimeout(() => {
                    window.location.href = '{{ route("admin.stipends.index") }}';
                }, 3000);
            }
        } catch (error) {
            console.error('Error validating page access:', error);
        }
    }

    async handleSendNotifications() {
        if (this.isProcessing) {
            if (this.validator) {
                await this.validator.showValidationError({
                    error: 'ALREADY_PROCESSING',
                    message: 'A notification request is already being processed. Please wait.'
                });
            }
            return;
        }

        if (!this.validator) {
            this.showFallbackError('System not ready. Please refresh the page and try again.');
            return;
        }

        // Pre-send validation
        const validation = await this.validator.validateBeforeSubmit({
            activeScholarsCount: {{ $activeScholarsCount ?? 0 }}
        });

        if (!validation.valid) {
            await this.validator.showValidationError(validation.errors[0]);
            return;
        }

        // Show final confirmation
        const confirmation = await this.validator.showFinalConfirmation({
            activeScholars: {{ $activeScholarsCount ?? 0 }}
        });

        if (!confirmation.isConfirmed) {
            return;
        }

        // Proceed with sending
        await this.sendNotifications();
    }

    async sendNotifications() {
        this.isProcessing = true;
        this.updateButtonState(true);

        // Show loading state
        if (this.validator) {
            this.validator.showLoading('Sending notifications to all active scholars...');
        }

        try {
            const response = await fetch('{{ route("admin.stipends.bulk-notify.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            // Close loading state
            if (this.validator) {
                this.validator.closeLoading();
            }

            if (response.ok && data.success) {
                await this.handleSuccess(data);
            } else {
                await this.handleError(data, response.status);
            }

        } catch (error) {
            console.error('Network error:', error);
            
            if (this.validator) {
                this.validator.closeLoading();
                await this.validator.showValidationError({
                    error: 'NETWORK_ERROR',
                    message: 'Network error occurred. Please check your connection and try again.'
                });
            } else {
                this.showFallbackError('Network error. Please check your connection and try again.');
            }
        } finally {
            this.isProcessing = false;
            this.updateButtonState(false);
        }
    }

    async handleSuccess(data) {
        if (this.validator) {
            const result = await this.validator.showSuccess({
                message: data.message,
                notificationsSent: data.notifications_sent || {{ $activeScholarsCount ?? 0 }}
            });

            if (result.isConfirmed) {
                window.location.href = '{{ route("admin.stipends.index") }}';
            }
        } else {
            this.showSuccessModal();
        }
    }

    async handleError(data, statusCode) {
        let errorType = 'UNKNOWN_ERROR';
        let message = data.message || 'An error occurred while sending notifications.';

        // Determine error type based on status code and message
        if (statusCode === 422) {
            errorType = 'FREQUENCY_LIMIT';
        } else if (statusCode === 403) {
            errorType = 'UNAUTHORIZED';
        } else if (statusCode === 500) {
            errorType = 'SERVER_ERROR';
        }

        if (this.validator) {
            await this.validator.showValidationError({
                error: errorType,
                message: message
            });
        } else {
            this.showFallbackError(message);
        }
    }

    updateButtonState(isLoading) {
        if (!this.elements.sendBtn) return;

        if (isLoading) {
            this.elements.sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
            this.elements.sendBtn.disabled = true;
            this.elements.sendBtn.style.cursor = 'not-allowed';
        } else {
            this.elements.sendBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Send Notification to All Scholars';
            this.elements.sendBtn.disabled = false;
            this.elements.sendBtn.style.cursor = 'pointer';
        }
    }

    // Fallback methods for when validator is not available
    showSuccessModal() {
        if (this.elements.successModal) {
            this.elements.successModal.style.display = 'flex';
        } else {
            alert('Notifications sent successfully!');
            window.location.href = '{{ route("admin.stipends.index") }}';
        }
    }

    showFallbackError(message) {
        if (this.elements.errorModal && this.elements.errorMessage) {
            this.elements.errorMessage.textContent = message;
            this.elements.errorModal.style.display = 'flex';
        } else {
            alert('Error: ' + message);
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            if (modalId === 'successModal') {
                window.location.href = '{{ route("admin.stipends.index") }}';
            }
        }
    }

    handleValidationError(errorData) {
        console.error('Bulk notification validation error:', errorData);
    }
}

// Legacy functions for backward compatibility
function sendStipendNotification() {
    if (window.bulkNotificationManager) {
        window.bulkNotificationManager.handleSendNotifications();
    }
}

function showSuccessModal() {
    if (window.bulkNotificationManager) {
        window.bulkNotificationManager.showSuccessModal();
    }
}

function closeSuccessModal() {
    if (window.bulkNotificationManager) {
        window.bulkNotificationManager.closeModal('successModal');
    }
}

function showErrorModal(message) {
    if (window.bulkNotificationManager) {
        window.bulkNotificationManager.showFallbackError(message);
    }
}

function closeErrorModal() {
    if (window.bulkNotificationManager) {
        window.bulkNotificationManager.closeModal('errorModal');
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.bulkNotificationManager = new BulkNotificationManager();
});
</script>
@endsection
