@props([
    'scholarProfile' => null,
    'isAdmin' => false
])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Scholarship Start Date"
        name="start_date"
        type="date"
        :value="$scholarProfile->start_date ?? ''"
        placeholder="e.g., 2024-01-15"
        required />
    
    <x-form-field 
        label="Enrollment Type"
        name="enrollment_type"
        type="select"
        :value="$scholarProfile->enrollment_type ?? ''"
        :options="['New' => 'New', 'Lateral' => 'Lateral']"
        required />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Study Time"
        name="study_time"
        type="select"
        :value="$scholarProfile->study_time ?? ''"
        :options="['Full-time' => 'Full-time', 'Part-time' => 'Part-time']"
        required />
    
    <x-form-field 
        label="Scholarship Duration (months)"
        name="scholarship_duration"
        type="number"
        :value="$scholarProfile->scholarship_duration ?? ''"
        placeholder="e.g., 24"
        min="1"
        max="60"
        required />
</div>



@if($isAdmin)
    <x-form-field 
        label="Scholarship Status"
        name="status"
        type="select"
        :value="$scholarProfile->status ?? ''"
        :options="[
            'Active' => 'Active',
            'Graduated' => 'Graduated',
            'Deferred' => 'Deferred',
            'Dropped' => 'Dropped',
            'Inactive' => 'Inactive'
        ]"
        required />
    
    <x-form-field 
        label="Actual Completion Date"
        name="actual_completion_date"
        type="date"
        :value="$scholarProfile->actual_completion_date ?? ''"
        placeholder="e.g., 2026-12-15"
        help-text="Leave blank if not yet completed" />

@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-field 
            label="Scholarship Status"
            name="scholarship_status"
            type="select"
            :value="$scholarProfile->scholarship_status ?? ''"
            :options="[
                'ongoing' => 'Ongoing',
                'completed' => 'Completed',
                'on_hold' => 'On Hold',
                'add' => 'Add',
                'deferred_repayment' => 'Deferred Repayment'
            ]" />
        
        <x-form-field 
            label="Year Graduated"
            name="year_graduated"
            type="date"
            :value="$scholarProfile->year_graduated ?? ''"
            placeholder="e.g., 2026-05-15"
            help-text="Leave blank if not yet graduated" />
    </div>
    
    <x-form-field 
        label="Actual Completion Date"
        name="actual_completion_date"
        type="date"
        :value="$scholarProfile->actual_completion_date ?? ''"
        placeholder="e.g., 2026-12-15"
        help-text="Leave blank if not yet completed" />
    
    <x-form-field 
        label="Notes"
        name="notes"
        type="textarea"
        :value="$scholarProfile->notes ?? ''"
        placeholder="e.g., Scholar is currently working on thesis defense preparation"
        :rows="3" />
@endif