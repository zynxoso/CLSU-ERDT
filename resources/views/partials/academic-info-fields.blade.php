@props([
    'scholarProfile' => null,
    'universities' => [],
    'departments' => [],
    'isAdmin' => false
])

<!-- Current Academic Information -->
<div class="mb-6">
    <h4 class="text-lg font-semibold text-gray-700 mb-4">Current Academic Information</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-field 
            label="Intended University"
            name="intended_university"
            value="Central Luzon State University"
            readonly
            required />
        
        <x-form-field 
            label="Department"
            name="department"
            value="Engineering"
            readonly
            required />
    </div>

    <x-form-field 
        label="Major/Specialization"
        name="major"
        type="select"
        :value="$scholarProfile->major ?? ''"
        :options="[
            'AB Machinery and Power Engineering' => 'AB Machinery and Power Engineering',
            'AB Land and Water Resources Engineering' => 'AB Land and Water Resources Engineering',
            'AB Structures and Environment Engineering' => 'AB Structures and Environment Engineering',
            'AB Process Engineering' => 'AB Process Engineering'
        ]"
        required />

    <x-form-field 
        label="Intended Degree"
        name="intended_degree"
        type="select"
        :value="$scholarProfile->intended_degree ?? ''"
        :options="[
            'PHD in ABE' => 'PHD in ABE',
            'MS in ABE' => 'MS in ABE'
        ]"
        required />


</div>

<!-- Previous Academic Background -->
<div class="mb-6">
    <h4 class="text-lg font-semibold text-gray-700 mb-4">Previous Academic Background</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-field 
            label="Course Completed"
            name="course_completed"
            type="select"
            :value="$scholarProfile->course_completed ?? ''"
            :options="[
                'MS in Agricultural Engineering' => 'MS in Agricultural Engineering',
                'BS Agricultural and Biosystem Engineering' => 'BS Agricultural and Biosystem Engineering'
            ]" />
        
        <x-form-field 
            label="University Graduated"
            name="university_graduated"
            :value="$scholarProfile->university_graduated ?? ''"
            placeholder="e.g., University of the Philippines Los Baños" />
    </div>

    <x-form-field 
        label="Entry Type"
        name="entry_type"
        type="select"
        :value="$scholarProfile->entry_type ?? ''"
        :options="['new' => 'New', 'lateral' => 'Lateral']"
        required />
</div>



<!-- Research Information -->
<div class="mb-6">
    <h4 class="text-lg font-semibold text-gray-700 mb-4">Research Information</h4>
    
    <x-form-field 
        label="Thesis/Dissertation Title"
        name="thesis_dissertation_title"
        type="textarea"
        :value="$scholarProfile->thesis_dissertation_title ?? ''"
        placeholder="e.g., Development of Smart Irrigation System for Rice Production"
        :rows="3" />
</div>

<!-- Academic Units -->
<div class="mb-6">
    <h4 class="text-lg font-semibold text-gray-700 mb-4">Academic Units</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-field 
            label="Units Required"
            name="units_required"
            type="number"
            :value="$scholarProfile->units_required ?? ''"
            placeholder="e.g., 60" />
        
        <x-form-field 
            label="Units Earned Prior"
            name="units_earned_prior"
            type="number"
            :value="$scholarProfile->units_earned_prior ?? ''"
            placeholder="e.g., 24" />
    </div>
</div>