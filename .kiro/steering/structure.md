---
inclusion: always
---

# Project Structure & Architecture

## Laravel Application Structure

### Core Application (`app/`)
- **Actions/** - Single-purpose action classes for complex operations
- **Http/Controllers/** - HTTP request handlers, keep thin with business logic in services
- **Livewire/** - Dynamic UI components, organize by feature domain
- **Models/** - Eloquent models with relationships, use singular nouns
- **Repositories/** - Data access layer, abstract database operations
- **Services/** - Business logic layer, handle complex workflows
- **Policies/** - Authorization logic, one policy per model

### Frontend Organization (`resources/`)
- **views/layouts/** - Base templates with consistent structure
- **views/livewire/** - Component templates matching `app/Livewire/` structure
- **views/{feature}/** - Feature-specific Blade templates
- **css/** - Tailwind CSS with component-based organization
- **js/** - Minimal JavaScript, prefer Livewire for interactivity

## Architectural Patterns

### Repository-Service Pattern
```php
// Repository handles data access
class ScholarRepository {
    public function findActiveScholars(): Collection;
}

// Service handles business logic
class ScholarService {
    public function processApplication(array $data): Scholar;
}

// Controller coordinates
class ScholarController {
    public function store(Request $request, ScholarService $service);
}
```

### Livewire Component Structure
- One component per distinct UI interaction
- Keep components focused on single responsibility
- Use events for component communication
- Place complex logic in services, not components

## Naming Conventions

### PHP Classes
- **Controllers**: `{Domain}Controller` (e.g., `ScholarController`)
- **Models**: Singular, PascalCase (e.g., `ScholarProfile`, `FundRequest`)
- **Livewire**: Descriptive action (e.g., `CreateFundRequest`, `ScholarDashboard`)
- **Services**: `{Domain}Service` (e.g., `FundRequestService`)
- **Repositories**: `{Model}Repository` (e.g., `ScholarRepository`)

### Database & Files
- **Tables**: Plural, snake_case (e.g., `scholar_profiles`, `fund_requests`)
- **Migrations**: `{timestamp}_create_{table}_table.php`
- **Blade Templates**: kebab-case (e.g., `fund-request-form.blade.php`)

## Code Organization Rules

### Controller Guidelines
- Keep controllers thin, delegate to services
- Use form requests for validation
- Return consistent response formats
- Handle only HTTP concerns

### Model Relationships
- Define relationships explicitly with return types
- Use consistent naming for foreign keys
- Implement soft deletes for audit trails
- Add model observers for complex lifecycle events

### Livewire Best Practices
- Use `#[Rule]` attributes for validation
- Implement real-time validation for better UX
- Keep component state minimal
- Use computed properties for derived data

### Service Layer Patterns
- One service per domain aggregate
- Return DTOs or models, not arrays
- Handle transactions within services
- Throw domain-specific exceptions

## Feature Development Workflow

1. **Database First**: Create migration and model
2. **Repository Layer**: Abstract data access
3. **Service Layer**: Implement business logic
4. **Controller/Livewire**: Handle user interactions
5. **Templates**: Create responsive UI
6. **Tests**: Write feature and unit tests
7. **Documentation**: Update relevant docs

## Security & Data Handling

### File Organization
- Sensitive operations in dedicated services
- Audit trails through model observers
- File uploads handled through dedicated controllers
- Configuration centralized in `config/` directory

### Testing Structure
- **Feature tests**: End-to-end user workflows
- **Unit tests**: Individual class behavior
- **Database tests**: Use factories and seeders
- Organize tests by domain, not by type