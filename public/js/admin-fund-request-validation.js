/**
 * Admin Fund Request Validation System
 * Extends the base validation for admin-specific functionality
 */

class AdminFundRequestValidator extends FundRequestValidator {
    constructor(formId) {
        super(formId);
        this.isAdmin = true;
        this.setupAdminSpecificValidation();
    }

    setupAdminSpecificValidation() {
        // Admin can create for any scholar, but still needs validation
        this.validationRules.roleBasedValidation = true;
        this.validationRules.adminCanCreateForScholars = true;
        
        // Add scholar selection if present
        const scholarField = this.form.querySelector('#scholar_profile_id');
        if (scholarField) {
            scholarField.addEventListener('change', () => this.validateScholarSelection());
            this.validationRules.requiredFields.push('scholar_profile_id');
        }
    }

    validateScholarSelection() {
        const field = this.form.querySelector('#scholar_profile_id');
        const value = field?.value;
        
        this.clearFieldError('scholar_profile_id');
        
        if (!value) {
            this.setFieldError('scholar_profile_id', 'Please select a scholar for this request.');
            return false;
        }

        this.setFieldSuccess('scholar_profile_id');
        return true;
    }

    async validateRequestType() {
        const field = this.form.querySelector('#request_type_id');
        const scholarField = this.form.querySelector('#scholar_profile_id');
        const value = field?.value;
        const scholarId = scholarField?.value;
        
        this.clearFieldError('request_type_id');
        
        if (!value) {
            this.setFieldError('request_type_id', 'Please select a request type. You must choose exactly one type per submission.');
            return false;
        }

        // For admin, check duplicates for the selected scholar
        if (scholarId) {
            try {
                const response = await fetch('/admin/fund-requests/check-duplicates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        scholar_profile_id: scholarId,
                        request_type_id: value
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.hasDuplicate) {
                        const requestType = field.options[field.selectedIndex]?.text;
                        this.setFieldError('request_type_id', 
                            `This scholar already has an active request for "${requestType}". Please wait for it to be completed before creating a new request of the same type.`);
                        return false;
                    }
                }
            } catch (error) {
                console.warn('Duplicate check failed:', error);
            }
        }

        this.setFieldSuccess('request_type_id');
        return true;
    }

    async performPreSubmissionValidation() {
        try {
            const formData = new FormData(this.form);
            const response = await fetch('/admin/fund-requests/pre-validate', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                return { valid: result.valid, message: result.message || '' };
            } else {
                const errorData = await response.json();
                return { valid: false, message: errorData.message || 'Validation failed' };
            }
        } catch (error) {
            console.error('Pre-submission validation failed:', error);
            return { valid: false, message: 'Unable to validate request. Please try again.' };
        }
    }

    showError(title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#EF4444'
            });
        } else if (typeof showValidationModal !== 'undefined') {
            showValidationModal(title, message);
        } else {
            alert(`${title}: ${message}`);
        }
    }
}

// Admin Modal Validation
class AdminModalValidator {
    constructor() {
        this.setupModalValidation();
    }

    setupModalValidation() {
        // Approve modal validation
        this.setupApproveModal();
        
        // Reject modal validation
        this.setupRejectModal();
        
        // Under review modal validation
        this.setupUnderReviewModal();
    }

    setupApproveModal() {
        const modal = document.getElementById('approve-modal');
        const form = modal?.querySelector('form');
        const submitBtn = modal?.querySelector('#approve-submit-btn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', (e) => {
                if (!this.validateApproveForm(form)) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    }

    setupRejectModal() {
        const modal = document.getElementById('reject-modal');
        const form = modal?.querySelector('form');
        const textarea = modal?.querySelector('#reject-notes');
        const submitBtn = modal?.querySelector('#reject-submit-btn');
        
        if (form && textarea && submitBtn) {
            // Real-time validation
            textarea.addEventListener('input', () => {
                this.validateRejectReason(textarea, submitBtn);
            });
            
            // Form submission validation
            form.addEventListener('submit', (e) => {
                if (!this.validateRejectForm(form)) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    }

    setupUnderReviewModal() {
        const modal = document.getElementById('under-review-modal');
        const form = modal?.querySelector('form');
        const submitBtn = modal?.querySelector('#review-submit-btn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', (e) => {
                if (!this.validateUnderReviewForm(form)) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    }

    validateApproveForm(form) {
        // Approve form is always valid (notes are optional)
        return true;
    }

    validateRejectForm(form) {
        const textarea = form.querySelector('#reject-notes');
        const reason = textarea?.value.trim();
        
        if (!reason || reason.length < 10) {
            this.showModalError('Please provide a detailed rejection reason (minimum 10 characters).');
            textarea?.focus();
            return false;
        }
        
        return true;
    }

    validateUnderReviewForm(form) {
        // Under review form is always valid (notes are optional)
        return true;
    }

    validateRejectReason(textarea, submitBtn) {
        const value = textarea.value.trim();
        
        if (value.length < 10) {
            submitBtn.disabled = true;
            textarea.style.borderColor = '#D32F2F';
            textarea.style.backgroundColor = '#FFEBEE';
        } else {
            submitBtn.disabled = false;
            textarea.style.borderColor = '#E0E0E0';
            textarea.style.backgroundColor = 'white';
        }
    }

    showModalError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Validation Error',
                text: message,
                icon: 'error',
                confirmButtonColor: '#D32F2F'
            });
        } else {
            alert(message);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin form validation
    const adminForm = document.getElementById('admin-fund-request-form');
    if (adminForm) {
        window.adminFundRequestValidator = new AdminFundRequestValidator('admin-fund-request-form');
    }
    
    // Initialize modal validation
    window.adminModalValidator = new AdminModalValidator();
    
    // Setup modal event handlers
    setupModalHandlers();
});

function setupModalHandlers() {
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
    
    // Close modals on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.add('hidden');
        }
    });
}

function closeAllModals() {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        modal.classList.add('hidden');
    });
}

// Export for global access
window.AdminFundRequestValidator = AdminFundRequestValidator;
window.AdminModalValidator = AdminModalValidator;