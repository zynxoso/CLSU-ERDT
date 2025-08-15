@props([
    'scholarProfile' => null
])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Street"
        name="street"
        :value="$scholarProfile->street ?? ''"
        placeholder="e.g., 123 Rizal Street" />
    
    <x-form-field 
        label="Village/Barangay"
        name="village"
        :value="$scholarProfile->village ?? ''"
        placeholder="e.g., Barangay San Jose" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Town/City"
        name="town"
        :value="$scholarProfile->town ?? ''"
        placeholder="e.g., Science City of Muñoz" />
    
    <x-form-field 
        label="District"
        name="district"
        :value="$scholarProfile->district ?? ''"
        placeholder="e.g., District 1" />
    
    <x-form-field 
        label="Region"
        name="region"
        :value="$scholarProfile->region ?? ''"
        placeholder="e.g., Region III (Central Luzon)" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-form-field 
        label="Province"
        name="province"
        :value="$scholarProfile->province ?? ''"
        placeholder="e.g., Nueva Ecija" />
    
    <x-form-field 
        label="Country"
        name="country"
        :value="$scholarProfile->country ?? 'Philippines'"
        placeholder="e.g., Philippines" />
</div>
