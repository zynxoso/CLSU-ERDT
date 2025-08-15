@props([
    'currentPhoto' => null,
    'name' => 'profile_photo',
    'required' => false,
    'maxSize' => 2048, // KB
    'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif']
])

<div x-data="profilePhotoUpload()" class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Profile Photo
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <!-- Photo Preview Area -->
    <div class="flex items-start space-x-4">
        <!-- Current/Preview Photo -->
        <div class="relative">
            <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden"
                 :class="{
                     'border-green-500 bg-green-50': dragOver,
                     'border-gray-300': !dragOver
                 }"
                 @dragover.prevent="dragOver = true"
                 @dragleave.prevent="dragOver = false"
                 @drop.prevent="handleDrop($event)">
                
                <template x-if="hasDisplayablePhoto">
                    <img :src="displayPhotoUrl" 
                         alt="Profile photo preview" 
                         class="w-full h-full object-cover rounded-lg">
                </template>
                
                <template x-if="!hasDisplayablePhoto">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="text-xs text-gray-500">No photo</p>
                    </div>
                </template>
            </div>
            
            <!-- Remove Photo Button -->
            <button type="button" 
                    x-show="hasDisplayablePhoto" 
                    @click="removeActivePhoto()"
                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Upload Controls -->
        <div class="flex-1">
            <div class="space-y-3">
                <!-- File Input (Hidden) -->
                <input type="file" 
                       x-ref="photoInput"
                       name="{{ $name }}"
                       accept="{{ implode(',', $allowedTypes) }}"
                       @change="updatePreview($event)"
                       class="hidden"
                       {{ $required ? 'required' : '' }}>
                
                <!-- Upload Button -->
                <button type="button" 
                        @click="triggerFileInput()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Choose Photo
                </button>
                
                <!-- Drag & Drop Instructions -->
                <p class="text-xs text-gray-500">
                    Or drag and drop a photo here
                </p>
                
                <!-- File Requirements -->
                <div class="text-xs text-gray-500">
                    <p>Requirements:</p>
                    <ul class="list-disc list-inside ml-2 space-y-1">
                        <li>Maximum size: {{ number_format($maxSize / 1024, 1) }}MB</li>
                        <li>Formats: JPG, PNG, GIF</li>
                        <li>Recommended: Square aspect ratio</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Message -->
    <div x-show="errorMessage" 
         x-transition
         class="mt-2 p-2 bg-red-50 border border-red-200 rounded-md">
        <p class="text-sm text-red-600" x-text="errorMessage"></p>
    </div>
    
    <!-- Hidden input for remove flag -->
    <input type="hidden" name="remove_photo" x-model="removePhotoFlag">
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profilePhotoUpload', () => ({
            initialPhoto: '{{ $currentPhoto ? asset('images/' . $currentPhoto) : '' }}',
            photoPreview: null,
            removePhotoFlag: false,
            dragOver: false,
            errorMessage: '',
            maxSizeKB: {{ $maxSize }},
            allowedTypes: @json($allowedTypes),
            
            init() {
                this.photoPreview = this.initialPhoto;
            },
            
            get displayPhotoUrl() {
                return this.photoPreview;
            },
            
            get hasDisplayablePhoto() {
                return !!this.photoPreview;
            },
            
            validateFile(file) {
                this.errorMessage = '';
                
                // Check file size
                if (file.size > this.maxSizeKB * 1024) {
                    this.errorMessage = `File is too large. Maximum size is ${(this.maxSizeKB / 1024).toFixed(1)}MB.`;
                    return false;
                }
                
                // Check file type
                if (!this.allowedTypes.includes(file.type)) {
                    this.errorMessage = 'Invalid file type. Only JPG, PNG, or GIF are allowed.';
                    return false;
                }
                
                return true;
            },
            
            updatePreview(event) {
                const file = event.target.files[0];
                if (file && this.validateFile(file)) {
                    this.processFile(file);
                } else if (file) {
                    event.target.value = null;
                }
            },
            
            processFile(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                    this.removePhotoFlag = false;
                    this.errorMessage = '';
                };
                reader.readAsDataURL(file);
            },
            
            handleDrop(event) {
                this.dragOver = false;
                const files = event.dataTransfer.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    if (this.validateFile(file)) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.photoInput.files = dataTransfer.files;
                        this.processFile(file);
                    }
                }
            },
            
            triggerFileInput() {
                this.$refs.photoInput.click();
            },
            
            removeActivePhoto() {
                this.photoPreview = '';
                this.$refs.photoInput.value = null;
                this.removePhotoFlag = true;
                this.errorMessage = '';
            }
        }));
    });
</script>
