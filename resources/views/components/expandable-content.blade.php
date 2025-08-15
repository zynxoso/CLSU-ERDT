@props([
    'title' => '',
    'expanded' => false,
    'variant' => 'default', // default, card, minimal
    'size' => 'md' // sm, md, lg
])

@php
$sizeClasses = [
    'sm' => 'px-4 py-4 sm:px-5 sm:py-5',
    'md' => 'px-6 py-6 sm:px-8 sm:py-8', 
    'lg' => 'px-8 py-8 sm:px-10 sm:py-10'
];

$variantClasses = [
    'default' => 'bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md',
    'card' => 'bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-lg',
    'minimal' => 'border-b border-gray-200 last:border-b-0'
];

$paddingClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$variantClass = $variantClasses[$variant] ?? $variantClasses['default'];
@endphp

<div class="{{ $variantClass }} transition-all duration-300" x-data="{ expanded: {{ $expanded ? 'true' : 'false' }} }">
    <button @click="expanded = !expanded"
            class="w-full {{ $paddingClass }} text-left focus:outline-none focus:ring-4 focus:ring-green-200 transition-all duration-300 min-h-[60px] touch-manipulation {{ $variant === 'minimal' ? '' : 'rounded-lg' }}"
            :class="expanded ? 'bg-green-50' : 'hover:bg-gray-50 active:bg-gray-100'"
            role="button"
            :aria-expanded="expanded"
            aria-controls="expandable-content"
            tabindex="0">
        <div class="flex items-center justify-between">
            <div class="flex-1 pr-4">
                @if($title)
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 leading-relaxed">{{ $title }}</h3>
                @else
                    {{ $trigger ?? '' }}
                @endif
            </div>
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600 transform transition-transform duration-300"
                     :class="expanded ? 'rotate-180' : 'rotate-0'"
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </button>
    
    <div x-show="expanded"
         x-collapse
         x-cloak
         id="expandable-content"
         class="{{ $variant === 'minimal' ? 'pb-6' : $paddingClass . ' pt-0' }}">
        <div class="{{ $variant === 'minimal' ? '' : 'pt-4 border-t border-gray-100' }}">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
