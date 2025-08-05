# Automatic Manuscript Creation Feature

## Overview

This feature automatically creates manuscript entries when specific types of fund requests are approved by administrators. This streamlines the workflow for scholars who receive grants that require manuscript submissions.

## Implementation Details

### Affected Request Types

The system automatically creates manuscripts for the following fund request types when approved:

1. **Thesis/Dissertation Outright Grant (Type 5)**
   - Creates an "Outline" manuscript
   - Used for thesis and dissertation outline submissions

2. **Research Dissemination Grant (Type 7)**
   - Creates a "Final" manuscript
   - Used for final research manuscript submissions

### How It Works

1. **Trigger**: When an administrator approves a fund request of type 5 or 7
2. **Check**: The system checks if a manuscript of the same type already exists for the scholar
3. **Create**: If no existing manuscript is found, a new manuscript is automatically created with:
   - Auto-generated reference number (format: MS-YYYY-NNNN)
   - Default title indicating it was auto-generated
   - Default abstract explaining the auto-creation
   - Status set to "Draft"
   - Appropriate manuscript type (Outline or Final)

### Code Changes

#### Modified Files

1. **`app/Services/FundRequestService.php`**
   - Added `Manuscript` model import
   - Modified `approveFundRequest()` method to call manuscript creation
   - Added `createManuscriptIfRequired()` private method

#### New Method: `createManuscriptIfRequired()`

```php
private function createManuscriptIfRequired(FundRequest $fundRequest): void
{
    // Check if the request type requires manuscript creation
    // Type 5: Thesis/Dissertation Outright Grant
    // Type 7: Research Dissemination Grant
    if (!in_array($fundRequest->request_type_id, [5, 7])) {
        return;
    }

    $scholarProfileId = $fundRequest->scholar_profile_id;
    $manuscriptType = $fundRequest->request_type_id === 5 ? 'Outline' : 'Final';

    // Check if manuscript already exists for this scholar and type
    $existingManuscript = Manuscript::where('scholar_profile_id', $scholarProfileId)
        ->where('manuscript_type', $manuscriptType)
        ->first();

    if ($existingManuscript) {
        // Manuscript already exists, no need to create another
        return;
    }

    // Generate reference number and create manuscript
    // ... (manuscript creation logic)
}
```

### Features

- **Duplicate Prevention**: Prevents creating multiple manuscripts of the same type for the same scholar
- **Audit Logging**: All manuscript creations are logged for audit purposes
- **Reference Number Generation**: Automatically generates unique reference numbers
- **Default Content**: Provides helpful default titles and abstracts that scholars can update

### Testing

A comprehensive test suite has been created in `tests/Feature/ManuscriptAutoCreationTest.php` that covers:

- Manuscript creation for Thesis/Dissertation grants
- Manuscript creation for Research Dissemination grants
- No manuscript creation for other request types
- Duplicate prevention functionality

### Usage

1. Scholar submits a fund request for Thesis/Dissertation Outright Grant or Research Dissemination Grant
2. Administrator reviews and approves the fund request
3. System automatically creates the appropriate manuscript entry
4. Scholar can view and edit the manuscript in the admin manuscripts section
5. Scholar updates the auto-generated title, abstract, and other details as needed

### Benefits

- **Streamlined Workflow**: Eliminates manual manuscript creation step
- **Consistency**: Ensures all approved grants have corresponding manuscripts
- **Time Saving**: Reduces administrative overhead
- **Error Prevention**: Prevents forgotten manuscript creation
- **Audit Trail**: Maintains clear connection between fund requests and manuscripts

### Future Enhancements

Potential improvements could include:

- Email notifications to scholars when manuscripts are auto-created
- Pre-population of manuscript fields with fund request details
- Integration with document upload workflows
- Customizable manuscript templates based on request type