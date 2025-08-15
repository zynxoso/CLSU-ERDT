@props([
    'steps' => [],
    'currentStep' => 0,
    'formAction' => '',
    'formMethod' => 'POST',
    'submitButtonText' => 'Submit',
    'cancelUrl' => null
])

<div x-data="multiStepForm()" class="bg-white shadow-lg rounded-lg overflow-hidden">
    <!-- Progress Bar -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Multi-Step Form' }}</h2>
            <span class="text-sm text-gray-500" x-text="`Step ${currentStep + 1} of ${steps.length}`"></span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                 :style="`width: ${((currentStep + 1) / steps.length) * 100}%`"></div>
        </div>
        
        <div class="flex justify-between mt-2">
            <template x-for="(step, index) in steps" :key="index">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors duration-200"
                         :class="{
                             'bg-green-600 text-white': index <= currentStep,
                             'bg-gray-300 text-gray-600': index > currentStep
                         }">
                        <span x-text="index + 1"></span>
                    </div>
                    <span class="text-xs mt-1 text-center max-w-20" 
                          :class="{
                              'text-green-600 font-medium': index <= currentStep,
                              'text-gray-500': index > currentStep
                          }"
                          x-text="step.title"></span>
                </div>
            </template>
        </div>
    </div>

    <!-- Form Content -->
    <form action="{{ $formAction }}" method="{{ $formMethod }}" enctype="multipart/form-data">
        @csrf
        @if($formMethod !== 'POST')
            @method($formMethod)
        @endif
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4 m-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4 m-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Step Content -->
        <div class="p-6">
            {{ $slot }}
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50">
            <button type="button" 
                    @click="previousStep()" 
                    x-show="currentStep > 0" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Previous
            </button>
            
            <div class="flex gap-3">
                @if($cancelUrl)
                    <a href="{{ $cancelUrl }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-md">
                        Cancel
                    </a>
                @endif
                
                <button type="button" 
                        @click="nextStep()" 
                        x-show="currentStep < steps.length - 1" 
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-md">
                    Next
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <button type="submit" 
                        x-show="currentStep === steps.length - 1" 
                        class="inline-flex items-center px-8 py-2 border border-transparent text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-md">
                    {{ $submitButtonText }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('multiStepForm', () => ({
            currentStep: {{ $currentStep }},
            steps: @json($steps),
            
            nextStep() {
                if (this.validateCurrentStep()) {
                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                    }
                }
            },
            
            previousStep() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },
            
            validateCurrentStep() {
                const currentStepFields = this.steps[this.currentStep].fields || [];
                let isValid = true;
                
                currentStepFields.forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field && field.hasAttribute('required') && !field.value.trim()) {
                        field.focus();
                        isValid = false;
                        return false;
                    }
                });
                
                return isValid;
            }
        }));
    });
</script>
