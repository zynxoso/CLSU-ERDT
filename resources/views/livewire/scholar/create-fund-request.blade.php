<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Create New Fund Request</h1>
                    <p class="text-gray-600 mt-1">Submit your fund request with comprehensive validation</p>
                </div>
                <a href="{{ route('scholar.fund-requests.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $currentStep >= 1 ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        1
                    </div>
                    <span class="ml-2 text-sm {{ $currentStep >= 1 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Request Details</span>
                </div>
                <div class="w-16 h-1 {{ $currentStep >= 2 ? 'bg-green-500' : 'bg-gray-300' }} rounded"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $currentStep >= 2 ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-sm {{ $currentStep >= 2 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Documents</span>
                </div>
                <div class="w-16 h-1 {{ $currentStep >= 3 ? 'bg-green-500' : 'bg-gray-300' }} rounded"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $currentStep >= 3 ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        3
                    </div>
                    <span class="ml-2 text-sm {{ $currentStep >= 3 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Review & Submit</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <!-- Important Information Box -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Important Information
                </h3>
                <div class="space-y-2 text-blue-700">
                    <p>• You can only submit <strong>one request type at a time</strong></p>
                    <p>• You cannot have multiple active requests of the same type</p>
                    <p>• Each request type has specific amount limits based on your program level</p>
                    <p>• All requests require administrator approval</p>
                </div>
            </div>

            <!-- Step 1: Request Details -->
            @if($currentStep === 1)
            <div class="space-y-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-lg font-semibold">1</div>
                    <h2 class="text-xl font-semibold text-gray-800 ml-3">Request Details</h2>
                </div>

                <!-- Request Type Selection -->
                <div>
                    <label for="request_type_id" class="block text-base font-medium text-gray-700 mb-2">
                        Request Type <span class="text-red-500">*</span>
                        <span class="ml-2 text-gray-400 cursor-help" title="Select exactly one request type per submission">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    <select wire:model.live="request_type_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 
                                   {{ isset($validationErrors['request_type_id']) ? 'border-red-500 bg-red-50' : (isset($validationWarnings['request_type_id']) ? 'border-yellow-500 bg-yellow-50' : '') }}">
                        <option value="">Select Request Type</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}" 
                                    {{ in_array($type->id, $existingRequestTypes) ? 'disabled' : '' }}>
                                {{ $type->name }}
                                {{ in_array($type->id, $existingRequestTypes) ? ' (Already Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    
                    @if(isset($validationErrors['request_type_id']))
                        <div class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            {{ $validationErrors['request_type_id'] }}
                        </div>
                    @endif
                    
                    @if(isset($validationWarnings['request_type_id']))
                        <div class="text-yellow-600 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ $validationWarnings['request_type_id'] }}
                        </div>
                    @endif
                </div>

                <!-- Amount Input -->
                <div>
                    <label for="amount" class="block text-base font-medium text-gray-700 mb-2">
                        Amount (₱) <span class="text-red-500">*</span>
                        <span class="ml-2 text-gray-400 cursor-help" title="Enter the amount you are requesting">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <input type="text" wire:model.live.debounce.500ms="amount" 
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200
                                      {{ isset($validationErrors['amount']) ? 'border-red-500 bg-red-50' : (isset($validationWarnings['amount']) ? 'border-yellow-500 bg-yellow-50' : '') }}"
                               placeholder="10,000.00">
                    </div>
                    
                    @if($selectedRequestType)
                        <div class="text-sm text-blue-600 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Maximum for {{ $selectedRequestType->name }}: 
                                @if(auth()->user()->scholarProfile)
                                    ₱{{ number_format($selectedRequestType->getMaxAmountForDegree(auth()->user()->scholarProfile->intended_degree ?? 'Masters'), 2) }}
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    @if(isset($validationErrors['amount']))
                        <div class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            {{ $validationErrors['amount'] }}
                        </div>
                    @endif
                    
                    @if(isset($validationWarnings['amount']))
                        <div class="text-yellow-600 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ $validationWarnings['amount'] }}
                        </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button type="button" wire:click="nextStep" 
                            class="px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base
                                   {{ !$request_type_id || !$amount || isset($validationErrors['request_type_id']) || isset($validationErrors['amount']) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$request_type_id || !$amount || isset($validationErrors['request_type_id']) || isset($validationErrors['amount']) ? 'disabled' : '' }}>
                        Next: Documents <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Step 2: Documents -->
            @if($currentStep === 2)
            <div class="space-y-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-lg font-semibold">2</div>
                    <h2 class="text-xl font-semibold text-gray-800 ml-3">Supporting Documents</h2>
                </div>

                <!-- Document Upload -->
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Supporting Document <span class="text-gray-500">(Optional)</span>
                        <span class="ml-2 text-gray-400 cursor-help" title="Upload relevant PDF documents up to 5MB">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    </label>
                    
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition-colors duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-green-500 mb-2"></i>
                                <p class="mb-2 text-sm text-green-700">
                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-green-600">PDF only (Max. 5MB)</p>
                            </div>
                            <input type="file" wire:model="document" class="hidden" accept=".pdf">
                        </label>
                    </div>
                    
                    @if($document)
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-green-600 mr-2"></i>
                                <span class="text-green-800 font-medium">{{ $document->getClientOriginalName() }}</span>
                                <span class="text-green-600 text-sm ml-2">({{ number_format($document->getSize() / 1024, 1) }} KB)</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(isset($validationErrors['document']))
                        <div class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            {{ $validationErrors['document'] }}
                        </div>
                    @endif
                </div>

                <div class="flex justify-between">
                    <button type="button" wire:click="previousStep" 
                            class="px-6 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                        <i class="fas fa-arrow-left mr-2"></i> Previous
                    </button>
                    <button type="button" wire:click="nextStep" 
                            class="px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                        Next: Review <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Step 3: Review & Submit -->
            @if($currentStep === 3)
            <div class="space-y-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-lg font-semibold">3</div>
                    <h2 class="text-xl font-semibold text-gray-800 ml-3">Review & Submit</h2>
                </div>

                <!-- Review Summary -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Your Request</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-base font-medium text-gray-600">Request Type:</span>
                            <span class="text-base text-gray-800 font-semibold">
                                {{ $selectedRequestType ? $selectedRequestType->name : 'Not selected' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-base font-medium text-gray-600">Amount:</span>
                            <span class="text-base text-gray-800 font-semibold">
                                ₱{{ $amount ? number_format(str_replace([',', '₱', ' '], '', $amount), 2) : '0.00' }}
                            </span>
                        </div>
                        <div class="py-2">
                            <span class="text-base font-medium text-gray-600">Supporting Document:</span>
                            <div class="mt-2">
                                @if($document)
                                    <div class="flex items-center text-base text-gray-800">
                                        <i class="fas fa-file-pdf text-green-500 mr-2"></i>
                                        <span>{{ $document->getClientOriginalName() }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 italic">No document uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Validation Warning -->
                @if(!empty($validationErrors))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="text-red-800 font-semibold mb-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Please correct the following errors:
                        </h4>
                        <ul class="text-red-700 space-y-1">
                            @foreach($validationErrors as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-between">
                    <button type="button" wire:click="previousStep" 
                            class="px-6 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                        <i class="fas fa-arrow-left mr-2"></i> Previous
                    </button>
                    <button type="button" wire:click="submit" 
                            class="px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center text-base
                                   {{ !empty($validationErrors) || $isValidating ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !empty($validationErrors) || $isValidating ? 'disabled' : '' }}>
                        @if($isValidating)
                            <i class="fas fa-spinner fa-spin mr-2"></i> Validating...
                        @else
                            <i class="fas fa-paper-plane mr-2"></i> Submit Request
                        @endif
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Validation Modal -->
    @if($showValidationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-xl border border-gray-200">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 bg-red-500">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ $validationModalTitle }}
                </h3>
                <button type="button" wire:click="closeValidationModal" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="px-6 py-4">
                <div class="text-gray-800 whitespace-pre-line">{{ $validationModalContent }}</div>
            </div>
            <div class="flex justify-end gap-2 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
                <button type="button" wire:click="closeValidationModal" 
                        class="px-6 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                    <i class="fas fa-check mr-2"></i> OK
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Loading Overlay -->
    @if($isValidating)
    <div class="fixed inset-0 z-40 bg-black/25 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <i class="fas fa-spinner fa-spin text-green-500 text-xl"></i>
                <span class="text-gray-800 font-medium">Validating your request...</span>
            </div>
        </div>
    </div>
    @endif
</div>