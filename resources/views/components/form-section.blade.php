@props([
    'title',
    'description' => null,
    'step' => null,
    'icon' => null,
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'form-section ' . $class]) }}
     @if($step !== null) x-show="currentStep === {{ $step }}" @endif>
    
    @if($title)
        <div class="mb-6 pb-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                @if($icon)
                    <div class="flex-shrink-0">
                        {!! $icon !!}
                    </div>
                @endif
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                    @if($description)
                        <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
