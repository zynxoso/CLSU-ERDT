```mermaid
erDiagram
    USERS {
        id int PK
        name string
        email string
        email_verified_at timestamp
        password string
        role enum
        is_active boolean
        remember_token string
        last_login_at timestamp
    }
    
    SCHOLAR_PROFILES {
        id int PK
        user_id int FK
        first_name string
        middle_name string
        last_name string
        contact_number string
        address text
        city string
        province string
        university string
        program string
        department string
        status enum
        start_date date
        expected_completion_date date
        actual_completion_date date
        bachelor_degree string
        bachelor_university string
        bachelor_graduation_year int
        research_area string
        notes text
    }
    
    FUND_REQUESTS {
        id int PK
        scholar_profile_id int FK
        request_type_id int FK
        amount decimal
        purpose text
        status enum
        admin_remarks text
        admin_notes text
        reviewed_by int FK
        reviewed_at timestamp
    }
    
    REQUEST_TYPES {
        id int PK
        name string
        description text
    }
    
    DISBURSEMENTS {
        id int PK
        fund_request_id int FK
        reference_number string
        amount decimal
        disbursement_date date
        status enum
        processed_by int FK
        remarks text
    }
    
    MANUSCRIPTS {
        id int PK
        scholar_profile_id int FK
        reference_number string
        title string
        abstract text
        manuscript_type enum
        co_authors string
        keywords string
        status enum
        admin_notes text
        reviewed_by int FK
        reviewed_at timestamp
    }
    
    DOCUMENTS {
        id int PK
        scholar_profile_id int FK
        fund_request_id int FK
        manuscript_id int FK
        file_name string
        file_path string
        file_type string
        file_size int
        category string
        description text
        status enum
        is_verified boolean
        verified_by int FK
        verified_at timestamp
    }
    
    AUDIT_LOGS {
        id int PK
        user_id int FK
        entity_type string
        entity_id int
        action string
        description text
        old_values json
        new_values json
    }
    
    NOTIFICATIONS {
        id int PK
        type string
        notifiable_type string
        notifiable_id int
        data json
        read_at timestamp
    }
    
    USERS ||--o{ SCHOLAR_PROFILES : has
    SCHOLAR_PROFILES ||--o{ FUND_REQUESTS : submits
    SCHOLAR_PROFILES ||--o{ MANUSCRIPTS : submits
    SCHOLAR_PROFILES ||--o{ DOCUMENTS : uploads
    FUND_REQUESTS }o--|| REQUEST_TYPES : categorized_by
    FUND_REQUESTS ||--o{ DISBURSEMENTS : results_in
    FUND_REQUESTS ||--o{ DOCUMENTS : includes
    MANUSCRIPTS ||--o{ DOCUMENTS : includes
    USERS ||--o{ AUDIT_LOGS : generates
    USERS ||--o{ FUND_REQUESTS : reviews
    USERS ||--o{ MANUSCRIPTS : reviews
    USERS ||--o{ DOCUMENTS : verifies
    USERS ||--o{ DISBURSEMENTS : processes
```

# CLSU-ERDT Scholar Management System - Entity-Relationship Diagram

The ERD above illustrates the database structure for the CLSU-ERDT Scholar Management System, showing the key entities and their relationships:

## Key Entities:

1. **Users**: Base authentication table with role-based access (admin/scholar)
2. **Scholar Profiles**: Detailed information about scholars linked to user accounts
3. **Fund Requests**: Scholar-submitted requests for financial support
4. **Request Types**: Categories of fund requests
5. **Disbursements**: Financial transactions related to approved fund requests
6. **Manuscripts**: Research documents submitted by scholars
7. **Documents**: Uploaded files associated with profiles, requests, or manuscripts
8. **Audit Logs**: System-wide activity tracking
9. **Notifications**: System notifications for users

## Key Relationships:

- Each User can have one Scholar Profile (for scholars)
- Scholar Profiles can submit multiple Fund Requests and Manuscripts
- Fund Requests are categorized by Request Types
- Approved Fund Requests result in Disbursements
- Documents can be linked to Scholar Profiles, Fund Requests, or Manuscripts
- Users (admins) review Fund Requests, Manuscripts, and verify Documents
- Users (admins) process Disbursements
- All significant actions are recorded in Audit Logs 
