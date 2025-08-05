/**
 * Fund Request Form Validation System
 * Implements comprehensive client-side validation for fund request forms
 */

class FundRequestValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.errors = {};
        this.isValidating = false;
        this.validationRules = {
            oneTypePerSubmission: true,
            noDuplicateApprovedRequests: true,
            requiredFields: ['request_type_id', 'amount'],
            roleBasedValidation: true
        };
        
        this.init();
    }

    init() {
        if (!this.form) {
            console.error('Fund request form not found');
            return;
        }

        this.setupEventListeners();
        this.setupRealTimeValidation();
        this.loadExistingRequests();
    }

    setupEventListeners() {
        // Form submission validation
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time field validation
        const requestTypeField = this.form.querySelector('#request_type_id');
        const amountField = this.form.querySelector('#amount');
        
        if (requestTypeField) {
            requestTypeField.addEventListener('change', () => this.validateRequestType());
            requestTypeField.addEventListener('blur', () => this.validateRequestType());
        }
        
        if (amountField) {
            amountField.addEventListener('input', () => this.validateAmount());
            amountField.addEventListener('blur', () => this.validateAmount());
        }

        // Document validation
        const documentField = this.form.querySelector('#dropzone-file');
        if (documentField) {
            documentField.addEventListener('change', () => this.validateDocument());
        }
    }

    setupRealTimeValidation() {
        // Validate on every input change with debouncing
        const inputs = this.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            let timeout;
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => this.validateField(input), 300);
            });
        });
    }

    async loadExistingRequests() {
        try {
            // Load existing requests to check for duplicates
            const response = await fetch('/scholar/fund-requests/existing-types', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            if (response.ok) {
                this.existingRequests = await response.json();
            }
        } catch (error) {
            console.warn('Could not load existing requests:', error);
        }
    }

    validateRequestType() {
        const field = this.form.querySelector('#request_type_id');
        const value = field?.value;
        
        this.clearFieldError('request_type_id');
        
        if (!value) {
            this.setFieldError('request_type_id', 'Please select a request type. You must choose exactly one type per submission.');
            return false;
        }

        // Check for duplicate active requests
        if (this.existingRequests && this.existingRequests.activeTypes) {
            const requestTypeName = field.options[field.selectedIndex]?.text;
            if (this.existingRequests.activeTypes.includes(parseInt(value))) {
                this.setFieldError('request_type_id', 
                    `You already have an active request for "${requestTypeName}". Please wait for it to be completed before submitting a new request of the same type.`);
                return false;
            }
        }

        this.setFieldSuccess('request_type_id');
        return true;
    }

    validateAmount() {
        const field = this.form.querySelector('#amount');
        const requestTypeField = this.form.querySelector('#request_type_id');
        
        this.clearFieldError('amount');
        
        if (!field?.value) {
            this.setFieldError('amount', 'Please enter the requested amount.');
            return false;
        }

        // Clean and validate amount
        const cleanAmount = field.value.replace(/[,₱\s]/g, '');
        const numericAmount = parseFloat(cleanAmount);

        if (isNaN(numericAmount)) {
            this.setFieldError('amount', 'The amount must be a valid number.');
            return false;
        }

        if (numericAmount < 1) {
            this.setFieldError('amount', 'The minimum request amount is ₱1.00.');
            return false;
        }

        if (numericAmount > 10000000) {
            this.setFieldError('amount', 'The amount exceeds the maximum allowable limit.');
            return false;
        }

        // Check decimal places
        const decimalPlaces = (cleanAmount.split('.')[1] || '').length;
        if (decimalPlaces > 2) {
            this.setFieldError('amount', 'Amount can have at most 2 decimal places.');
            return false;
        }

        // Validate against request type limits
        if (requestTypeField?.value) {
            this.validateAmountAgainstLimits(numericAmount, requestTypeField.value);
        }

        this.setFieldSuccess('amount');
        return true;
    }

    async validateAmountAgainstLimits(amount, requestTypeId) {
        try {
            const response = await fetch('/scholar/fund-requests/validate-amount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    amount: amount,
                    request_type_id: requestTypeId
                })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.error) {
                    this.setFieldError('amount', result.error);
                    return false;
                } else if (result.warning) {
                    this.setFieldWarning('amount', result.warning);
                }
            }
        } catch (error) {
            console.warn('Amount validation request failed:', error);
        }
        
        return true;
    }

    validateDocument() {
        const field = this.form.querySelector('#dropzone-file');
        const file = field?.files[0];
        
        this.clearFieldError('document');
        
        if (!file) {
            return true; // Document is optional
        }

        // File type validation
        if (file.type !== 'application/pdf') {
            this.setFieldError('document', 'Only PDF files are allowed for document uploads.');
            return false;
        }

        // File size validation (5MB)
        if (file.size > 5242880) {
            this.setFieldError('document', 'The document file size must not exceed 5MB.');
            return false;
        }

        // Minimum file size (1KB)
        if (file.size < 1024) {
            this.setFieldError('document', 'The document file appears to be too small or corrupted.');
            return false;
        }

        // File name validation
        if (file.name.length > 255) {
            this.setFieldError('document', 'The file name is too long. Please rename the file and try again.');
            return false;
        }

        // Check for suspicious characters
        if (/[<>:"|?*]/.test(file.name)) {
            this.setFieldError('document', 'The file name contains invalid characters. Please rename the file.');
            return false;
        }

        this.setFieldSuccess('document');
        return true;
    }

    validateField(field) {
        switch (field.id || field.name) {
            case 'request_type_id':
                return this.validateRequestType();
            case 'amount':
                return this.validateAmount();
            case 'dropzone-file':
            case 'document':
                return this.validateDocument();
            default:
                return true;
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (this.isValidating) {
            return;
        }

        this.isValidating = true;
        this.clearAllErrors();

        // Show loading state
        this.showLoadingState();

        try {
            // Validate all fields
            const isValid = await this.validateAllFields();
            
            if (!isValid) {
                this.showValidationErrors();
                return;
            }

            // Additional pre-submission checks
            const preSubmissionCheck = await this.performPreSubmissionValidation();
            if (!preSubmissionCheck.valid) {
                this.showError('Validation Error', preSubmissionCheck.message);
                return;
            }

            // Submit the form
            this.form.submit();
            
        } catch (error) {
            console.error('Form validation error:', error);
            this.showError('Validation Error', 'An error occurred while validating your request. Please try again.');
        } finally {
            this.isValidating = false;
            this.hideLoadingState();
        }
    }

    async validateAllFields() {
        let isValid = true;
        
        // Validate required fields
        for (const fieldName of this.validationRules.requiredFields) {
            const field = this.form.querySelector(`#${fieldName}, [name="${fieldName}"]`);
            if (!field || !this.validateField(field)) {
                isValid = false;
            }
        }

        // Validate optional fields that have values
        const optionalFields = this.form.querySelectorAll('input, select, textarea');
        for (const field of optionalFields) {
            if (field.value && !this.validationRules.requiredFields.includes(field.name)) {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            }
        }

        return isValid;
    }

    async performPreSubmissionValidation() {
        try {
            const formData = new FormData(this.form);
            const response = await fetch('/scholar/fund-requests/pre-validate', {
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

    setFieldError(fieldName, message) {
        this.errors[fieldName] = message;
        const field = this.form.querySelector(`#${fieldName}, [name="${fieldName}"]`);
        const errorContainer = this.getOrCreateErrorContainer(fieldName);
        
        if (field) {
            field.classList.remove('validation-success', 'validation-warning');
            field.classList.add('validation-error');
        }
        
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.className = 'text-red-500 text-sm mt-1 flex items-center';
            errorContainer.innerHTML = `<i class="fas fa-times-circle mr-2"></i>${message}`;
            errorContainer.style.display = 'flex';
        }
    }

    setFieldWarning(fieldName, message) {
        const field = this.form.querySelector(`#${fieldName}, [name="${fieldName}"]`);
        const warningContainer = this.getOrCreateWarningContainer(fieldName);
        
        if (field) {
            field.classList.remove('validation-success', 'validation-error');
            field.classList.add('validation-warning');
        }
        
        if (warningContainer) {
            warningContainer.textContent = message;
            warningContainer.className = 'text-yellow-600 text-sm mt-1 flex items-center';
            warningContainer.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
            warningContainer.style.display = 'flex';
        }
    }

    setFieldSuccess(fieldName) {
        delete this.errors[fieldName];
        const field = this.form.querySelector(`#${fieldName}, [name="${fieldName}"]`);
        
        if (field) {
            field.classList.remove('validation-error', 'validation-warning');
            field.classList.add('validation-success');
        }
        
        this.clearFieldError(fieldName);
    }

    clearFieldError(fieldName) {
        const errorContainer = this.form.querySelector(`#${fieldName}-error`);
        const warningContainer = this.form.querySelector(`#${fieldName}-warning`);
        
        if (errorContainer) {
            errorContainer.style.display = 'none';
        }
        if (warningContainer) {
            warningContainer.style.display = 'none';
        }
    }

    clearAllErrors() {
        this.errors = {};
        const errorContainers = this.form.querySelectorAll('[id$="-error"], [id$="-warning"]');
        errorContainers.forEach(container => {
            container.style.display = 'none';
        });
        
        const fields = this.form.querySelectorAll('.validation-error, .validation-warning, .validation-success');
        fields.forEach(field => {
            field.classList.remove('validation-error', 'validation-warning', 'validation-success');
        });
    }

    getOrCreateErrorContainer(fieldName) {
        let container = this.form.querySelector(`#${fieldName}-error`);
        if (!container) {
            container = document.createElement('div');
            container.id = `${fieldName}-error`;
            container.style.display = 'none';
            
            const field = this.form.querySelector(`#${fieldName}, [name="${fieldName}"]`);
            if (field && field.parentNode) {
                field.parentNode.insertBefore(container, field.nextSibling);
            }
        }
        return container;
    }

    getOrCreateWarningContainer(fieldName) {
        let container = this.form.querySelector(`#${fieldName}-warning`);
        if (!container) {
            container = document.createElement('div');
            container.id = `${fieldName}-warning`;
            container.style.display = 'none';
            
            const field = this.form.querySelector(`#${fieldName}, [name="${fieldName}"]`);
            if (field && field.parentNode) {
                field.parentNode.insertBefore(container, field.nextSibling);
            }
        }
        return container;
    }

    showValidationErrors() {
        const errorMessages = Object.values(this.errors);
        if (errorMessages.length > 0) {
            let errorText = 'Please correct the following errors:\n\n';
            errorMessages.forEach((error, index) => {
                errorText += `${index + 1}. ${error}\n`;
            });

            this.showError('Validation Errors', errorText);
        }
    }

    showLoadingState() {
        const submitButton = this.form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Validating...';
        }
    }

    hideLoadingState() {
        const submitButton = this.form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Request';
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
        } else {
            alert(`${title}: ${message}`);
        }
    }

    showSuccess(title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4CAF50'
            });
        } else {
            alert(`${title}: ${message}`);
        }
    }
}

// Initialize validation when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const fundRequestForm = document.getElementById('multi-step-form');
    if (fundRequestForm) {
        window.fundRequestValidator = new FundRequestValidator('multi-step-form');
    }
});