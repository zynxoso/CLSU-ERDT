@props([
    'scholarProfile' => null,
    'includePhoto' => true,
    'photoRequired' => false
])

@if($includePhoto)
    <x-profile-photo-upload 
        :current-photo="$scholarProfile->profile_photo ?? null"
        :required="$photoRequired" />
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="First Name"
        name="first_name"
        :value="$scholarProfile->first_name ?? ''"
        placeholder="e.g., Juan"
        required />
    
    <x-form-field 
        label="Middle Name"
        name="middle_name"
        :value="$scholarProfile->middle_name ?? ''"
        placeholder="e.g., Santos" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Last Name"
        name="last_name"
        :value="$scholarProfile->last_name ?? ''"
        placeholder="e.g., Dela Cruz"
        required />
    
    <x-form-field 
        label="Suffix"
        name="suffix"
        :value="$scholarProfile->suffix ?? ''"
        placeholder="e.g., Jr., Sr., III" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Birth Date"
        name="birth_date"
        type="date"
        :value="$scholarProfile->birth_date ?? ''"
        required />
    
    <x-form-field 
        label="Gender"
        name="gender"
        type="select"
        :value="$scholarProfile->gender ?? ''"
        :options="['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other', 'Prefer not to say' => 'Prefer not to say']"
        required />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Contact Number"
        name="contact_number"
        type="tel"
        :value="$scholarProfile->contact_number ?? ''"
        placeholder="e.g., +63 912 345 6789"
        required />
    
    <x-form-field 
        label="Email Address"
        name="email"
        type="email"
        :value="$scholarProfile->email ?? ''"
        placeholder="e.g., juan.delacruz@email.com"
        required />
</div>