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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Academic Calendar Configuration -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Academic Calendar Configuration
                </h2>
            </div>
            <div class="p-6">
                <form id="academic-calendar-form">
                    @csrf
                    <div class="space-y-4">
                        <!-- Academic Year -->
                        <div>
                            <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Current Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year" value="2024-2025"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., 2024-2025">
                        </div>

                        <!-- Semester Configuration -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="first_semester_start" class="block text-sm font-medium text-gray-700 mb-1">1st Semester Start</label>
                                <input type="date" id="first_semester_start" name="first_semester_start"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="first_semester_end" class="block text-sm font-medium text-gray-700 mb-1">1st Semester End</label>
                                <input type="date" id="first_semester_end" name="first_semester_end"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="second_semester_start" class="block text-sm font-medium text-gray-700 mb-1">2nd Semester Start</label>
                                <input type="date" id="second_semester_start" name="second_semester_start"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="second_semester_end" class="block text-sm font-medium text-gray-700 mb-1">2nd Semester End</label>
                                <input type="date" id="second_semester_end" name="second_semester_end"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Summer Term -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="summer_term_start" class="block text-sm font-medium text-gray-700 mb-1">Summer Term Start</label>
                                <input type="date" id="summer_term_start" name="summer_term_start"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="summer_term_end" class="block text-sm font-medium text-gray-700 mb-1">Summer Term End</label>
                                <input type="date" id="summer_term_end" name="summer_term_end"
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
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="application_deadline_2nd" class="block text-sm font-medium text-gray-700 mb-1">2nd Semester Deadline</label>
                                    <input type="date" id="application_deadline_2nd" name="application_deadline_2nd"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                <form id="scholarship-parameters-form">
                    @csrf
                    <div class="space-y-4">
                        <!-- Funding Limits -->
                        <div class="border-b pb-4">
                            <h3 class="text-md font-medium text-gray-800 mb-3">Funding Limits</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="max_monthly_allowance" class="block text-sm font-medium text-gray-700 mb-1">Maximum Monthly Allowance (₱)</label>
                                    <input type="number" id="max_monthly_allowance" name="max_monthly_allowance" value="15000" step="100"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="max_tuition_support" class="block text-sm font-medium text-gray-700 mb-1">Maximum Tuition Support (₱)</label>
                                    <input type="number" id="max_tuition_support" name="max_tuition_support" value="50000" step="1000"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="max_research_budget" class="block text-sm font-medium text-gray-700 mb-1">Maximum Research Budget (₱)</label>
                                    <input type="number" id="max_research_budget" name="max_research_budget" value="100000" step="5000"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="max_scholarship_duration" class="block text-sm font-medium text-gray-700 mb-1">Maximum Scholarship Duration (years)</label>
                                    <input type="number" id="max_scholarship_duration" name="max_scholarship_duration" value="4" min="1" max="10"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Eligibility Criteria -->
                        <div>
                            <h3 class="text-md font-medium text-gray-800 mb-3">Eligibility Criteria</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="min_gpa" class="block text-sm font-medium text-gray-700 mb-1">Minimum GPA Requirement</label>
                                    <input type="number" id="min_gpa" name="min_gpa" value="2.5" step="0.1" min="1.0" max="4.0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="max_age_limit" class="block text-sm font-medium text-gray-700 mb-1">Maximum Age Limit</label>
                                    <input type="number" id="max_age_limit" name="max_age_limit" value="35" min="18" max="65"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="required_documents" class="block text-sm font-medium text-gray-700 mb-1">Required Documents</label>
                                    <textarea id="required_documents" name="required_documents" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                              placeholder="List required documents, one per line">Transcript of Records
Birth Certificate
Certificate of Enrollment
Research Proposal
Recommendation Letters</textarea>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="require_entrance_exam" name="require_entrance_exam"
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Require Entrance Examination</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="require_interview" name="require_interview"
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

        // Simulate form submission
        const formData = new FormData(this);

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        // Simulate API call
        setTimeout(() => {
            showMessage('success', 'Academic calendar configuration saved successfully!');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 1000);
    });

    // Scholarship Parameters Form Handler
    document.getElementById('scholarship-parameters-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Simulate form submission
        const formData = new FormData(this);

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        // Simulate API call
        setTimeout(() => {
            showMessage('success', 'Scholarship parameters saved successfully!');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 1000);
    });

    function showMessage(type, message) {
        const messageEl = document.getElementById(type + '-message');
        const textEl = document.getElementById(type + '-text');

        textEl.textContent = message;
        messageEl.classList.remove('hidden');

        setTimeout(() => {
            messageEl.classList.add('hidden');
        }, 3000);
    }
});
</script>
@endsection
