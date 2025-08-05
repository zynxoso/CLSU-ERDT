@extends('layouts.app')

@section('title', 'Process Monthly Stipends')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="max-w-4xl mx-auto py-6">
        <!-- Header -->
        <div class="rounded-xl shadow-sm border mb-6" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div class="flex items-start space-x-4 mb-4 lg:mb-0">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: #4A90E2;">
                                <i class="fas fa-calendar-check text-white text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold" style="color: #212121;">Process Monthly Stipends</h1>
                            <p class="mt-1" style="color: #424242; font-size: 15px;">Create stipend disbursements for all active scholars for a specific month</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.stipends.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg transition-colors text-xs sm:text-sm" style="background-color: #757575; color: white;" onmouseover="this.style.backgroundColor='#424242'" onmouseout="this.style.backgroundColor='#757575'">
                            <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Back to Stipends</span>
                            <span class="sm:hidden">Back</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Process Form -->
        <div class="rounded-xl shadow-sm border" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-5 border-b" style="background-color: #FAFAFA; border-color: #E0E0E0;">
                <h2 class="text-xl font-bold flex items-center" style="color: #212121;">
                    <i class="fas fa-money-bill-wave mr-3" style="color: #4A90E2;">
                    Monthly Stipend Processing
                </h2>
                <p class="text-sm mt-1" style="color: #424242;">Select the month and year to process stipend disbursements for all active scholars</p>
            </div>
            
            <div class="p-6">
                <!-- Information Card -->
                <div class="rounded-xl p-6 mb-6" style="background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%); border: 1px solid #90CAF9;">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: #4A90E2;">
                                <i class="fas fa-info-circle text-white text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2" style="color: #0D47A1;">How Monthly Processing Works</h3>
                            <ul class="space-y-2 text-sm" style="color: #1565C0;">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mr-2 mt-0.5" style="color: #4A90E2;">
                                    Creates disbursement records for all active scholars
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mr-2 mt-0.5" style="color: #4A90E2;">
                                    Automatically calculates stipend amounts based on degree level
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mr-2 mt-0.5" style="color: #4A90E2;"></i>
                                    Sets initial status as "Pending" for review
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mr-2 mt-0.5" style="color: #4A90E2;"></i>
                                    Skips scholars who already have disbursements for the selected month
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.stipends.process-monthly') }}" id="processMonthlyForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Month Selection -->
                        <div class="space-y-2">
                            <label for="month" class="block text-sm font-medium flex items-center" style="color: #424242;">
                                <i class="fas fa-calendar mr-2" style="color: #4A90E2;"></i>
                                Select Month & Year
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="month" 
                                   name="month" 
                                   id="month" 
                                   value="{{ old('month', date('Y-m')) }}"
                                   required 
                                   class="w-full border rounded-lg px-4 py-3 transition-colors" 
                                   style="border-color: #E0E0E0; background-color: #FAFAFA; color: #212121;" 
                                   onfocus="this.style.borderColor='#4A90E2'; this.style.backgroundColor='white'" 
                                   onblur="this.style.borderColor='#E0E0E0'; this.style.backgroundColor='#FAFAFA'">
                            <p class="text-xs mt-1" style="color: #757575;">
                                <i class="fas fa-info-circle mr-1"></i>
                                This will create disbursements for the selected month
                            </p>
                        </div>

                        <!-- Processing Type -->
                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-medium flex items-center" style="color: #424242;">
                                <i class="fas fa-cog mr-2" style="color: #4CAF50;"></i>
                                Processing Type
                            </label>
                            <select name="type" 
                                    id="type" 
                                    class="w-full border rounded-lg px-4 py-3 transition-colors" 
                                    style="border-color: #E0E0E0; background-color: #FAFAFA; color: #212121;" 
                                    onfocus="this.style.borderColor='#4CAF50'; this.style.backgroundColor='white'" 
                                    onblur="this.style.borderColor='#E0E0E0'; this.style.backgroundColor='#FAFAFA'">
                                <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly Stipend</option>
                                <option value="special" {{ old('type') == 'special' ? 'selected' : '' }}>Special Disbursement</option>
                                <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            </select>
                            <p class="text-xs mt-1" style="color: #757575;">
                                <i class="fas fa-info-circle mr-1"></i>
                                Select the type of disbursement to process
                            </p>
                        </div>
                    </div>

                    <!-- Warning Alert -->
                    <div class="rounded-xl p-4 mb-6" style="background-color: #FFF3E0; border: 1px solid #FFE0B2;">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-exclamation-triangle mt-1" style="color: #FF9800; font-size: 18px;"></i>
                            <div>
                                <div class="font-semibold mb-2" style="color: #E65100;">Important Notice</div>
                                <ul class="space-y-1 text-sm" style="color: #BF360C;">
                                    <li>• This action will process stipends for <strong>all active scholars</strong></li>
                                    <li>• Disbursement records will be created with "Pending" status</li>
                                    <li>• You can review and modify individual disbursements after processing</li>
                                    <li>• Scholars who already have disbursements for the selected month will be skipped</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t" style="border-color: #E0E0E0;">
                        <button type="submit" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-medium transition-colors" 
                                style="background-color: #4CAF50; color: white;"
            onmouseover="this.style.backgroundColor='#388E3C'"
            onmouseout="this.style.backgroundColor='#4CAF50'">
                            <i class="fas fa-play mr-2"></i>
                            Process Monthly Stipends
                        </button>
                        <a href="{{ route('admin.stipends.index') }}" 
                           class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-medium transition-colors border" 
                           style="background-color: #FAFAFA; color: #424242; border-color: #E0E0E0;" 
                           onmouseover="this.style.backgroundColor='#F5F5F5'" 
                           onmouseout="this.style.backgroundColor='#FAFAFA'">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Processing History -->
        <div class="rounded-xl shadow-sm border mt-6" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-4 border-b" style="background-color: #FAFAFA; border-color: #E0E0E0;">
                <h3 class="text-lg font-medium flex items-center" style="color: #212121;">
                    <i class="fas fa-history mr-2" style="color: #7B1FA2;"></i>
                    Recent Processing History
                </h3>
                <p class="text-sm mt-1" style="color: #424242;">Last 5 monthly processing activities</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recentProcessing ?? [] as $processing)
                    <div class="flex items-center justify-between p-4 rounded-lg border" style="background-color: #FAFAFA; border-color: #E0E0E0;">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #E8F5E8;">
                                <i class="fas fa-calendar-check" style="color: #4CAF50;"></i>
                            </div>
                            <div>
                                <div class="font-medium" style="color: #212121;">{{ $processing->period ?? 'N/A' }}</div>
                                <div class="text-sm" style="color: #757575;">{{ $processing->processed_count ?? 0 }} scholars processed</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium" style="color: #424242;">{{ $processing->created_at ? $processing->created_at->format('M d, Y') : 'N/A' }}</div>
                            <div class="text-xs" style="color: #757575;">{{ $processing->created_at ? $processing->created_at->format('h:i A') : 'N/A' }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #F5F5F5;">
                            <i class="fas fa-history text-2xl" style="color: #757575;"></i>
                        </div>
                        <p class="text-sm" style="color: #757575;">No processing history available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const form = document.getElementById('processMonthlyForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            const monthInput = document.getElementById('month');
            
            if (!monthInput.value) {
                alert('Please select a month/year.');
                return;
            }
            
            // Create confirmation dialog
            const selectedMonth = new Date(monthInput.value + '-01').toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long'
            });
            
            if (confirm(`Are you sure you want to process stipends for ${selectedMonth}?\n\nThis will create disbursements for all active scholars.`)) {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                submitButton.style.backgroundColor = '#757575';
                
                // Submit the form
                this.submit();
            }
        });
    }
});

// Show success message if processing was successful
@if(session('success'))
    $(document).ready(function() {
        // Create and show success toast
        const toast = $(`
            <div class="toast align-items-center text-white border-0 position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; background-color: #4CAF50; border-radius: 12px; box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center" style="padding: 16px;">
                        <i class="fas fa-check-circle me-3" style="font-size: 20px;"></i>
                        <div>
                            <div class="fw-bold mb-1">Success!</div>
                            <div style="font-size: 14px; opacity: 0.9;">{{ session('success') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        const bsToast = new bootstrap.Toast(toast[0], { delay: 5000 });
        bsToast.show();
        
        // Remove toast after it's hidden
        toast[0].addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    });
@endif

@if(session('error'))
    $(document).ready(function() {
        // Create and show error toast
        const toast = $(`
            <div class="toast align-items-center text-white border-0 position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; background-color: #D32F2F; border-radius: 12px; box-shadow: 0 8px 25px rgba(211, 47, 47, 0.3);" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center" style="padding: 16px;">
                        <i class="fas fa-exclamation-circle me-3" style="font-size: 20px;"></i>
                        <div>
                            <div class="fw-bold mb-1">Error!</div>
                            <div style="font-size: 14px; opacity: 0.9;">{{ session('error') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        const bsToast = new bootstrap.Toast(toast[0], { delay: 5000 });
        bsToast.show();
        
        
        // Remove toast after it's hidden
        toast[0].addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    });
@endif
</script>
@endpush