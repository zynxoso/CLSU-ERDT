@props([
    'title',
    'data' => [],
    'columns' => 1,
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'review-section ' . $class]) }}>
    @if($title)
        <h3 class="font-medium text-gray-900 mb-3">{{ $title }}</h3>
    @endif
    
    <div class="{{ $columns > 1 ? 'grid grid-cols-' . $columns . ' gap-6' : '' }}">
        @if(is_array($data) && count($data) > 0)
            <ul class="text-gray-600 space-y-2">
                @foreach($data as $label => $value)
                    @if($value !== null && $value !== '')
                        <li class="flex flex-wrap">
                            <span class="font-semibold text-gray-700 min-w-0 flex-shrink-0">{{ $label }}:</span>
                            <span class="ml-2 break-words">{{ $value }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            {{ $slot }}
        @endif
    </div>
</div>