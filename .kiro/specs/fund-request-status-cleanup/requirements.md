# Requirements Document

## Introduction

This feature addresses the redundancy and inconsistency issues in the fund request status system. Currently, there are mismatched status values between the database schema, UI dropdowns, and business logic. The system needs to be streamlined to use a clear, consistent set of statuses that accurately reflect the fund request lifecycle.

## Requirements

### Requirement 1

**User Story:** As a system administrator, I want consistent fund request statuses across the database and UI, so that there are no data integrity issues or user confusion.

#### Acceptance Criteria

1. WHEN the system is updated THEN the database SHALL only contain valid status values: 'Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Completed'
2. WHEN a user views the status dropdown THEN the system SHALL display only the valid statuses without duplicates
3. WHEN the database migration runs THEN the system SHALL remove 'Disbursed' status and replace it with 'Completed'
4. IF a fund request has 'Disbursed' status THEN the system SHALL convert it to 'Completed'
5. WHEN the system validates statuses THEN it SHALL reject any 'Pending' status as it doesn't exist in the database schema

### Requirement 2

**User Story:** As a scholar, I want to see clear status progression for my fund requests, so that I understand where my request stands in the approval process.

#### Acceptance Criteria

1. WHEN a scholar creates a fund request THEN the system SHALL set the initial status to 'Draft'
2. WHEN a scholar submits a draft request THEN the system SHALL change the status to 'Submitted'
3. WHEN an admin starts reviewing a submitted request THEN the system SHALL allow changing status to 'Under Review'
4. WHEN an admin completes review THEN the system SHALL allow status changes to 'Approved' or 'Rejected'
5. WHEN a request is approved and funds are disbursed THEN the system SHALL change the status to 'Completed'
6. WHEN displaying statuses THEN the system SHALL show them in logical order: Draft, Submitted, Under Review, Approved, Rejected, Completed

### Requirement 3

**User Story:** As an administrator, I want to filter fund requests by status without seeing redundant or invalid options, so that I can efficiently manage requests.

#### Acceptance Criteria

1. WHEN an admin opens the status filter dropdown THEN the system SHALL display "All Statuses" as the header without duplicating it in the list
2. WHEN filtering by status THEN the system SHALL only show requests matching the exact status selected
3. WHEN the "All Statuses" option is selected THEN the system SHALL display requests of all valid statuses
4. WHEN displaying the dropdown THEN the system SHALL remove the redundant "Pending" status that doesn't exist in the database

### Requirement 4

**User Story:** As a developer, I want the codebase to use consistent status values, so that there are no runtime errors or logical inconsistencies.

#### Acceptance Criteria

1. WHEN the service layer processes status changes THEN the system SHALL only use the six valid status values
2. WHEN notifications are sent THEN the system SHALL reference the correct status names
3. WHEN status history is recorded THEN the system SHALL log the standardized status values
4. WHEN validation occurs THEN the system SHALL reject any attempts to set invalid status values
5. WHEN the system starts up THEN all existing invalid status references SHALL be identified and flagged for correction