@props([
    'id',
    'name',
    'label' => '',
    'required' => false,
    'options' => [],
    'value' => '',
    'help' => '',
    'error' => null,
])
<div class="mb-4">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <select
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200']) }}
    >
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if($help)
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif
    @if($error)
        <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
    @endif
</div>
