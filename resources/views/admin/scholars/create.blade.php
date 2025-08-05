@extends('layouts.app')

@section('title', 'Add New Scholar')

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="container mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #424242;">Add New Scholar</h1>
                    <p class="mt-2" style="color: #757575;">Create a new scholar profile with complete information.</p>
                </div>
                <a href="{{ route('admin.scholars.index') }}" 
                   class="inline-flex items-center px-4 py-2 border text-sm font-medium bg-white hover:opacity-90 focus:outline-none focus:ring-2 rounded-md" style="border-color: #E0E0E0; color: #424242; focus:ring-color: #4CAF50;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Scholars
                </a>
            </div>
        </div>

        <!-- Multi-Step Form Component -->
        <x-multi-step-form 
            title="Scholar Registration"
            form-action="{{ route('admin.scholars.store') }}"
            form-method="POST"
            submit-button-text="Create Scholar"
            cancel-url="{{ route('admin.scholars.index') }}"
            :steps="[
                ['title' => 'Basic Info', 'fields' => ['first_name', 'last_name', 'email']],
                ['title' => 'Address', 'fields' => ['province']],
                ['title' => 'Academic', 'fields' => ['intended_university', 'department']],
                ['title' => 'Scholarship', 'fields' => []],
                ['title' => 'Review', 'fields' => []]
            ]">
            
            <!-- Login Information Notice -->
            <div class="mb-6 p-4 rounded-lg border" style="background-color: rgba(76, 175, 80, 0.1); border-color: #4CAF50;">
                <h3 class="text-lg font-semibold mb-2" style="color: #4CAF50;">
                    <i class="fas fa-info-circle mr-2" style="color: #4CAF50;"></i> Scholar Login Information
                </h3>
                <p class="text-sm mb-3" style="color: #4CAF50;">When you create a scholar account, the system will generate the following login credentials:</p>

                <div class="flex flex-col md:flex-row md:items-center p-3 bg-white rounded border" style="border-color: #4CAF50;">
                    <div class="font-medium md:w-1/4" style="color: #4CAF50;">Default Password:</div>
                    <div class="md:w-3/4 flex items-center">
                        <code class="px-2 py-1 rounded font-mono" style="background-color: #F5F5F5; color: #424242;">CLSU-scholar123</code>
                        <button type="button" class="ml-2 px-2 py-1 rounded text-sm" style="background-color: #4CAF50; color: white;" onclick="copyPassword()">
                            <i class="fas fa-copy mr-1"></i> Copy
                        </button>
                    </div>
                </div>

                <p class="text-sm mt-3" style="color: #4CAF50;">
                    <i class="fas fa-exclamation-triangle mr-1" style="color: #4CAF50;"></i> Important: The scholar will receive instructions to set their password after account creation.
                </p>
            </div>

            <!-- Step 1: Basic Information -->
            <x-form-section title="Basic Information" step="0" 
                icon='<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>'
                description="Enter the scholar's personal information">
                
                @include('partials.personal-info-fields', ['scholarProfile' => null, 'includePhoto' => false])
            </x-form-section>

            <!-- Step 2: Address Information -->
            <x-form-section title="Address Information" step="1"
                icon='<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>'
                description="Enter the scholar's address details">
                
                @include('partials.address-info-fields', ['scholarProfile' => null])
            </x-form-section>

            <!-- Step 3: Academic Information -->
            <x-form-section title="Academic Information" step="2"
                icon='<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>'
                description="Enter academic and educational details">
                
                @include('partials.academic-info-fields', [
                    'scholarProfile' => null, 
                    'universities' => ['CLSU' => 'Central Luzon State University'], 
                    'departments' => ['Graduate School' => 'Graduate School'],
                    'isAdmin' => true
                ])
            </x-form-section>

            <!-- Step 4: Scholarship Details -->
            <x-form-section title="Scholarship Details" step="3"
                icon='<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>'
                description="Enter scholarship and program details">
                
                @include('partials.scholarship-details-fields', ['scholarProfile' => null, 'isAdmin' => true])
            </x-form-section>

            <!-- Step 5: Review & Submit -->
            <x-form-section title="Review & Submit" step="4"
                icon='<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>'
                description="Review all information before creating the scholar profile">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-review-section title="Personal Information" :data="[]">
                        <ul class="space-y-2" style="color: #757575;">
                            <li><span class="font-semibold">Name:</span> <span id="review-name">-</span></li>
                            <li><span class="font-semibold">Email:</span> <span id="review-email">-</span></li>
                            <li><span class="font-semibold">Phone:</span> <span id="review-phone">-</span></li>
                            <li><span class="font-semibold">Birth Date:</span> <span id="review-birth-date">-</span></li>
                            <li><span class="font-semibold">Gender:</span> <span id="review-gender">-</span></li>
                        </ul>
                    </x-review-section>
                    
                    <x-review-section title="Address Information" :data="[]">
                        <ul class="space-y-2" style="color: #757575;">
                            <li><span class="font-semibold">Address:</span> <span id="review-address">-</span></li>

                            <li><span class="font-semibold">Province:</span> <span id="review-province">-</span></li>
                            <li><span class="font-semibold">Country:</span> <span id="review-country">-</span></li>
                        </ul>
                    </x-review-section>
                    
                    <x-review-section title="Academic Information" :data="[]">
                        <ul class="space-y-2" style="color: #757575;">
                            <li><span class="font-semibold">University:</span> <span id="review-university">-</span></li>
                            <li><span class="font-semibold">Department:</span> <span id="review-department">-</span></li>
                            <li><span class="font-semibold">Major:</span> <span id="review-major">-</span></li>
                        </ul>
                    </x-review-section>
                    
                    <x-review-section title="Scholarship Details" :data="[]">
                        <ul class="space-y-2" style="color: #757575;">
                            <li><span class="font-semibold">Status:</span> <span id="review-status">-</span></li>
                            <li><span class="font-semibold">Start Date:</span> <span id="review-start-date">-</span></li>
                        </ul>
                    </x-review-section>
                </div>
                
                <div class="flex items-center gap-2 text-sm mt-6" style="color: #757575;">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>The scholar will be created with the default password and can change it after first login.</span>
                </div>
            </x-form-section>
        </x-multi-step-form>
    </div>
</div>

<script>
// Copy password function
function copyPassword() {
    navigator.clipboard.writeText('CLSU-scholar123').then(function() {
        // Show success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    });
}

// Update review section when form values change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('multi-step-scholar-form');
    if (!form) return;
    
    function updateReview() {
        // Personal Information
        const firstName = document.querySelector('[name="first_name"]')?.value || '';
        const middleName = document.querySelector('[name="middle_name"]')?.value || '';
        const lastName = document.querySelector('[name="last_name"]')?.value || '';
        const fullName = [firstName, middleName, lastName].filter(n => n).join(' ');
        
        document.getElementById('review-name').textContent = fullName || '-';
        document.getElementById('review-email').textContent = document.querySelector('[name="email"]')?.value || '-';
        document.getElementById('review-phone').textContent = document.querySelector('[name="contact_number"]')?.value || '-';
        document.getElementById('review-birth-date').textContent = document.querySelector('[name="birth_date"]')?.value || '-';
        document.getElementById('review-gender').textContent = document.querySelector('[name="gender"]')?.selectedOptions[0]?.text || '-';
        
        // Address Information
        const street = document.querySelector('[name="street"]')?.value || '';
        const village = document.querySelector('[name="village"]')?.value || '';
        const fullAddress = [street, village].filter(a => a).join(', ');
        document.getElementById('review-address').textContent = fullAddress || '-';

        document.getElementById('review-province').textContent = document.querySelector('[name="province"]')?.value || '-';
        document.getElementById('review-country').textContent = document.querySelector('[name="country"]')?.value || '-';
        
        // Academic Information
        document.getElementById('review-university').textContent = document.querySelector('[name="intended_university"]')?.value || '-';
        document.getElementById('review-department').textContent = document.querySelector('[name="department"]')?.value || '-';
        document.getElementById('review-major').textContent = document.querySelector('[name="major"]')?.value || '-';
        
        // Scholarship Details
        document.getElementById('review-status').textContent = document.querySelector('[name="status"]')?.selectedOptions[0]?.text || '-';
        document.getElementById('review-start-date').textContent = document.querySelector('[name="start_date"]')?.value || '-';
    }
    
    // Update review when inputs change
    form.addEventListener('input', updateReview);
    form.addEventListener('change', updateReview);
    
    // Initial update
    updateReview();
});
</script>
@endsection
