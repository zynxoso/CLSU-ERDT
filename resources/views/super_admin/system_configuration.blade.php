@extends('layouts.app')

@section('title', 'System Configuration')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">System Configuration</h1>
        <a href="{{ route('super_admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Current Settings Status -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">📊 Current System Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="font-medium text-blue-700">Academic Year:</span>
                <span class="text-blue-600">{{ $academicSettings['academic_year'] ?? 'Not Set' }}</span>
            </div>
            <div>
                <span class="font-medium text-blue-700">Current Semester:</span>
                <span class="text-blue-600">{{ \App\Models\SiteSetting::getCurrentSemester() }}</span>
            </div>
            <div>
                <span class="font-medium text-blue-700">Max Monthly Allowance:</span>
                <span class="text-blue-600">₱{{ number_format($scholarshipSettings['max_monthly_allowance'] ?? 0) }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Academic Calendar Configuration -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                    </svg>
                    Academic Calendar Configuration
                </h2>
            </div>
            <div class="p-6">
                <form id="academic-calendar-form" action="{{ route('super_admin.system_configuration.academic_calendar') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Academic Year -->
                        <div>
                            <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Current Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year"
                                   value="{{ $academicSettings['academic_year'] ?? '2024-2025' }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., 2024-2025" required>
                        </div>

                        <!-- Semester Configuration -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="first_semester_start" class="block text-sm font-medium text-gray-700 mb-1">1st Semester Start</label>
                                <input type="date" id="first_semester_start" name="first_semester_start"
                                       value="{{ isset($academicSettings['first_semester_start']) ? \Carbon\Carbon::parse($academicSettings['first_semester_start'])->format('Y-m-d') : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="first_semester_end" class="block text-sm font-medium text-gray-700 mb-1">1st Semester End</label>
                                <input type="date" id="first_semester_end" name="first_semester_end"
                                       value="{{ isset($academicSettings['first_semester_end']) ? \Carbon\Carbon::parse($academicSettings['first_semester_end'])->format('Y-m-d') : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="second_semester_start" class="block text-sm font-medium text-gray-700 mb-1">2nd Semester Start</label>
                                <input type="date" id="second_semester_start" name="second_semester_start"
                                       value="{{ isset($academicSettings['second_semester_start']) ? \Carbon\Carbon::parse($academicSettings['second_semester_start'])->format('Y-m-d') : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="second_semester_end" class="block text-sm font-medium text-gray-700 mb-1">2nd Semester End</label>
                                <input type="date" id="second_semester_end" name="second_semester_end"
                                       value="{{ isset($academicSettings['second_semester_end']) ? \Carbon\Carbon::parse($academicSettings['second_semester_end'])->format('Y-m-d') : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>

                        <!-- Summer Term -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="summer_term_start" class="block text-sm font-medium text-gray-700 mb-1">Summer Term Start</label>
                                <input type="date" id="summer_term_start" name="summer_term_start"
                                       value="{{ isset($academicSettings['summer_term_start']) ? \Carbon\Carbon::parse($academicSettings['summer_term_start'])->format('Y-m-d') : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="summer_term_end" class="block text-sm font-medium text-gray-700 mb-1">Summer Term End</label>
                                <input type="date" id="summer_term_end" name="summer_term_end"
                                       value="{{ isset($academicSettings['summer_term_end']) ? \Carbon\Carbon::parse($academicSettings['summer_term_end'])->format('Y-m-d') : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Application Deadlines -->
                        <div class="border-t pt-4">
                            <h3 class="text-md font-medium text-gray-800 mb-3">Application Deadlines</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="application_deadline_1st" class="block text-sm font-medium text-gray-700 mb-1">1st Semester Deadline</label>
                                    <input type="date" id="application_deadline_1st" name="application_deadline_1st"
                                           value="{{ isset($academicSettings['application_deadline_1st']) ? \Carbon\Carbon::parse($academicSettings['application_deadline_1st'])->format('Y-m-d') : '' }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="application_deadline_2nd" class="block text-sm font-medium text-gray-700 mb-1">2nd Semester Deadline</label>
                                    <input type="date" id="application_deadline_2nd" name="application_deadline_2nd"
                                           value="{{ isset($academicSettings['application_deadline_2nd']) ? \Carbon\Carbon::parse($academicSettings['application_deadline_2nd'])->format('Y-m-d') : '' }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200">
                            Save Academic Calendar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scholarship Program Parameters -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    Scholarship Program Parameters
                </h2>
            </div>
            <div class="p-6">
                <form id="scholarship-parameters-form" action="{{ route('super_admin.system_configuration.scholarship_parameters') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Funding Limits -->
                        <div class="border-b pb-4">
                            <h3 class="text-md font-medium text-gray-800 mb-3">Funding Limits</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="max_monthly_allowance" class="block text-sm font-medium text-gray-700 mb-1">Maximum Monthly Allowance (₱)</label>
                                    <input type="number" id="max_monthly_allowance" name="max_monthly_allowance"
                                           value="{{ $scholarshipSettings['max_monthly_allowance'] ?? 15000 }}" step="100"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="max_tuition_support" class="block text-sm font-medium text-gray-700 mb-1">Maximum Tuition Support (₱)</label>
                                    <input type="number" id="max_tuition_support" name="max_tuition_support"
                                           value="{{ $scholarshipSettings['max_tuition_support'] ?? 50000 }}" step="1000"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="max_research_allowance" class="block text-sm font-medium text-gray-700 mb-1">Maximum Research Allowance (₱)</label>
                                    <input type="number" id="max_research_allowance" name="max_research_allowance"
                                           value="{{ $scholarshipSettings['max_research_allowance'] ?? 30000 }}" step="5000"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="max_book_allowance" class="block text-sm font-medium text-gray-700 mb-1">Maximum Book Allowance (₱)</label>
                                    <input type="number" id="max_book_allowance" name="max_book_allowance"
                                           value="{{ $scholarshipSettings['max_book_allowance'] ?? 10000 }}" step="1000"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="max_scholarship_duration" class="block text-sm font-medium text-gray-700 mb-1">Maximum Scholarship Duration (months)</label>
                                    <input type="number" id="max_scholarship_duration" name="max_scholarship_duration"
                                           value="{{ $scholarshipSettings['max_scholarship_duration'] ?? 36 }}" min="1" max="120"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                                </div>
                            </div>
                        </div>

                        <!-- Application Requirements -->
                        <div>
                            <h3 class="text-md font-medium text-gray-800 mb-3">Application Requirements</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="required_documents" class="block text-sm font-medium text-gray-700 mb-1">Required Documents</label>
                                    <textarea id="required_documents" name="required_documents" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                              placeholder="List required documents, separated by commas">{{ is_array($scholarshipSettings['required_documents'] ?? null) ? implode(',', $scholarshipSettings['required_documents']) : ($scholarshipSettings['required_documents'] ?? 'Transcript of Records,Valid ID,Certificate of Enrollment,Grade Reports,Recommendation Letters') }}</textarea>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="require_entrance_exam" name="require_entrance_exam" value="1"
                                               {{ ($scholarshipSettings['require_entrance_exam'] ?? false) ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Require Entrance Examination</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="require_interview" name="require_interview" value="1"
                                               {{ ($scholarshipSettings['require_interview'] ?? false) ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Require Interview</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200">
                            Save Scholarship Parameters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="success-message" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span id="success-text">Settings saved successfully!</span>
        </div>
    </div>

    <div id="error-message" class="hidden fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span id="error-text">Error saving settings!</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Academic Calendar Form Handler
    document.getElementById('academic-calendar-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        // Show loading state
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.message);
            } else {
                showMessage('error', data.message || 'An error occurred while saving the configuration');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'An error occurred while saving the configuration');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    // Scholarship Parameters Form Handler
    document.getElementById('scholarship-parameters-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        // Show loading state
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.message);
            } else {
                showMessage('error', data.message || 'An error occurred while saving the parameters');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'An error occurred while saving the parameters');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    function showMessage(type, message) {
        const messageEl = document.getElementById(type + '-message');
        const textEl = document.getElementById(type + '-text');

        textEl.textContent = message;
        messageEl.classList.remove('hidden');

        setTimeout(() => {
            messageEl.classList.add('hidden');
        }, 5000);
    }
});
</script>
@endsection
