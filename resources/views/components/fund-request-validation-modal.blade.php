<!-- Fund Request Validation Modal -->
<div id="validation-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="validation-modal-title">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-xl border border-gray-200">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 bg-red-500">
            <h3 id="validation-modal-title" class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Validation Error
            </h3>
            <button type="button" id="close-validation-modal" class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close modal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="px-6 py-4">
            <div id="validation-modal-content" class="text-gray-800">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
        <div class="flex justify-end gap-2 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
            <button type="button" id="validation-modal-ok" class="px-6 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                <i class="fas fa-check mr-2"></i> OK
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="success-modal-title">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-xl border border-gray-200">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 bg-green-500">
            <h3 id="success-modal-title" class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Success
            </h3>
            <button type="button" id="close-success-modal" class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close modal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="px-6 py-4">
            <div id="success-modal-content" class="text-gray-800">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
        <div class="flex justify-end gap-2 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
            <button type="button" id="success-modal-ok" class="px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                <i class="fas fa-check mr-2"></i> OK
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation Modal handlers
    const validationModal = document.getElementById('validation-modal');
    const closeValidationModal = document.getElementById('close-validation-modal');
    const validationModalOk = document.getElementById('validation-modal-ok');
    
    function showValidationModal(title, content) {
        document.getElementById('validation-modal-title').innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${title}`;
        document.getElementById('validation-modal-content').innerHTML = content;
        validationModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function hideValidationModal() {
        validationModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    closeValidationModal?.addEventListener('click', hideValidationModal);
    validationModalOk?.addEventListener('click', hideValidationModal);
    
    // Success Modal handlers
    const successModal = document.getElementById('success-modal');
    const closeSuccessModal = document.getElementById('close-success-modal');
    const successModalOk = document.getElementById('success-modal-ok');
    
    function showSuccessModal(title, content) {
        document.getElementById('success-modal-title').innerHTML = `<i class="fas fa-check-circle mr-2"></i>${title}`;
        document.getElementById('success-modal-content').innerHTML = content;
        successModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function hideSuccessModal() {
        successModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    closeSuccessModal?.addEventListener('click', hideSuccessModal);
    successModalOk?.addEventListener('click', hideSuccessModal);
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideValidationModal();
            hideSuccessModal();
        }
    });
    
    // Close modals on backdrop click
    validationModal?.addEventListener('click', function(e) {
        if (e.target === validationModal) {
            hideValidationModal();
        }
    });
    
    successModal?.addEventListener('click', function(e) {
        if (e.target === successModal) {
            hideSuccessModal();
        }
    });
    
    // Make functions globally available
    window.showValidationModal = showValidationModal;
    window.hideValidationModal = hideValidationModal;
    window.showSuccessModal = showSuccessModal;
    window.hideSuccessModal = hideSuccessModal;
});
</script>