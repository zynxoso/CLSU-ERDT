{{-- Reusable component for manuscript type options --}}
@props(['includeAll' => true, 'allText' => 'All Types'])

@if($includeAll)
    <option value="">{{ $allText }}</option>
@endif
<option value="Outline">Outline</option>
<option value="Final">Final</option>