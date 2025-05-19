@extends('layouts.app')

@section('title', 'Create Fund Request')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <!-- <a href="{{ route('scholar.fund-requests') }}"
               class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                <span>Back to List</span>
            </a> -->
            <h1 class="text-2xl font-bold text-black mt-2">Create New Fund Request</h1>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Important Information</h3>
                <p class="text-sm text-blue-700">Your fund request will be reviewed by an administrator after submission.</p>
                <p class="text-sm text-blue-700 mt-1">Please note that each request type has a maximum allowable amount based on your program level (Master's or Doctoral).</p>
            </div>
            
            <form action="{{ route('scholar.fund-requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="request_type_id" class="block text-sm font-medium text-gray-700 mb-1">Request Type <span class="text-red-500">*</span></label>
                    <select id="request_type_id" name="request_type_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Request Type</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('request_type_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <div class="relative mb-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 text-base">₱</span>
                        </div>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" class="w-full border border-gray-300 rounded-md pl-7 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div id="amount-limit-info" class="text-xs text-blue-600 mb-1 font-medium h-5"></div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                


                <div class="mb-4">
                    <label for="admin_remarks" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea id="admin_remarks" name="admin_remarks" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('admin_remarks') }}</textarea>
                    @error('admin_remarks')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="dropzone-file" class="block text-sm font-medium text-gray-700 mb-1">Supporting Document <span class="text-gray-500">(Optional)</span></label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full min-h-[8rem] sm:min-h-[12rem] md:min-h-[16rem] border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-blue-50 hover:bg-blue-100 transition-colors duration-200 px-2 sm:px-6">
                            <div class="flex flex-col items-center justify-center pt-4 sm:pt-5 pb-4 sm:pb-6 w-full">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 sm:mb-4 text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm sm:text-base text-blue-500 text-center"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs sm:text-sm text-blue-400 text-center">PDF, JPG, PNG, DOC, DOCX (Max. 10MB)</p>
                            </div>
                            <input id="dropzone-file" name="document" type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        </label>
                    </div>
                    <div id="selected-file-info" class="mt-4"></div>
                    <p class="text-xs text-gray-500 mt-1">Upload supporting documents like registration forms, receipts, or other relevant files.</p>

                    
                    @error('uploaded_document_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const fileInput = document.getElementById('dropzone-file');
                    const infoDiv = document.getElementById('selected-file-info');
                    const dropdown = document.getElementById('uploaded_documents');
                    const loadingDiv = document.getElementById('dropdown-loading');

                    // Populate dropdown with existing uploaded files
                    function populateDropdown(selectedId = null) {
                        loadingDiv.classList.remove('hidden');
                        fetch('/scholar/documents/json')
                            .then(response => response.json())
                            .then(files => {
                                loadingDiv.classList.add('hidden');
                                // Remove all except first placeholder
                                while (dropdown.options.length > 1) dropdown.remove(1);
                                if (Array.isArray(files)) {
                                    files.forEach(doc => {
                                        const option = document.createElement('option');
                                        option.value = doc.id;
                                        option.textContent = doc.file_name;
                                        dropdown.appendChild(option);
                                    });
                                    if (selectedId) dropdown.value = selectedId;
                                }
                            });
                    }

                    // AJAX file upload
                    if (fileInput) {
                        fileInput.addEventListener('change', function (e) {
                            if (fileInput.files && fileInput.files[0]) {
                                const newFile = fileInput.files[0];
                                // Check for duplicate filename in dropdown
                                let duplicate = false;
                                for (let i = 1; i < dropdown.options.length; i++) {
                                    if (dropdown.options[i].textContent.trim().toLowerCase() === newFile.name.trim().toLowerCase()) {
                                        duplicate = true;
                                        break;
                                    }
                                }
                                if (duplicate) {
                                    alert('A file with this name has already been uploaded. Please rename your file or select it from the dropdown.');
                                    fileInput.value = '';
                                    infoDiv.innerHTML = '';
                                    return;
                                }
                                const formData = new FormData();
                                formData.append('document', newFile);
                                loadingDiv.classList.remove('hidden');
                                fetch('/scholar/documents/ajax-upload', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    loadingDiv.classList.add('hidden');
                                    if (data.error) {
                                        alert('Upload failed: ' + data.error);
                                        return;
                                    }
                                    // Repopulate dropdown and select new file
                                    populateDropdown(data.id);
                                })
                                .catch(() => {
                                    loadingDiv.classList.add('hidden');
                                    alert('Upload failed. Please try again.');
                                });
                            }
                        });
                    }

                    // Initial population on page load
                    populateDropdown();
                });
                </script>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Amount limit validation based on request type
                    const requestTypeSelect = document.getElementById('request_type_id');
                    const amountInput = document.getElementById('amount');
                    const amountLimitInfo = document.getElementById('amount-limit-info');
                    
                    // Define amount limits based on the entitlements table and request types
                    const requestTypeLimits = {
                        // Map request type IDs to their corresponding limits
                        // These IDs should match the ones in your database
                        // You'll need to update these based on your actual request type IDs
                        '1': { // Tuition Fee
                            'name': 'Tuition Fee',
                            'masters': 'Actual as billed',
                            'doctoral': 'Actual as billed',
                            'period': 'per semester'
                        },
                        '2': { // Living Allowance/Stipend
                            'name': 'Living Allowance/Stipend',
                            'masters': 30000,
                            'doctoral': 38000,
                            'period': 'per month'
                        },
                        '3': { // Learning Materials
                            'name': 'Learning Materials',
                            'masters': 20000,
                            'doctoral': 20000,
                            'period': 'per academic year'
                        },
                        '4': { // Thesis/Dissertation Grant
                            'name': 'Thesis/Dissertation Grant',
                            'masters': 60000,
                            'doctoral': 100000,
                            'period': 'one-time'
                        },
                        '5': { // Research Grant
                            'name': 'Research Grant',
                            'masters': 225000,
                            'doctoral': 475000,
                            'period': 'one-time'
                        },
                        '6': { // Research Dissemination
                            'name': 'Research Dissemination',
                            'masters': 75000,
                            'doctoral': 150000,
                            'period': 'one-time'
                        },
                        '7': { // Mentor's Fee
                            'name': "Mentor's Fee",
                            'masters': 36000,
                            'doctoral': 72000,
                            'period': 'one-time'
                        }
                    };
                    
                    // Get the scholar's program level (master's or doctoral)
                    let scholarProgram = 'masters'; // Default to masters
                    
                    // Check if the program contains 'doctoral' or 'phd'
                    @if(Auth::user()->scholarProfile && (stripos(Auth::user()->scholarProfile->program, 'doctoral') !== false || stripos(Auth::user()->scholarProfile->program, 'phd') !== false))
                        scholarProgram = 'doctoral';
                    @endif
                    
                    function updateAmountLimit() {
                        const requestTypeId = requestTypeSelect.value;
                        
                        if (requestTypeId && requestTypeLimits[requestTypeId]) {
                            const requestType = requestTypeLimits[requestTypeId];
                            const limit = requestType[scholarProgram];
                            const period = requestType['period'] || '';
                            const name = requestType['name'];
                            
                            if (limit === 'Actual as billed') {
                                amountLimitInfo.textContent = `${name}: Actual as billed ${period}`;
                                amountInput.removeAttribute('max');
                            } else if (limit) {
                                amountLimitInfo.textContent = `${name}: Limit ₱${limit.toLocaleString()} ${period}`;
                                amountInput.setAttribute('max', limit);
                            } else {
                                amountLimitInfo.textContent = '';
                                amountInput.removeAttribute('max');
                            }
                        } else {
                            amountLimitInfo.textContent = '';
                            amountInput.removeAttribute('max');
                        }
                    }
                    
                    // Update amount limit when request type changes
                    if (requestTypeSelect) {
                        requestTypeSelect.addEventListener('change', updateAmountLimit);
                        // Initialize on page load
                        updateAmountLimit();
                    }
                    
                    // Validate amount when form is submitted
                    const form = document.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            const requestTypeId = requestTypeSelect.value;
                            const amount = parseFloat(amountInput.value);
                            
                            if (requestTypeId && requestTypeLimits[requestTypeId] && requestTypeLimits[requestTypeId][scholarProgram] !== 'Actual as billed') {
                                const limit = requestTypeLimits[requestTypeId][scholarProgram];
                                const name = requestTypeLimits[requestTypeId]['name'];
                                
                                if (amount > limit) {
                                    e.preventDefault();
                                    alert(`The maximum amount allowed for ${name} is ₱${limit.toLocaleString()}.`);
                                    amountInput.focus();
                                }
                            }
                        });
                    }
                    
                    // File upload handling
                    const fileInput = document.getElementById('dropzone-file');
                    const infoDiv = document.getElementById('selected-file-info');
                    function getFileIcon(type) {
                        if (type.startsWith('image/')) {
                            return '<i class="fas fa-file-image text-blue-500 text-2xl mr-2"></i>';
                        } else if (type === 'application/pdf') {
                            return '<i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>';
                        } else {
                            return '<i class="fas fa-file-alt text-gray-400 text-2xl mr-2"></i>';
                        }
                    }
                    function clearFile() {
                        fileInput.value = '';
                        infoDiv.innerHTML = '';
                    }
                    if (fileInput) {
                        fileInput.addEventListener('change', function (e) {
                            infoDiv.innerHTML = '';
                            if (fileInput.files && fileInput.files[0]) {
                                const file = fileInput.files[0];
                                const card = document.createElement('div');
                                card.className = 'flex flex-col sm:flex-row items-center gap-2 sm:gap-4 bg-white border border-blue-200 rounded-lg p-2 sm:p-3 shadow transition-all duration-300 animate-fade-in w-full overflow-x-auto';
                                card.style.marginBottom = '0.5rem';
                                card.innerHTML = `
                                    <span>${getFileIcon(file.type)}</span>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-800 text-sm">${file.name}</div>
                                        <div class="text-xs text-gray-500">${(file.size/1024).toFixed(1)} KB</div>
                                    </div>
                                    <button type="button" title="Remove file" class="remove-file-btn text-gray-400 hover:text-red-500 p-1 rounded transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-300">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                                // Image preview
                                if (file.type.startsWith('image/')) {
                                    const img = document.createElement('img');
                                    img.className = 'h-16 w-16 object-cover rounded shadow border border-gray-200 ml-2';
                                    img.alt = 'Image preview';
                                    img.src = URL.createObjectURL(file);
                                    card.insertBefore(img, card.children[1]);
                                }
                                // Progress bar animation (visual feedback only)
                                const progress = document.createElement('div');
                                progress.className = 'w-full h-1 bg-blue-100 rounded overflow-hidden mt-2';
                                progress.innerHTML = '<div class="h-1 bg-blue-400 animate-progress-bar" style="width:0%"></div>';
                                setTimeout(() => {
                                    progress.firstChild.style.width = '100%';
                                }, 100);
                                card.appendChild(progress);
                                infoDiv.appendChild(card);
                                // Remove button interaction
                                card.querySelector('.remove-file-btn').onclick = clearFile;
                            }
                        });
                    }
                });
                </script>
                <style>
                @keyframes fade-in {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .animate-fade-in { animation: fade-in 0.4s ease; }
                @keyframes progress-bar {
                    from { width: 0%; }
                    to { width: 100%; }
                }
                .animate-progress-bar { animation: progress-bar 1s cubic-bezier(.4,1.7,.7,1) forwards; }
                </style>


                
                <div class="flex justify-end space-x-4">
                    <button type="submit" name="status" value="Draft" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300">
                        <i class="fas fa-save mr-2" style="color: white !important;"></i> Save as Draft
                    </button>
                    <button type="submit" name="status" value="Submitted" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <i class="fas fa-paper-plane mr-2" style="color: white !important;"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
