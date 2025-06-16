@extends('layouts.app')

@section('title', 'Data Management')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Data Management</h1>
        <a href="{{ route('super_admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Database Backup & Restore -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                    Database Backup & Restore
                </h2>
            </div>
            <div class="p-6">
                <!-- Database Backup Section -->
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Create Backup</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="backup_name" class="block text-sm font-medium text-gray-700 mb-1">Backup Name</label>
                            <input type="text" id="backup_name" name="backup_name"
                                   value="backup_{{ date('Y-m-d_H-i-s') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Backup Options</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="include_users" name="backup_options[]" value="users" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Include User Data</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="include_scholars" name="backup_options[]" value="scholars" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Include Scholar Data</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="include_applications" name="backup_options[]" value="applications" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Include Applications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="include_reports" name="backup_options[]" value="reports" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Include Reports</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="include_settings" name="backup_options[]" value="settings" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Include System Settings</span>
                                </label>
                            </div>
                        </div>

                        <button type="button" id="create-backup-btn"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Create Backup
                        </button>
                    </div>
                </div>

                <!-- Database Restore Section -->
                <div class="border-t pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Restore Database</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="backup_file" class="block text-sm font-medium text-gray-700 mb-1">Select Backup File</label>
                            <input type="file" id="backup_file" name="backup_file" accept=".sql,.zip"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800">Warning</h4>
                                    <p class="text-sm text-yellow-700 mt-1">Restoring a backup will overwrite all current data. This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="restore-backup-btn"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Restore Database
                        </button>
                    </div>
                </div>

                <!-- Recent Backups -->
                <div class="border-t pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Recent Backups</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded text-sm">
                            <div>
                                <span class="font-medium">backup_2024-01-15_14-30-25.sql</span>
                                <span class="text-gray-500 ml-2">2.5 MB</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800">Download</button>
                                <button class="text-red-600 hover:text-red-800">Delete</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded text-sm">
                            <div>
                                <span class="font-medium">backup_2024-01-14_09-15-10.sql</span>
                                <span class="text-gray-500 ml-2">2.3 MB</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800">Download</button>
                                <button class="text-red-600 hover:text-red-800">Delete</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded text-sm">
                            <div>
                                <span class="font-medium">backup_2024-01-13_16-45-33.sql</span>
                                <span class="text-gray-500 ml-2">2.1 MB</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800">Download</button>
                                <button class="text-red-600 hover:text-red-800">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Import & Export -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                    Data Import & Export
                </h2>
            </div>
            <div class="p-6">
                <!-- Data Export Section -->
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Export Data</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Data to Export</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="export_users" name="export_options[]" value="users"
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">User Data</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="export_scholars" name="export_options[]" value="scholars"
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Scholar Profiles</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="export_applications" name="export_options[]" value="applications"
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Applications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="export_reports" name="export_options[]" value="reports"
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Reports & Analytics</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="export_format" class="block text-sm font-medium text-gray-700 mb-1">Export Format</label>
                            <select id="export_format" name="export_format"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                <option value="csv">CSV (Comma Separated Values)</option>
                                <option value="xlsx">Excel (XLSX)</option>
                                <option value="json">JSON</option>
                                <option value="xml">XML</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range (Optional)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" id="export_date_from" name="export_date_from"
                                       class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                       placeholder="From">
                                <input type="date" id="export_date_to" name="export_date_to"
                                       class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                       placeholder="To">
                            </div>
                        </div>

                        <button type="button" id="export-data-btn"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Data
                        </button>
                    </div>
                </div>

                <!-- Data Import Section -->
                <div class="border-t pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Import Data</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="import_type" class="block text-sm font-medium text-gray-700 mb-1">Import Type</label>
                            <select id="import_type" name="import_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                <option value="">Select import type...</option>
                                <option value="users">User Data</option>
                                <option value="scholars">Scholar Profiles</option>
                                <option value="applications">Applications</option>
                            </select>
                        </div>

                        <div>
                            <label for="import_file" class="block text-sm font-medium text-gray-700 mb-1">Select File</label>
                            <input type="file" id="import_file" name="import_file" accept=".csv,.xlsx,.json,.xml"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="validate_before_import" name="validate_before_import" checked
                                       class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Validate data before importing</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="skip_duplicates" name="skip_duplicates" checked
                                       class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Skip duplicate records</span>
                            </label>
                        </div>

                        <button type="button" id="import-data-btn"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Import Data
                        </button>
                    </div>
                </div>

                <!-- Import/Export History -->
                <div class="border-t pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Recent Operations</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        <div class="flex items-center justify-between p-2 bg-green-50 rounded text-sm">
                            <div>
                                <span class="font-medium text-green-800">Export: Scholar Data</span>
                                <span class="text-green-600 ml-2">Success</span>
                            </div>
                            <span class="text-xs text-gray-500">2 hours ago</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-blue-50 rounded text-sm">
                            <div>
                                <span class="font-medium text-blue-800">Import: User Data</span>
                                <span class="text-blue-600 ml-2">Success (150 records)</span>
                            </div>
                            <span class="text-xs text-gray-500">1 day ago</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-red-50 rounded text-sm">
                            <div>
                                <span class="font-medium text-red-800">Import: Applications</span>
                                <span class="text-red-600 ml-2">Failed (validation errors)</span>
                            </div>
                            <span class="text-xs text-gray-500">2 days ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Modal -->
    <div id="progress-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="progress-title">Processing...</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="progress-message">Please wait while we process your request.</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" id="progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="success-message" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span id="success-text">Operation completed successfully!</span>
        </div>
    </div>

    <div id="error-message" class="hidden fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span id="error-text">Operation failed!</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create Backup Handler
    document.getElementById('create-backup-btn').addEventListener('click', function() {
        const backupName = document.getElementById('backup_name').value;
        const selectedOptions = Array.from(document.querySelectorAll('input[name="backup_options[]"]:checked')).map(cb => cb.value);

        if (selectedOptions.length === 0) {
            showMessage('error', 'Please select at least one backup option.');
            return;
        }

        showProgressModal('Creating Backup', 'Preparing database backup...');
        simulateProgress(() => {
            hideProgressModal();
            showMessage('success', `Backup "${backupName}" created successfully!`);
        });
    });

    // Restore Backup Handler
    document.getElementById('restore-backup-btn').addEventListener('click', function() {
        const backupFile = document.getElementById('backup_file').files[0];

        if (!backupFile) {
            showMessage('error', 'Please select a backup file to restore.');
            return;
        }

        if (confirm('Are you sure you want to restore this backup? This will overwrite all current data and cannot be undone.')) {
            showProgressModal('Restoring Database', 'Restoring from backup file...');
            simulateProgress(() => {
                hideProgressModal();
                showMessage('success', 'Database restored successfully!');
            });
        }
    });

    // Export Data Handler
    document.getElementById('export-data-btn').addEventListener('click', function() {
        const selectedOptions = Array.from(document.querySelectorAll('input[name="export_options[]"]:checked')).map(cb => cb.value);
        const format = document.getElementById('export_format').value;

        if (selectedOptions.length === 0) {
            showMessage('error', 'Please select at least one data type to export.');
            return;
        }

        showProgressModal('Exporting Data', `Preparing ${format.toUpperCase()} export...`);
        simulateProgress(() => {
            hideProgressModal();
            showMessage('success', `Data exported successfully as ${format.toUpperCase()}!`);
        });
    });

    // Import Data Handler
    document.getElementById('import-data-btn').addEventListener('click', function() {
        const importType = document.getElementById('import_type').value;
        const importFile = document.getElementById('import_file').files[0];

        if (!importType) {
            showMessage('error', 'Please select an import type.');
            return;
        }

        if (!importFile) {
            showMessage('error', 'Please select a file to import.');
            return;
        }

        showProgressModal('Importing Data', 'Validating and importing data...');
        simulateProgress(() => {
            hideProgressModal();
            showMessage('success', `${importType} data imported successfully!`);
        });
    });

    function showProgressModal(title, message) {
        document.getElementById('progress-title').textContent = title;
        document.getElementById('progress-message').textContent = message;
        document.getElementById('progress-modal').classList.remove('hidden');
    }

    function hideProgressModal() {
        document.getElementById('progress-modal').classList.add('hidden');
        document.getElementById('progress-bar').style.width = '0%';
    }

    function simulateProgress(callback) {
        let progress = 0;
        const progressBar = document.getElementById('progress-bar');

        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(callback, 500);
            }
            progressBar.style.width = progress + '%';
        }, 200);
    }

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
