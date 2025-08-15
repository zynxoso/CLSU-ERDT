@props([
    'type' => 'info',
    'position' => 'bottom-right',
    'autoClose' => true,
    'autoCloseDelay' => 5000, 
    'id' => 'toast-' . uniqid(),
])

@php
    $typeStyles = [
        'info' => 'bg-blue-500 text-white',
        'success' => 'bg-green-500 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'error' => 'bg-red-500 text-white',
        'default' => 'bg-gray-800 text-white',
        'light' => 'bg-white text-gray-800 border border-gray-200',
    ];
    
    $positionClasses = [
        'top-right' => 'top-4 right-4',
        'top-center' => 'top-4 left-1/2 transform -translate-x-1/2',
        'top-left' => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-center' => 'bottom-4 left-1/2 transform -translate-x-1/2',
        'bottom-left' => 'bottom-4 left-4',
    ];
    
    $icons = [
        'info' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
        'success' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
        'warning' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
        'error' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
        'default' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
        'light' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
    ];
@endphp

<div
    x-data="{
        show: false,
        timeout: null,
        init() {
            this.show = true;
            if ({{ $autoClose ? 'true' : 'false' }}) {
                this.timeout = setTimeout(() => {
                    this.show = false;
                }, {{ $autoCloseDelay }});
            }
        },
        close() {
            this.show = false;
            if (this.timeout) clearTimeout(this.timeout);
        }
    }"
    x-show="show"
    x-init="init()"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.away="close()"
    id="{{ $id }}"
    class="fixed z-50 flex items-center w-full max-w-xs p-4 shadow-lg rounded-lg {{ $typeStyles[$type] }} {{ $positionClasses[$position] }} {{ $attributes->get('class') }}"
    role="alert"
>
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ $type === 'light' ? 'text-blue-500' : 'text-white' }} rounded-lg">
        {!! $icons[$type] !!}
    </div>
    <div class="ml-3 text-sm font-normal">
        {{ $slot }}
    </div>
    <button 
        type="button" 
        class="ml-auto -mx-1.5 -my-1.5 {{ $type === 'light' ? 'bg-white text-gray-400' : 'text-white' }} rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-opacity-20 hover:bg-gray-800 inline-flex h-8 w-8 items-center justify-center" 
        aria-label="Close"
        @click="close()"
    >
        <span class="sr-only">Close</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>

<script>
    function showToast(message, type = 'default', position = 'bottom-right', autoClose = true, autoCloseDelay = 5000) {
        // Create a unique ID for the toast
        const id = 'toast-' + Math.random().toString(36).substr(2, 9);
        
        // Create the toast element
        const toast = document.createElement('div');
        
        const typeStyles = {
            'info': 'bg-blue-500 text-white',
            'success': 'bg-green-500 text-white',
            'warning': 'bg-yellow-500 text-white',
            'error': 'bg-red-500 text-white',
            'default': 'bg-gray-800 text-white',
            'light': 'bg-white text-gray-800 border border-gray-200',
        };
        
        const positionClasses = {
            'top-right': 'top-4 right-4',
            'top-center': 'top-4 left-1/2 transform -translate-x-1/2',
            'top-left': 'top-4 left-4',
            'bottom-right': 'bottom-4 right-4',
            'bottom-center': 'bottom-4 left-1/2 transform -translate-x-1/2',
            'bottom-left': 'bottom-4 left-4',
        };
        
        const icons = {
            'info': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
            'success': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            'warning': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            'error': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            'default': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
            'light': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
        };
        
        // Set the toast innerHTML
        toast.innerHTML = `
            <div 
                id="${id}" 
                class="fixed z-50 flex items-center w-full max-w-xs p-4 shadow-lg rounded-lg ${typeStyles[type]} ${positionClasses[position]}"
                role="alert"
            >
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
                    ${icons[type]}
                </div>
                <div class="ml-3 text-sm font-normal">
                    ${message}
                </div>
                <button 
                    type="button" 
                    class="ml-auto -mx-1.5 -my-1.5 ${type === 'light' ? 'bg-white text-gray-400' : 'text-white'} rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-opacity-20 hover:bg-gray-800 inline-flex h-8 w-8 items-center justify-center" 
                    aria-label="Close"
                    onclick="closeToast('${id}')"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add the toast to the body
        document.body.appendChild(toast.firstElementChild);
        
        // Add entry animation
        const toastElement = document.getElementById(id);
        toastElement.style.opacity = '0';
        toastElement.style.transform = 'translateY(20px)';
        toastElement.style.transition = 'all 0.3s ease-out';
        
        setTimeout(() => {
            toastElement.style.opacity = '1';
            toastElement.style.transform = 'translateY(0)';
        }, 10);
        
        // Auto close the toast if enabled
        if (autoClose) {
            setTimeout(() => {
                closeToast(id);
            }, autoCloseDelay);
        }
        
        return id;
    }
    
    function closeToast(id) {
        const toast = document.getElementById(id);
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
</script> 
