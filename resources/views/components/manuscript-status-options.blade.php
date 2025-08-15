{{-- Reusable component for manuscript status options --}}
@props(['includeAll' => true, 'allText' => 'All Statuses'])

@if($includeAll)
    <option value="">{{ $allText }}</option>
@endif
<option value="Submitted">Submitted</option>
<option value="Under Review">Under Review</option>
<option value="Revision Requested">Revision Requested</option>
<option value="Accepted">Accepted</option>
<option value="Published">Published</option>
<option value="Rejected">Rejected</option>