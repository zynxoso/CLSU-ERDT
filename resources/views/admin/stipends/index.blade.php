@extends('layouts.app')

@section('title', 'Stipend Notifications')

@section('content')
    <!-- Validation Error Handling -->
    @if(session('validation_error'))
        <div id="serverValidationError" 
             data-error-type="{{ session('error_type') }}" 
             data-error-message="{{ session('validation_error') }}"
             style="display: none;"></div>
    @endif
    <div class="bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header -->
            <div class="rounded-xl shadow-sm border mb-6" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                <div class="px-4 sm:px-6 py-5">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-start space-x-3 sm:space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center"
                                    style="background-color: rgba(74, 144, 226, 0.1);">
                                    <i class="fas fa-bell text-base sm:text-lg" style="color: #4A90E2;"></i>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h1 class="text-xl sm:text-2xl font-bold truncate" style="color: rgb(23 23 23);">Stipend Notifications</h1>
                                <p class="mt-1 text-sm sm:text-base" style="color: rgb(64 64 64);">Send stipend notifications to active scholars</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                            <a href="{{ route('admin.stipends.bulk-notify') }}"
                                id="sendNotificationsBtn"
                                class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-sm sm:text-base transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5 w-full sm:w-auto"
                                style="background: linear-gradient(135deg, #4CAF50 0%, #43A047 100%); color: rgb(255 255 255);">
                                <i class="fas fa-paper-plane mr-2"></i>
                                <span class="hidden sm:inline">Send Notifications</span>
                                <span class="sm:hidden">Send</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <!-- Active Scholars Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                    <div class="px-4 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: rgba(76, 175, 80, 0.1);">
                                    <i class="fas fa-user-graduate text-sm sm:text-base" style="color: rgb(34 197 94);"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium truncate" style="color: rgb(64 64 64);">Active Scholars</p>
                                <p class="text-xl sm:text-2xl font-bold" style="color: rgb(23 23 23);" id="activeScholarsCount">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Notifications Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                    <div class="px-4 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: rgba(74, 144, 226, 0.1);">
                                    <i class="fas fa-bell text-sm sm:text-base" style="color: #4A90E2;"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium truncate" style="color: rgb(64 64 64);">Total Notifications</p>
                                <p class="text-xl sm:text-2xl font-bold" style="color: rgb(23 23 23);" id="totalNotificationsCount">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Last Notification Card -->
                <div class="rounded-xl shadow-sm border sm:col-span-2 lg:col-span-1" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                    <div class="px-4 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: rgba(255, 152, 0, 0.1);">
                                    <i class="fas fa-clock text-sm sm:text-base" style="color: #FF9800;"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium truncate" style="color: rgb(64 64 64);">Last Notification</p>
                                <p class="text-xs sm:text-sm font-bold break-words" style="color: rgb(23 23 23);" id="lastNotificationDate">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="rounded-xl shadow-sm border" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                <div class="px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas fa-info-circle text-base sm:text-lg" style="color: #4A90E2;"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold mb-3" style="color: rgb(23 23 23);">How Stipend Notifications Work</h3>
                            <div class="space-y-2 sm:space-y-3 text-sm sm:text-base" style="color: rgb(64 64 64);">
                                <div class="flex items-start space-x-2">
                                    <span class="text-blue-500 mt-1">•</span>
                                    <p><strong class="text-gray-700">Active Scholars:</strong> Only scholars with 'Active' status will receive notifications</p>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="text-blue-500 mt-1">•</span>
                                    <p><strong class="text-gray-700">Notification Content:</strong> A simple message informing scholars that their stipend is now available</p>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="text-blue-500 mt-1">•</span>
                                    <p><strong class="text-gray-700">Delivery:</strong> Notifications are sent through the in-system notification center only</p>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="text-blue-500 mt-1">•</span>
                                    <p><strong class="text-gray-700">Bulk Sending:</strong> Use the "Send Notifications" button to notify all active scholars at once</p>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="text-blue-500 mt-1">•</span>
                                    <p><strong class="text-gray-700">Frequency Limit:</strong> Notifications can only be sent once per month to prevent spam</p>
                                </div>
                            </div>
                            
                            <!-- Last Notification Info -->
                            <div id="lastNotificationInfo" class="mt-4 p-3 sm:p-4 rounded-lg" style="background-color: #f8f9fa; border: 1px solid #e9ecef; display: none;">
                                <h4 class="text-sm sm:text-base font-semibold mb-2" style="color: #495057;">Last Notification Status</h4>
                                <div class="text-sm sm:text-base space-y-1" style="color: #6c757d;">
                                    <p id="lastNotificationText" class="break-words"></p>
                                    <p id="nextAllowedText" class="break-words"></p>
                                </div>
                            </div>
                            
                            <!-- Validation Modal -->
                            <div id="validationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                                    <div class="p-6">
                                        <div class="flex items-center mb-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-lg font-medium text-gray-900">Notification Limit Reached</h3>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <p id="modalMessage" class="text-sm text-gray-600"></p>
                                        </div>
                                        <div class="flex justify-end">
                                            <button onclick="closeValidationModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Stipend Validation System -->
    <script src="{{ asset('js/stipend-validation.js') }}"></script>
    
    <script>
        // Enhanced stipend management with validation
        class StipendManager {
            constructor() {
                this.elements = {};
                this.statsData = null;
                this.validator = null;
                this.init();
            }

            init() {
                this.cacheElements();
                this.bindEvents();
                this.loadStats();
                
                // Wait for validator to be available
                this.waitForValidator();
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
                    activeScholarsCount: document.getElementById('activeScholarsCount'),
                    totalNotificationsCount: document.getElementById('totalNotificationsCount'),
                    lastNotificationDate: document.getElementById('lastNotificationDate'),
                    sendBtn: document.getElementById('sendNotificationsBtn'),
                    lastNotificationInfo: document.getElementById('lastNotificationInfo'),
                    lastNotificationText: document.getElementById('lastNotificationText'),
                    nextAllowedText: document.getElementById('nextAllowedText'),
                    validationModal: document.getElementById('validationModal'),
                    modalMessage: document.getElementById('modalMessage')
                };
            }

            bindEvents() {
                // Enhanced send button click handler
                if (this.elements.sendBtn) {
                    this.elements.sendBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.handleSendNotifications();
                    });
                }

                // Close modal events
                if (this.elements.validationModal) {
                    this.elements.validationModal.addEventListener('click', (e) => {
                        if (e.target === this.elements.validationModal) {
                            this.closeValidationModal();
                        }
                    });
                }

                // Listen for validation events
                document.addEventListener('stipend-validation-error', (e) => {
                    this.handleValidationError(e.detail);
                });
            }

            async handleSendNotifications() {
                if (!this.validator || !this.statsData) {
                    this.showFallbackValidation('System not ready. Please refresh the page and try again.');
                    return;
                }

                // Validate before proceeding
                const validation = await this.validator.validateBeforeSubmit({
                    lastNotificationDate: this.statsData.last_notification_date,
                    activeScholarsCount: this.statsData.active_scholars
                });

                if (!validation.valid) {
                    // Show the first validation error
                    await this.validator.showValidationError(validation.errors[0]);
                    return;
                }

                // Show confirmation dialog
                const confirmation = await this.validator.showConfirmation({
                    activeScholars: this.statsData.active_scholars,
                    nextAllowedDate: this.getNextAllowedDate()
                });

                if (confirmation.isConfirmed) {
                    // Proceed to bulk notify page
                    window.location.href = '{{ route("admin.stipends.bulk-notify") }}';
                }
            }

            getNextAllowedDate() {
                if (!this.statsData.last_notification_date || this.statsData.last_notification_date === 'Never') {
                    return 'in 30 days';
                }
                
                const lastDate = new Date(this.statsData.last_notification_date);
                const nextDate = new Date(lastDate);
                nextDate.setDate(nextDate.getDate() + 30);
                
                return nextDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            async loadStats() {
                try {
                    const response = await fetch('{{ route("admin.stipends.stats") }}');
                    
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    
                    this.statsData = await response.json();
                    this.updateStatsDisplay(this.statsData);
                    this.updateButtonState(this.statsData);
                    
                } catch (error) {
                    console.error('Error loading stats:', error);
                    this.showStatsError();
                    
                    // Show user-friendly error
                    if (this.validator) {
                        this.validator.showValidationError({
                            error: 'NETWORK_ERROR',
                            message: 'Unable to load stipend statistics. Please check your connection and refresh the page.'
                        });
                    }
                }
            }

            updateStatsDisplay(data) {
                // Update statistics with enhanced formatting
                if (this.elements.activeScholarsCount) {
                    this.elements.activeScholarsCount.textContent = data.active_scholars || 0;
                }
                
                if (this.elements.totalNotificationsCount) {
                    this.elements.totalNotificationsCount.textContent = data.total_notifications || 0;
                }
                
                if (this.elements.lastNotificationDate) {
                    this.elements.lastNotificationDate.textContent = data.last_notification_date || 'Never';
                }

                // Update last notification info section
                this.updateLastNotificationInfo(data);
            }

            updateLastNotificationInfo(data) {
                if (!this.elements.lastNotificationInfo) return;

                if (data.last_notification_date && data.last_notification_date !== 'Never') {
                    this.elements.lastNotificationInfo.style.display = 'block';
                    
                    if (this.elements.lastNotificationText) {
                        this.elements.lastNotificationText.textContent = `Last notification sent: ${data.last_notification_date}`;
                    }
                    
                    if (this.elements.nextAllowedText) {
                        if (data.can_send_notification) {
                            this.elements.nextAllowedText.textContent = 'You can send a new notification now.';
                            this.elements.nextAllowedText.className = 'text-green-600 font-medium';
                        } else {
                            this.elements.nextAllowedText.textContent = `Next notification allowed: ${data.next_allowed_date} (${data.days_remaining} days remaining)`;
                            this.elements.nextAllowedText.className = 'text-orange-600 font-medium';
                        }
                    }
                } else {
                    this.elements.lastNotificationInfo.style.display = 'none';
                }
            }

            updateButtonState(data) {
                if (!this.elements.sendBtn) return;

                if (!data.can_send_notification && data.last_notification_date !== 'Never') {
                    // Disable button visually but keep click handler for validation
                    this.elements.sendBtn.style.background = 'linear-gradient(135deg, #9E9E9E 0%, #757575 100%)';
                    this.elements.sendBtn.style.cursor = 'not-allowed';
                    this.elements.sendBtn.title = `Next notification available on ${data.next_allowed_date}`;
                } else {
                    // Enable button
                    this.elements.sendBtn.style.background = 'linear-gradient(135deg, #4CAF50 0%, #43A047 100%)';
                    this.elements.sendBtn.style.cursor = 'pointer';
                    this.elements.sendBtn.title = 'Send stipend notifications to all active scholars';
                }
            }

            showStatsError() {
                if (this.elements.activeScholarsCount) {
                    this.elements.activeScholarsCount.textContent = 'Error';
                }
                if (this.elements.lastNotificationDate) {
                    this.elements.lastNotificationDate.textContent = 'Error';
                }
                if (this.elements.totalNotificationsCount) {
                    this.elements.totalNotificationsCount.textContent = 'Error';
                }
            }

            showFallbackValidation(message) {
                if (this.elements.modalMessage && this.elements.validationModal) {
                    this.elements.modalMessage.textContent = message;
                    this.elements.validationModal.style.display = 'flex';
                } else {
                    alert(message);
                }
            }

            closeValidationModal() {
                if (this.elements.validationModal) {
                    this.elements.validationModal.style.display = 'none';
                }
            }

            handleValidationError(errorData) {
                console.error('Stipend validation error:', errorData);
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            window.stipendManager = new StipendManager();
            
            // Handle server-side validation errors
            handleServerValidationErrors();
        });
        
        // Handle server-side validation errors
        function handleServerValidationErrors() {
            const errorElement = document.getElementById('serverValidationError');
            if (errorElement) {
                const errorType = errorElement.getAttribute('data-error-type');
                const errorMessage = errorElement.getAttribute('data-error-message');
                
                // Wait for validator to be available
                const showError = () => {
                    if (window.stipendValidator) {
                        window.stipendValidator.showValidationError({
                            error: errorType,
                            message: errorMessage
                        });
                    } else {
                        setTimeout(showError, 100);
                    }
                };
                
                showError();
            }
        }

        // Legacy function for modal close button
        function closeValidationModal() {
            if (window.stipendManager) {
                window.stipendManager.closeValidationModal();
            }
        }
    </script>
@endsection
