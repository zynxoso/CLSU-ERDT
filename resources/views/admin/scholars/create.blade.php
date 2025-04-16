@extends('layouts.app')

@section('title', 'Add New Scholar')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('admin.scholars.index') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Scholars
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Add New Scholar</h1>
            <p class="text-gray-500 mt-1">ERDT PRISM: A Portal for a Responsive and Integrated Scholarship Management</p>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('admin.scholars.store') }}" method="POST" id="create-scholar-form">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i> Scholar Login Information
                    </h2>
                    <p class="text-sm text-blue-700 mb-3">When you create a scholar account, the system will generate the following login credentials:</p>

                    <div class="flex flex-col md:flex-row md:items-center mb-2 p-3 bg-white rounded border border-blue-100">
                        <div class="font-medium text-blue-900 md:w-1/4">Login Email:</div>
                        <div class="md:w-3/4 text-gray-700">The email address you provide in the form below</div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center p-3 bg-white rounded border border-blue-100">
                        <div class="font-medium text-blue-900 md:w-1/4">Default Password:</div>
                        <div class="md:w-3/4 flex items-center">
                            <code class="bg-gray-100 px-2 py-1 rounded text-gray-700 font-mono">CLSU-scholar123</code>
                            <button type="button" class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 focus:outline-none text-sm" onclick="copyPassword()">
                                <i class="fas fa-copy mr-1"></i> Copy
                            </button>
                        </div>
                    </div>

                    <p class="text-sm text-blue-700 mt-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Important: Please inform the scholar about their default password. They should change it after their first login.
                    </p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('middle_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">The scholar will use this email to login.</p>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('contact_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Address Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Street number, building, barangay" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Municipality <span class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('city')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('province')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code <span class="text-red-500">*</span></label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <select id="country" name="country" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Philippines" {{ old('country', 'Philippines') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('country')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scholarship Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="university" class="block text-sm font-medium text-gray-700 mb-1">University <span class="text-red-500">*</span></label>
                            <select id="university" name="university" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Central Luzon State University" {{ old('university', 'Central Luzon State University') == 'Central Luzon State University' ? 'selected' : '' }}>Central Luzon State University (CLSU)</option>
                                <option value="University of the Philippines Los Baños" {{ old('university') == 'University of the Philippines Los Baños' ? 'selected' : '' }}>University of the Philippines Los Baños</option>
                                <option value="Bulacan State University" {{ old('university') == 'Bulacan State University' ? 'selected' : '' }}>Bulacan State University</option>
                                <option value="Nueva Ecija University of Science and Technology" {{ old('university') == 'Nueva Ecija University of Science and Technology' ? 'selected' : '' }}>Nueva Ecija University of Science and Technology</option>
                                <option value="Other" {{ old('university') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                            @error('university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                            <select id="department" name="department" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Engineering" {{ old('department', 'Engineering') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="Agricultural Engineering Department" {{ old('department') == 'Agricultural Engineering Department' ? 'selected' : '' }}>Agricultural Engineering Department</option>
                                <option value="Department of Agricultural and Biosystems Engineering" {{ old('department') == 'Department of Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Department of Agricultural and Biosystems Engineering</option>
                                <option value="College of Engineering" {{ old('department') == 'College of Engineering' ? 'selected' : '' }}>College of Engineering</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Department offering BSABE at CLSU</p>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                            <select id="program" name="program" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Master in Agricultural and Biosystems Engineering" {{ old('program') == 'Master in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Master in Agricultural and Biosystems Engineering</option>
                                <option value="Master of Science in Agricultural and Biosystems Engineering" {{ old('program') == 'Master of Science in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Master of Science in Agricultural and Biosystems Engineering</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">ABE Masteral Scholarship Program</p>
                            @error('program')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="New" {{ old('status') == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="On Extension" {{ old('status') == 'On Extension' ? 'selected' : '' }}>On Extension</option>
                                <option value="Graduated" {{ old('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="Terminated" {{ old('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                <option value="Deferred Repayment" {{ old('status') == 'Deferred Repayment' ? 'selected' : '' }}>Deferred Repayment</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date <span class="text-red-500">*</span></label>
                            <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('expected_completion_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Academic Background</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="bachelor_degree" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Degree</label>
                            <select id="bachelor_degree" name="bachelor_degree" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="BS in Agricultural and Biosystems Engineering" {{ old('bachelor_degree', 'BS in Agricultural and Biosystems Engineering') == 'BS in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>BS in Agricultural and Biosystems Engineering (BSABE)</option>
                                <option value="BS in Agricultural Engineering" {{ old('bachelor_degree') == 'BS in Agricultural Engineering' ? 'selected' : '' }}>BS in Agricultural Engineering</option>
                                <option value="BS in Biosystems Engineering" {{ old('bachelor_degree') == 'BS in Biosystems Engineering' ? 'selected' : '' }}>BS in Biosystems Engineering</option>
                                <option value="Other Engineering Degree" {{ old('bachelor_degree') == 'Other Engineering Degree' ? 'selected' : '' }}>Other Engineering Degree</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">BSABE is preferred for this scholarship program</p>
                            @error('bachelor_degree')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bachelor_university" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's University</label>
                            <select id="bachelor_university" name="bachelor_university" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Central Luzon State University" {{ old('bachelor_university', 'Central Luzon State University') == 'Central Luzon State University' ? 'selected' : '' }}>Central Luzon State University (CLSU)</option>
                                <option value="University of the Philippines Los Baños" {{ old('bachelor_university') == 'University of the Philippines Los Baños' ? 'selected' : '' }}>University of the Philippines Los Baños</option>
                                <option value="Bulacan State University" {{ old('bachelor_university') == 'Bulacan State University' ? 'selected' : '' }}>Bulacan State University</option>
                                <option value="Nueva Ecija University of Science and Technology" {{ old('bachelor_university') == 'Nueva Ecija University of Science and Technology' ? 'selected' : '' }}>Nueva Ecija University of Science and Technology</option>
                                <option value="Other" {{ old('bachelor_university') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">CLSU graduates are given priority for this program</p>
                            @error('bachelor_university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bachelor_graduation_year" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Graduation Year</label>
                            <input type="number" id="bachelor_graduation_year" name="bachelor_graduation_year" value="{{ old('bachelor_graduation_year') }}" min="1950" max="{{ date('Y') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('bachelor_graduation_year')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="research_area" class="block text-sm font-medium text-gray-700 mb-1">Research Area</label>
                            <select id="research_area" name="research_area" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Research Area</option>
                                <option value="Agricultural Machinery and Equipment" {{ old('research_area') == 'Agricultural Machinery and Equipment' ? 'selected' : '' }}>Agricultural Machinery and Equipment</option>
                                <option value="Irrigation and Water Resources" {{ old('research_area') == 'Irrigation and Water Resources' ? 'selected' : '' }}>Irrigation and Water Resources</option>
                                <option value="Food Processing and Post-Harvest Technology" {{ old('research_area') == 'Food Processing and Post-Harvest Technology' ? 'selected' : '' }}>Food Processing and Post-Harvest Technology</option>
                                <option value="Environmental Engineering" {{ old('research_area') == 'Environmental Engineering' ? 'selected' : '' }}>Environmental Engineering</option>
                                <option value="Sustainable Agriculture" {{ old('research_area') == 'Sustainable Agriculture' ? 'selected' : '' }}>Sustainable Agriculture</option>
                                <option value="Renewable Energy" {{ old('research_area') == 'Renewable Energy' ? 'selected' : '' }}>Renewable Energy</option>
                                <option value="Precision Agriculture" {{ old('research_area') == 'Precision Agriculture' ? 'selected' : '' }}>Precision Agriculture</option>
                                <option value="Other" {{ old('research_area') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('research_area')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-user-plus mr-2 text-white" style="color: white !important;"></i> Create Scholar Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-scholar-form');

    // Add error handling for form submission
    form.addEventListener('submit', function(e) {
        console.log('Form submission attempted');

        // Create a hidden field with timestamp
        const timestampField = document.createElement('input');
        timestampField.type = 'hidden';
        timestampField.name = 'submission_time';
        timestampField.value = new Date().toISOString();
        form.appendChild(timestampField);

        // Check if the form has a CSRF token
        const csrfToken = form.querySelector('input[name="_token"]');
        if (!csrfToken || !csrfToken.value) {
            console.error('CSRF token missing or empty');
            alert('CSRF token is missing. Please refresh the page and try again.');
            e.preventDefault();
            return false;
        }

        // If normal form submission fails, try AJAX
        const formData = new FormData(form);

        // Make a backup submission to our debug endpoint
        fetch('/debug-scholar-form', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            console.log('Debug form submission result:', data);
            // Don't prevent the normal form submission
        })
        .catch(error => {
            console.error('Debug form submission error:', error);
            // Still don't prevent the normal form submission
        });

        // Continue with normal form submission
        return true;
    });
});

// Function to copy the default password to clipboard
function copyPassword() {
    const password = 'CLSU-scholar123';

    // Create a temporary element
    const tempElement = document.createElement('textarea');
    tempElement.value = password;
    document.body.appendChild(tempElement);

    // Select and copy the text
    tempElement.select();
    document.execCommand('copy');

    // Remove the temporary element
    document.body.removeChild(tempElement);

    // Show a tooltip or notification
    alert('Password copied to clipboard: ' + password);
}
</script>
@endsection
