@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'options' => [],
    'rows' => 3,
    'class' => '',
    'helpText' => null,
    'disabled' => false
])

<div class="mb-6 sm:mb-4">
    <label for="{{ $name }}" class="block text-sm sm:text-base font-medium text-gray-700 mb-3 sm:mb-2 leading-relaxed">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    @if($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base min-h-[44px] touch-manipulation {{ $class }}"
        >
            <option value="">Select {{ $label }}</option>
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-vertical text-base min-h-[100px] touch-manipulation {{ $class }}"
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'date')
        <input 
            type="date" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base min-h-[44px] touch-manipulation {{ $class }}"
        >
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base min-h-[44px] touch-manipulation {{ $class }}"
        >
    @endif
    
    @if($helpText)
        <p class="mt-2 text-sm sm:text-xs text-gray-500 leading-relaxed">{{ $helpText }}</p>
    @endif
    
    @error($name)
        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>
