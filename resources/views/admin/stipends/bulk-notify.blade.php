@extends('layouts.app')

@section('title', 'Send Stipend Notification')

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="max-w-4xl mx-auto px-6 py-5">
        <div class="space-y-6">
            <!-- Simple Header Card -->
            <div class="bg-white rounded-xl shadow-lg border" style="border-color: #E0E0E0;">
                <div class="px-6 py-5 border-b" style="background: linear-gradient(135deg, #4A90E2 0%, #1565C0 100%); border-color: #E0E0E0;" class="rounded-t-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background-color: rgba(255,255,255,0.2);">
                                <i class="fas fa-money-bill-wave text-xl" style="color: white;"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Stipend Available Notification</h1>
                                <p class="text-blue-100 mt-1">Notify scholars that their stipends are now available</p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('admin.stipends.index') }}" 
                               class="px-4 py-2 rounded-lg inline-flex items-center justify-center text-sm font-medium transition-all duration-200 hover:shadow-md"
                               style="background-color: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Stipends
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Send Stipend Available Notification -->
            <div class="bg-white rounded-xl shadow-lg border" style="border-color: #E0E0E0;">
                <div class="px-6 py-4 border-b" style="background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); border-color: #E0E0E0;" class="rounded-t-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3" style="background-color: rgba(255,255,255,0.2);">
                            <i class="fas fa-bell text-lg" style="color: white;"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Send Stipend Available Notification</h2>
                    </div>
                </div>
                <div class="p-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Notification Details</h3>
                                <p class="text-sm mt-1" style="color: #4A90E2;">
                                    This will send a notification to all active scholars informing them that their stipends are now available for collection or disbursement.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Preview -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Notification Message:</h3>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium">Subject: Your Stipend is Now Available</p>
                            <p class="mt-2">Dear Scholar,</p>
                            <p class="mt-1">We are pleased to inform you that your stipend is now available. Please contact the ERDT office for collection details.</p>
                            <p class="mt-2">Best regards,<br>CLSU-ERDT Team</p>
                        </div>
                    </div>

                    <!-- Send Button -->
                     <div class="pt-4">
                         <button type="button" id="sendNotificationBtn" onclick="sendStipendNotification()"
                                 class="w-full px-6 py-3 rounded-xl font-semibold text-base transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5" 
                                 style="background: linear-gradient(135deg, #4CAF50 0%, #43A047 100%); color: white;">
                             <i class="fas fa-paper-plane mr-2"></i> Send Notification to All Scholars
                         </button>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Success</h3>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">Notifications sent successfully!</p>
            </div>
            <div class="flex justify-end">
                <button onclick="closeSuccessModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Error</h3>
                </div>
            </div>
            <div class="mb-4">
                <p id="errorMessage" class="text-sm text-gray-600"></p>
            </div>
            <div class="flex justify-end">
                <button onclick="closeErrorModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Send stipend notification function
function sendStipendNotification() {
    if (!confirm('Are you sure you want to send the stipend available notification to all active scholars?')) {
        return;
    }
    
    const btn = document.getElementById('sendNotificationBtn');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
    
    // Send actual AJAX request
    fetch('{{ route("admin.stipends.bulk-notify.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Failed to send notifications');
            });
        }
        return response.json();
    })
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        if (data.success) {
            showSuccessModal();
        } else {
            showErrorModal('Failed to send notifications: ' + data.message);
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        showErrorModal('Error: ' + error.message);
    });
}

// Setup event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('sendNotificationBtn').addEventListener('click', sendStipendNotification);
});

// Modal functions
function showSuccessModal() {
    document.getElementById('successModal').style.display = 'flex';
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
    window.location.href = '{{ route("admin.stipends.index") }}';
}

function showErrorModal(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorModal').style.display = 'flex';
}

function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
}

// Close modals when clicking outside
document.getElementById('successModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuccessModal();
    }
});

document.getElementById('errorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeErrorModal();
    }
});
</script>
@endsection