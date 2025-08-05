<?php

namespace App\Livewire\Scholar;

use App\Models\FundRequest;
use App\Models\RequestType;
use App\Services\FundRequestService;
use App\Services\FundRequestValidationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateFundRequest extends Component
{
    use WithFileUploads;

    // Form fields
    public $request_type_id = '';
    public $amount = '';
    public $document;
    public $admin_remarks = '';

    // Validation states
    public $validationErrors = [];
    public $validationWarnings = [];
    public $isValidating = false;
    public $canSubmit = false;

    // UI states
    public $currentStep = 1;
    public $showValidationModal = false;
    public $validationModalTitle = '';
    public $validationModalContent = '';

    // Data
    public $requestTypes = [];
    public $existingRequestTypes = [];
    public $selectedRequestType = null;

    protected $rules = [
        'request_type_id' => 'required|exists:request_types,id',
        'amount' => 'required|numeric|min:0.01|max:10000000',
        'document' => 'nullable|file|mimes:pdf|max:5120',
        'admin_remarks' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'request_type_id.required' => 'Please select a request type. You must choose exactly one type per submission.',
        'request_type_id.exists' => 'The selected request type is invalid or no longer available.',
        'amount.required' => 'Please enter the requested amount.',
        'amount.numeric' => 'The amount must be a valid number.',
        'amount.min' => 'The minimum request amount is ₱1.00.',
        'amount.max' => 'The amount exceeds the maximum allowable limit.',
        'document.mimes' => 'Only PDF files are allowed for document uploads.',
        'document.max' => 'The document file size must not exceed 5MB.',
    ];

    public function mount()
    {
        $this->loadRequestTypes();
        $this->loadExistingRequestTypes();
    }

    public function loadRequestTypes()
    {
        $this->requestTypes = RequestType::getRequestableTypes();
    }

    public function loadExistingRequestTypes()
    {
        $user = Auth::user();
        if ($user && $user->scholarProfile) {
            $this->existingRequestTypes = FundRequest::where('scholar_profile_id', $user->scholarProfile->id)
                ->whereIn('status', [
                    FundRequest::STATUS_SUBMITTED,
                    FundRequest::STATUS_UNDER_REVIEW,
                    FundRequest::STATUS_APPROVED
                ])
                ->pluck('request_type_id')
                ->toArray();
        }
    }

    public function updatedRequestTypeId($value)
    {
        $this->selectedRequestType = RequestType::find($value);
        $this->validateRequestType();
        $this->validateAmount(); // Re-validate amount when type changes
    }

    public function updatedAmount($value)
    {
        $this->validateAmount();
    }

    public function updatedDocument($value)
    {
        $this->validateDocument();
    }

    public function validateRequestType()
    {
        $this->clearFieldValidation('request_type_id');

        if (!$this->request_type_id) {
            $this->setFieldError('request_type_id', 'Please select a request type. You must choose exactly one type per submission.');
            return false;
        }

        // Check for duplicate active requests
        if (in_array($this->request_type_id, $this->existingRequestTypes)) {
            $requestType = RequestType::find($this->request_type_id);
            $typeName = $requestType ? $requestType->name : 'this type';
            $this->setFieldError('request_type_id', "You already have an active request for \"{$typeName}\". Please wait for it to be completed before submitting a new request of the same type.");
            return false;
        }

        $this->setFieldSuccess('request_type_id');
        return true;
    }

    public function validateAmount()
    {
        $this->clearFieldValidation('amount');

        if (!$this->amount) {
            $this->setFieldError('amount', 'Please enter the requested amount.');
            return false;
        }

        // Clean amount
        $cleanAmount = str_replace([',', '₱', ' '], '', $this->amount);
        
        if (!is_numeric($cleanAmount)) {
            $this->setFieldError('amount', 'The amount must be a valid number.');
            return false;
        }

        $numericAmount = floatval($cleanAmount);

        if ($numericAmount < 1) {
            $this->setFieldError('amount', 'The minimum request amount is ₱1.00.');
            return false;
        }

        if ($numericAmount > 10000000) {
            $this->setFieldError('amount', 'The amount exceeds the maximum allowable limit.');
            return false;
        }

        // Validate against request type limits
        if ($this->request_type_id) {
            $user = Auth::user();
            if ($user && $user->scholarProfile) {
                $validationService = app(FundRequestService::class);
                $limitValidation = $validationService->validateFundRequestAmount(
                    $this->request_type_id,
                    $numericAmount,
                    $user->scholarProfile->intended_degree
                );

                if ($limitValidation && isset($limitValidation['amount'])) {
                    $this->setFieldError('amount', $limitValidation['amount']);
                    return false;
                }
            }
        }

        $this->setFieldSuccess('amount');
        return true;
    }

    public function validateDocument()
    {
        $this->clearFieldValidation('document');

        if (!$this->document) {
            return true; // Document is optional
        }

        // File type validation
        if ($this->document->getMimeType() !== 'application/pdf') {
            $this->setFieldError('document', 'Only PDF files are allowed for document uploads.');
            return false;
        }

        // File size validation (5MB)
        if ($this->document->getSize() > 5242880) {
            $this->setFieldError('document', 'The document file size must not exceed 5MB.');
            return false;
        }

        // Minimum file size (1KB)
        if ($this->document->getSize() < 1024) {
            $this->setFieldError('document', 'The document file appears to be too small or corrupted.');
            return false;
        }

        $this->setFieldSuccess('document');
        return true;
    }

    public function validateAllFields()
    {
        $isValid = true;

        if (!$this->validateRequestType()) {
            $isValid = false;
        }

        if (!$this->validateAmount()) {
            $isValid = false;
        }

        if (!$this->validateDocument()) {
            $isValid = false;
        }

        $this->canSubmit = $isValid;
        return $isValid;
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            if ($this->validateRequestType() && $this->validateAmount()) {
                $this->currentStep = 2;
            }
        } elseif ($this->currentStep === 2) {
            if ($this->validateDocument()) {
                $this->currentStep = 3;
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit()
    {
        $this->isValidating = true;

        try {
            // Final validation
            if (!$this->validateAllFields()) {
                $this->showValidationErrors();
                return;
            }

            // Use validation service for comprehensive check
            $user = Auth::user();
            if (!$user || !$user->scholarProfile) {
                $this->showError('Error', 'Scholar profile not found. Please complete your profile setup first.');
                return;
            }

            $validationService = app(FundRequestValidationService::class);
            $validationResult = $validationService->validateFundRequestCreation([
                'request_type_id' => $this->request_type_id,
                'amount' => $this->amount,
                'document' => $this->document,
                'admin_remarks' => $this->admin_remarks,
            ], $user->scholarProfile->id);

            if (!$validationResult['valid']) {
                $this->showError('Validation Error', $validationService->formatErrorMessages($validationResult['errors']));
                return;
            }

            // Create the fund request
            $fundRequestService = app(FundRequestService::class);
            $fundRequest = $fundRequestService->createFundRequest([
                'request_type_id' => $this->request_type_id,
                'amount' => str_replace([',', '₱', ' '], '', $this->amount),
                'admin_remarks' => $this->admin_remarks,
                'status' => FundRequest::STATUS_SUBMITTED,
            ], $user->scholarProfile->id, request());

            // Handle document upload if provided
            if ($this->document) {
                // Document handling is done in the service
            }

            session()->flash('success', 'Fund request submitted successfully. Your request will be reviewed by an administrator.');
            return redirect()->route('scholar.fund-requests.show', $fundRequest->id);

        } catch (\Exception $e) {
            $this->showError('Submission Error', 'Failed to submit fund request: ' . $e->getMessage());
        } finally {
            $this->isValidating = false;
        }
    }

    private function setFieldError($field, $message)
    {
        $this->validationErrors[$field] = $message;
        $this->canSubmit = false;
    }

    private function setFieldWarning($field, $message)
    {
        $this->validationWarnings[$field] = $message;
    }

    private function setFieldSuccess($field)
    {
        unset($this->validationErrors[$field]);
        unset($this->validationWarnings[$field]);
    }

    private function clearFieldValidation($field)
    {
        unset($this->validationErrors[$field]);
        unset($this->validationWarnings[$field]);
    }

    private function showValidationErrors()
    {
        $errors = array_values($this->validationErrors);
        if (!empty($errors)) {
            $errorText = "Please correct the following errors:\n\n";
            foreach ($errors as $index => $error) {
                $errorText .= ($index + 1) . ". " . $error . "\n";
            }
            $this->showError('Validation Errors', $errorText);
        }
    }

    private function showError($title, $message)
    {
        $this->validationModalTitle = $title;
        $this->validationModalContent = $message;
        $this->showValidationModal = true;
    }

    public function closeValidationModal()
    {
        $this->showValidationModal = false;
    }

    public function render()
    {
        return view('livewire.scholar.create-fund-request');
    }
}