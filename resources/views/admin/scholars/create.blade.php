@extends('layouts.app')

@section('title', 'Add New Scholar')

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold mt-2" style="color: #424242;">Add New Scholar</h1>
        </div>
        <div class="bg-white rounded-lg p-6 border shadow-sm" style="border-color: #E0E0E0;">
            <form action="{{ route('admin.scholars.store') }}" method="POST" id="multi-step-scholar-form">
                @csrf

                <!-- Step 1: Basic Information -->
                <fieldset id="step-1" class="step-content" aria-labelledby="legend-step-1">
                    <legend id="legend-step-1" class="text-xl font-semibold mb-4" style="color: #424242;">Basic Information</legend>

                    <div class="mb-6 p-4 rounded-lg border" style="background-color: #FFF3E0; border-color: #FFCA28;">
                        <h3 class="text-lg font-semibold mb-2" style="color: #E65100;">
                            <i class="fas fa-info-circle mr-2" style="color: #FFCA28;"></i> Scholar Login Information
                        </h3>
                        <p class="text-sm mb-3" style="color: #BF360C;">When you create a scholar account, the system will generate the following login credentials:</p>

                        <div class="flex flex-col md:flex-row md:items-center mb-2 p-3 bg-white rounded border" style="border-color: #FFE082;">
                            <div class="font-medium md:w-1/4" style="color: #E65100;">Login Email:</div>
                            <div class="md:w-3/4" style="color: #424242;">The email address you provide in the form below</div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center p-3 bg-white rounded border" style="border-color: #FFE082;">
                            <div class="font-medium md:w-1/4" style="color: #E65100;">Default Password:</div>
                            <div class="md:w-3/4 flex items-center">
                                <code class="px-2 py-1 rounded font-mono" style="background-color: #F5F5F5; color: #424242;">CLSU-scholar123</code>
                                <button type="button" class="ml-2 px-2 py-1 rounded text-sm" style="background-color: #FFF3E0; color: #E65100;" onclick="copyPassword()">
                                    <i class="fas fa-copy mr-1"></i> Copy
                                </button>
                            </div>
                        </div>

                        <p class="text-sm mt-3" style="color: #BF360C;">
                            <i class="fas fa-exclamation-triangle mr-1" style="color: #FFCA28;"></i> Important: The scholar will receive instructions to set their password after account creation.
                        </p>
                    </div>

                    <x-forms.input name="first_name" label="First Name" required :error="$errors->first('first_name')" />
                    <x-forms.input name="last_name" label="Last Name" required :error="$errors->first('last_name')" />
                    <x-forms.input name="middle_name" label="Middle Name" :error="$errors->first('middle_name')" />
                    <x-forms.input name="email" label="Email Address" type="email" required help="The scholar will use this email to login." :error="$errors->first('email')" />
                    <x-forms.input name="contact_number" label="Phone Number" required :error="$errors->first('contact_number')" />

                    <div class="flex justify-end mt-6">
                        <button type="button" id="next-step-1" class="px-6 py-2 text-white rounded-lg" style="background-color: #2E7D32;">
                            Next: Address & Contact <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </fieldset>

                <!-- Step 2: Address & Contact -->
                <fieldset id="step-2" class="step-content hidden" aria-labelledby="legend-step-2">
                    <legend id="legend-step-2" class="text-xl font-semibold mb-4" style="color: #424242;">Address & Contact Information</legend>
                    <x-forms.input name="address" label="Street Address" required placeholder="Street number, building, barangay" :error="$errors->first('address')" />
                    <x-forms.input name="city" label="City/Municipality" required :error="$errors->first('city')" />
                    <x-forms.input name="province" label="Province" required :error="$errors->first('province')" />
                    <x-forms.input name="postal_code" label="Postal Code" required :error="$errors->first('postal_code')" />
                    <x-forms.select name="country" label="Country" required :options="['Philippines' => 'Philippines', 'Other' => 'Other']" value="{{ old('country', 'Philippines') }}" :error="$errors->first('country')" />
                    <div class="flex justify-between mt-6">
                        <button type="button" id="prev-step-2" class="px-6 py-2 text-white rounded-lg" style="background-color: #757575;">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-step-2" class="px-6 py-2 text-white rounded-lg" style="background-color: #2E7D32;">
                            Next: Scholarship Details <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </fieldset>

                <!-- Step 3: Scholarship Details -->
                <div id="step-3" class="step-content hidden">
                    <h2 class="text-xl font-semibold mb-4" style="color: #424242;">Scholarship Details</h2>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #424242;">University Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="university" class="block text-sm font-medium mb-1" style="color: #424242;">University <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="university" name="university" value="Central Luzon State University" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly required>
                                <p class="text-xs mt-1" style="color: #757575;">CLSU - Central Luzon State University</p>
                                @error('university')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium mb-1" style="color: #424242;">Department <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="department" name="department" value="Department of Agricultural and Biosystems Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly required>
                                <p class="text-xs mt-1" style="color: #757575;">ABE Department at CLSU</p>
                                @error('department')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="program" class="block text-sm font-medium mb-1" style="color: #424242;">Program <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="program" name="program" value="Agricultural Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly required>
                                <p class="text-xs mt-1" style="color: #757575;">Agricultural Engineering Program</p>
                                @error('program')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="major" class="block text-sm font-medium mb-1" style="color: #424242;">Major/Specialization <span style="color: #D32F2F;">*</span></label>
                                <select id="major" name="major" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="">Select Major/Specialization</option>
                                    <option value="AB Machinery and Power Engineering" {{ old('major') == 'AB Machinery and Power Engineering' ? 'selected' : '' }}>AB Machinery and Power Engineering</option>
                                    <option value="AB Land and Water Resources Engineering" {{ old('major') == 'AB Land and Water Resources Engineering' ? 'selected' : '' }}>AB Land and Water Resources Engineering</option>
                                    <option value="AB Structures and Environment Engineering" {{ old('major') == 'AB Structures and Environment Engineering' ? 'selected' : '' }}>AB Structures and Environment Engineering</option>
                                    <option value="AB Process Engineering" {{ old('major') == 'AB Process Engineering' ? 'selected' : '' }}>AB Process Engineering</option>
                                </select>
                                <p class="text-xs mt-1" style="color: #757575;">Choose your specific area of specialization within ABE</p>
                                @error('major')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="degree_level" class="block text-sm font-medium mb-1" style="color: #424242;">Degree Level <span style="color: #D32F2F;">*</span></label>
                                <select id="degree_level" name="degree_level" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="Masteral" {{ old('degree_level') == 'Masteral' ? 'selected' : '' }}>Masteral</option>
                                    <option value="PhD" {{ old('degree_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                                @error('degree_level')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #424242;">Scholarship Status & Timeline</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium mb-1" style="color: #424242;">Status <span style="color: #D32F2F;">*</span></label>
                                <select id="status" name="status" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="New" {{ old('status') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="On Extension" {{ old('status') == 'On Extension' ? 'selected' : '' }}>On Extension</option>
                                    <option value="Graduated" {{ old('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Terminated" {{ old('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                                @error('status')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-1" style="color: #424242;">Start Date <span style="color: #D32F2F;">*</span></label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                @error('start_date')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="expected_completion_date" class="block text-sm font-medium mb-1" style="color: #424242;">Expected Completion Date <span style="color: #D32F2F;">*</span></label>
                                <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                @error('expected_completion_date')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="scholarship_duration" class="block text-sm font-medium mb-1" style="color: #424242;">Scholarship Duration (months) <span style="color: #D32F2F;">*</span></label>
                                <input type="number" id="scholarship_duration" name="scholarship_duration" value="{{ old('scholarship_duration') }}" min="1" max="60" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                @error('scholarship_duration')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="enrollment_type" class="block text-sm font-medium mb-1" style="color: #424242;">Enrollment Type <span style="color: #D32F2F;">*</span></label>
                                <select id="enrollment_type" name="enrollment_type" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="New" {{ old('enrollment_type') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Lateral" {{ old('enrollment_type') == 'Lateral' ? 'selected' : '' }}>Lateral</option>
                                </select>
                                @error('enrollment_type')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="study_time" class="block text-sm font-medium mb-1" style="color: #424242;">Study Time <span style="color: #D32F2F;">*</span></label>
                                <select id="study_time" name="study_time" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="Full-time" {{ old('study_time') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="Part-time" {{ old('study_time') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                </select>
                                @error('study_time')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #424242;">Academic Background</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bachelor_degree" class="block text-sm font-medium mb-1" style="color: #424242;">Bachelor's Degree</label>
                                <input type="text" id="bachelor_degree" name="bachelor_degree" value="BS in Agricultural and Biosystems Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly>
                                <p class="text-xs mt-1" style="color: #757575;">BSABE is the standard qualification for this program</p>
                                @error('bachelor_degree')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bachelor_university" class="block text-sm font-medium mb-1" style="color: #424242;">Bachelor's University</label>
                                <input type="text" id="bachelor_university" name="bachelor_university" value="Central Luzon State University" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly>
                                <p class="text-xs mt-1" style="color: #757575;">CLSU - Central Luzon State University</p>
                                @error('bachelor_university')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" id="prev-step-3" class="px-6 py-2 text-white rounded-lg" style="background-color: #757575;">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-step-3" class="px-6 py-2 text-white rounded-lg" style="background-color: #2E7D32;">
                            Next: Review & Submit <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Review & Submit -->
                <fieldset id="step-4" class="step-content hidden" aria-labelledby="legend-step-4" role="region">
                    <legend id="legend-step-4" class="text-xl font-semibold mb-4" style="color: #424242;">Review & Submit</legend>
                    <div class="p-6 rounded-lg mb-6 border" style="background-color: #F5F5F5; border-color: #E0E0E0;">
                        <h3 class="font-semibold mb-4" style="color: #424242;">Review Scholar Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <h4 class="font-medium border-b pb-1" style="color: #424242;">Basic Information</h4>
                                <div class="flex justify-between"><span style="color: #616161;">Name:</span><span id="review-name" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Email:</span><span id="review-email" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Phone:</span><span id="review-phone" class="font-medium" style="color: #424242;">-</span></div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-medium border-b pb-1" style="color: #424242;">Address</h4>
                                <div class="flex justify-between"><span style="color: #616161;">Street:</span><span id="review-address" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">City:</span><span id="review-city" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Province:</span><span id="review-province" class="font-medium" style="color: #424242;">-</span></div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-medium border-b pb-1" style="color: #424242;">Scholarship Details</h4>
                                <div class="flex justify-between"><span style="color: #616161;">Degree Level:</span><span id="review-degree" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Major:</span><span id="review-major" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Status:</span><span id="review-status" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Start Date:</span><span id="review-start-date" class="font-medium" style="color: #424242;">-</span></div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-medium border-b pb-1" style="color: #424242;">Program Details</h4>
                                <div class="flex justify-between"><span style="color: #616161;">Duration:</span><span id="review-duration" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Study Time:</span><span id="review-study-time" class="font-medium" style="color: #424242;">-</span></div>
                                <div class="flex justify-between"><span style="color: #616161;">Enrollment:</span><span id="review-enrollment" class="font-medium" style="color: #424242;">-</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button type="button" id="prev-step-4" class="px-6 py-2 text-white rounded-lg" style="background-color: #757575;">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="px-6 py-2 text-white rounded-lg" style="background-color: #2E7D32;">
                            <i class="fas fa-user-plus mr-2"></i> Create Scholar Account
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<!-- Scholar Account Creation Confirmation Modal -->
<x-modal id="scholar-create-confirm-modal" :closeButton="false">
    <x-slot name="title">Create Scholar Account?</x-slot>
    <div>This will create a new scholar account with the provided information.</div>
    <x-slot name="footer">
        <x-button type="button" variant="secondary" onclick="closeModal('scholar-create-confirm-modal')">Cancel</x-button>
        <x-button type="button" variant="success" id="confirm-create-scholar">Yes, create account!</x-button>
    </x-slot>
</x-modal>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    const steps = ['step-1', 'step-2', 'step-3', 'step-4'];
    let currentStep = 0;

    function showStep(stepIndex) {
        steps.forEach((stepId, index) => {
            const stepElement = document.getElementById(stepId);
            if (index === stepIndex) {
                stepElement.classList.remove('hidden');
            } else {
                stepElement.classList.add('hidden');
            }
        });
        currentStep = stepIndex;
    }

    function validateStep1() {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const contactNumber = document.getElementById('contact_number').value.trim();

        if (!firstName || !lastName || !email || !contactNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields in Step 1.',
                customClass: {
                    confirmButton: 'swal-confirm-button'
                },
                showConfirmButton: true,
                timer: undefined
            });
            return false;
        }
        return true;
    }

    function validateStep2() {
        const address = document.getElementById('address').value.trim();
        const city = document.getElementById('city').value.trim();
        const province = document.getElementById('province').value.trim();
        const postalCode = document.getElementById('postal_code').value.trim();

        if (!address || !city || !province || !postalCode) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields in Step 2.',
                customClass: {
                    confirmButton: 'swal-confirm-button'
                },
                showConfirmButton: true,
                timer: undefined
            });
            return false;
        }
        return true;
    }

    function validateStep3() {
        const degreeLevel = document.getElementById('degree_level').value;
        const major = document.getElementById('major').value;
        const status = document.getElementById('status').value;
        const startDate = document.getElementById('start_date').value;
        const expectedCompletionDate = document.getElementById('expected_completion_date').value;
        const scholarshipDuration = document.getElementById('scholarship_duration').value;
        const enrollmentType = document.getElementById('enrollment_type').value;
        const studyTime = document.getElementById('study_time').value;

        if (!degreeLevel || !major || !status || !startDate || !expectedCompletionDate || !scholarshipDuration || !enrollmentType || !studyTime) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields in Step 3.',
                customClass: {
                    confirmButton: 'swal-confirm-button'
                },
                showConfirmButton: true,
                timer: undefined
            });
            return false;
        }
        return true;
    }

    function updateReviewSection() {
        // Basic Information
        const firstName = document.getElementById('first_name').value;
        const middleName = document.getElementById('middle_name').value;
        const lastName = document.getElementById('last_name').value;
        const fullName = `${firstName} ${middleName ? middleName + ' ' : ''}${lastName}`;

        document.getElementById('review-name').textContent = fullName;
        document.getElementById('review-email').textContent = document.getElementById('email').value;
        document.getElementById('review-phone').textContent = document.getElementById('contact_number').value;

        // Address
        document.getElementById('review-address').textContent = document.getElementById('address').value;
        document.getElementById('review-city').textContent = document.getElementById('city').value;
        document.getElementById('review-province').textContent = document.getElementById('province').value;

        // Scholarship Details
        document.getElementById('review-degree').textContent = document.getElementById('degree_level').value;
        document.getElementById('review-major').textContent = document.getElementById('major').value;
        document.getElementById('review-status').textContent = document.getElementById('status').value;
        document.getElementById('review-start-date').textContent = document.getElementById('start_date').value;
        document.getElementById('review-duration').textContent = document.getElementById('scholarship_duration').value + ' months';
        document.getElementById('review-study-time').textContent = document.getElementById('study_time').value;
        document.getElementById('review-enrollment').textContent = document.getElementById('enrollment_type').value;
    }

    // Step navigation
    document.getElementById('next-step-1').addEventListener('click', function() {
        if (validateStep1()) {
            showStep(1);
        }
    });

    document.getElementById('prev-step-2').addEventListener('click', function() {
        showStep(0);
    });

    document.getElementById('next-step-2').addEventListener('click', function() {
        if (validateStep2()) {
            showStep(2);
        }
    });

    document.getElementById('prev-step-3').addEventListener('click', function() {
        showStep(1);
    });

    document.getElementById('next-step-3').addEventListener('click', function() {
        if (validateStep3()) {
            updateReviewSection();
            showStep(3);
        }
    });

    document.getElementById('prev-step-4').addEventListener('click', function() {
        showStep(2);
    });

    // Form submission with confirmation
    const form = document.getElementById('multi-step-scholar-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        openModal('scholar-create-confirm-modal');
    });

    document.getElementById('confirm-create-scholar').addEventListener('click', function() {
        // Create a hidden field with timestamp
        const timestampField = document.createElement('input');
        timestampField.type = 'hidden';
        timestampField.name = 'submission_time';
        timestampField.value = new Date().toISOString();
        form.appendChild(timestampField);
        closeModal('scholar-create-confirm-modal');
        form.submit();
    });

    // Function to copy the default password to clipboard
    window.copyPassword = function() {
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
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Password copied to clipboard: ' + password,
            timer: undefined,
            showConfirmButton: true,
            customClass: {
                popup: 'swal-popup'
            }
        });
    }
});
</script>

<style>
/* Global Typography Improvements */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #424242;
}

/* SweetAlert Custom Styling */
.swal-confirm-button {
    background-color: #2E7D32 !important;
    border: none !important;
    border-radius: 0.375rem !important;
    font-weight: 600 !important;
    padding: 0.6em 2em !important;
}

.swal-popup {
    border-radius: 0.75rem !important;
    box-shadow: 0 4px 24px 0 rgba(46, 125, 50, 0.08);
}

/* Enhanced Button Styling */
button, a {
    transition: none !important;
}

/* Professional Shadow Effects */
.shadow-sm {
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}
</style>
@endsection
