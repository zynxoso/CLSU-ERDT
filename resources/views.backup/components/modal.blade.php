@props([
    'id' => 'modal', 
    'maxWidth' => '2xl', 
    'closeButton' => true
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div
    x-data="{ open: false }"
    x-show="open"
    x-on:open-modal.window="$event.detail.id === '{{ $id }}' ? open = true : null"
    x-on:close-modal.window="$event.detail.id === '{{ $id }}' ? open = false : null"
    x-on:keydown.escape.window="open = false"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden"
    style="display: none;"
>
    <div 
        x-show="open" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 transform transition-all"
        @click="open = false"
    ></div>

    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full {{ $maxWidth }} p-4 mx-auto my-8"
        @click.outside="open = false"
    >
        <div class="relative overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
            @if($closeButton)
            <button
                type="button"
                class="absolute top-3 right-3 inline-flex items-center justify-center rounded-md p-1.5 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transform transition-all duration-200 hover:rotate-90"
                @click="open = false"
            >
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            @endif

            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    {{ $title ?? '' }}
                </div>

                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ $slot }}
                </div>
            </div>

            @if (isset($footer))
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right rounded-b-lg">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: id } }));
    }
    
    function closeModal(id) {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: id } }));
    }
</script> 