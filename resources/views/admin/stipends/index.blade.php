@extends('layouts.app')

@section('title', 'Stipend Notifications')

@section('content')
    <div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <!-- Header -->
            <div class="rounded-xl shadow-sm border mb-6" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-5">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                        <div class="flex items-start space-x-4 mb-4 lg:mb-0">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                    style="background-color: rgba(74, 144, 226, 0.1);">
                                    <i class="fas fa-bell text-lg" style="color: #4A90E2;"></i>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold" style="color: #212121;">Stipend Notifications</h1>
                                <p class="mt-1" style="color: #424242; font-size: 15px;">Send stipend notifications to active scholars</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.stipends.bulk-notify') }}"
                                id="sendNotificationsBtn"
                                class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5"
                                style="background: linear-gradient(135deg, #4CAF50 0%, #43A047 100%); color: white;">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Active Scholars Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="px-6 py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: rgba(76, 175, 80, 0.1);">
                                    <i class="fas fa-user-graduate" style="color: #4CAF50;"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color: #424242;">Active Scholars</p>
                                <p class="text-2xl font-bold" style="color: #212121;" id="activeScholarsCount">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Notifications Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="px-6 py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: rgba(74, 144, 226, 0.1);">
                                    <i class="fas fa-bell" style="color: #4A90E2;"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color: #424242;">Total Notifications</p>
                                <p class="text-2xl font-bold" style="color: #212121;" id="totalNotificationsCount">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Last Notification Card -->
                <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="px-6 py-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: rgba(255, 152, 0, 0.1);">
                                    <i class="fas fa-clock" style="color: #FF9800;"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color: #424242;">Last Notification</p>
                                <p class="text-sm font-bold" style="color: #212121;" id="lastNotificationDate">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-5">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-lg" style="color: #4A90E2;"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2" style="color: #212121;">How Stipend Notifications Work</h3>
                            <div class="space-y-2 text-sm" style="color: #424242;">
                                <p>• <strong>Active Scholars:</strong> Only scholars with 'Active' status will receive notifications</p>
                                <p>• <strong>Notification Content:</strong> A simple message informing scholars that their stipend is now available</p>
                                <p>• <strong>Delivery:</strong> Notifications are sent through the in-system notification center only</p>
                                <p>• <strong>Bulk Sending:</strong> Use the "Send Notifications" button to notify all active scholars at once</p>
                                <p>• <strong>Frequency Limit:</strong> Notifications can only be sent once per month to prevent spam</p>
                            </div>
                            
                            <!-- Last Notification Info -->
                            <div id="lastNotificationInfo" class="mt-4 p-3 rounded-lg" style="background-color: #f8f9fa; border: 1px solid #e9ecef; display: none;">
                                <h4 class="text-sm font-semibold mb-2" style="color: #495057;">Last Notification Status</h4>
                                <div class="text-sm" style="color: #6c757d;">
                                    <p id="lastNotificationText"></p>
                                    <p id="nextAllowedText"></p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
        });

        function loadStats() {
            fetch('{{ route("admin.stipends.stats") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('activeScholarsCount').textContent = data.active_scholars || 0;
                    document.getElementById('lastNotificationDate').textContent = data.last_notification_date || 'Never';
                    
                    // Handle notification status
                    const sendBtn = document.getElementById('sendNotificationsBtn');
                    const lastNotificationInfo = document.getElementById('lastNotificationInfo');
                    const lastNotificationText = document.getElementById('lastNotificationText');
                    const nextAllowedText = document.getElementById('nextAllowedText');
                    
                    if (data.last_notification_date && data.last_notification_date !== 'Never') {
                        lastNotificationInfo.style.display = 'block';
                        lastNotificationText.textContent = `Last notification sent: ${data.last_notification_date}`;
                        
                        if (!data.can_send_notification) {
                            nextAllowedText.textContent = `Next notification allowed: ${data.next_allowed_date} (${data.days_remaining} days remaining)`;
                            sendBtn.style.background = 'linear-gradient(135deg, #9E9E9E 0%, #757575 100%)';
                            sendBtn.style.cursor = 'not-allowed';
                            sendBtn.onclick = function(e) {
                                e.preventDefault();
                                showValidationModal(`You can send the next notification on ${data.next_allowed_date} (${data.days_remaining} days remaining).`);
                                return false;
                            };
                        } else {
                            nextAllowedText.textContent = 'You can send a new notification now.';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                });
        }
        
        function showValidationModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('validationModal').style.display = 'flex';
        }
        
        function closeValidationModal() {
            document.getElementById('validationModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        document.getElementById('validationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeValidationModal();
            }
        });
    </script>
@endsection
