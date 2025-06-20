@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'fullWidth' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'rounded' => false,
    'animate' => false
])

@php
    // Variant styles
    $variantStyles = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary' => 'bg-gray-500 hover:bg-gray-600 text-white focus:ring-gray-400',
        'success' => 'bg-green-500 hover:bg-green-600 text-white focus:ring-green-400',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white focus:ring-red-400',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-400',
        'info' => 'bg-sky-500 hover:bg-sky-600 text-white focus:ring-sky-400',
        'light' => 'bg-gray-100 hover:bg-gray-200 text-gray-800 focus:ring-gray-200',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white focus:ring-gray-700',
        'outline-primary' => 'bg-transparent hover:bg-blue-50 text-blue-600 border border-blue-600 focus:ring-blue-500',
        'outline-secondary' => 'bg-transparent hover:bg-gray-50 text-gray-600 border border-gray-500 focus:ring-gray-400',
        'outline-success' => 'bg-transparent hover:bg-green-50 text-green-600 border border-green-500 focus:ring-green-400',
        'outline-danger' => 'bg-transparent hover:bg-red-50 text-red-600 border border-red-500 focus:ring-red-400',
        'outline-warning' => 'bg-transparent hover:bg-yellow-50 text-yellow-600 border border-yellow-500 focus:ring-yellow-400',
        'outline-info' => 'bg-transparent hover:bg-sky-50 text-sky-600 border border-sky-500 focus:ring-sky-400',
        'outline-light' => 'bg-transparent hover:bg-gray-50 text-gray-500 border border-gray-300 focus:ring-gray-200',
        'outline-dark' => 'bg-transparent hover:bg-gray-800 hover:bg-opacity-10 text-gray-800 border border-gray-800 focus:ring-gray-700',
    ];

    // Size styles
    $sizeStyles = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-base',
    ];

    // Full width
    $widthClass = $fullWidth ? 'w-full' : '';

    // Rounded
    $roundedClass = $rounded ? 'rounded-full' : 'rounded-lg';

    // Animation
    $animateClass = $animate ? 'transform transition-all duration-300 hover:scale-105 hover:shadow-md active:scale-95' : 'transition-colors duration-200';

    // Disabled
    $disabledClass = $disabled ? 'opacity-60 cursor-not-allowed' : '';

    // Icon positions
    $iconClasses = [
        'left' => 'mr-2',
        'right' => 'ml-2 order-2',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 {$variantStyles[$variant]} {$sizeStyles[$size]} {$widthClass} {$roundedClass} {$animateClass} {$disabledClass}"
    ]) }}>
        @if ($loading)
            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $iconClasses[$iconPosition] }}" style="color: currentColor;"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $iconClasses[$iconPosition] }}" style="color: currentColor;"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 {$variantStyles[$variant]} {$sizeStyles[$size]} {$widthClass} {$roundedClass} {$animateClass} {$disabledClass}"
    ]) }} @if($disabled) disabled @endif>
        @if ($loading)
            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $iconClasses[$iconPosition] }}" style="color: currentColor;"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $iconClasses[$iconPosition] }}" style="color: currentColor;"></i>
        @endif
    </button>
@endif
