<!-- GSD:project-start source:PROJECT.md -->
## Project

**Pulse**

Pulse is an internal issue tracking system for hotel/DM (Departmental Manager) operations. Staff create, assign, track, and close issues related to hotel operations across departments. The system provides reporting and statistics for management oversight.

**Core Value:** **Hotel operations teams resolve issues quickly and transparently.**

If issues can be tracked, assigned, and closed efficiently — with visibility into what needs attention — the system succeeds. Everything else supports this.

### Constraints

- **Tech Stack**: Laravel + Livewire + Alpine.js + Tailwind — must stay within this ecosystem
- **Browser Support**: Modern browsers (Chrome, Edge, Safari, Firefox) — mobile and desktop
- **Performance**: Charts and dashboards must load quickly; consider server-side aggregation
- **Mobile**: Touch-friendly interfaces must work on tablets and phones
- **Offline**: No offline capability requirement (always connected)
- **Accessibility**: WCAG 2.1 AA compliance where feasible
<!-- GSD:project-end -->

<!-- GSD:stack-start source:codebase/STACK.md -->
## Technology Stack

## Languages
- PHP ^8.2 - Backend application logic and Laravel framework
- JavaScript (ES6+) - Frontend interactivity via Alpine.js
## Runtime
- PHP ^8.2
- Composer 2.x for PHP package management
- Composer (PHP)
- npm (Node.js)
- Lockfile: `composer.lock` present, `package-lock.json` present
## Frameworks
- Laravel 12.53.0 - Full-stack web application framework
- Livewire v4.2 - Full-stack framework for dynamic UIs
- PHPUnit ^11.5.3 - PHP testing framework
- Faker ^1.23 - Test data generation
- Mockery ^1.6 - Mocking framework
- Vite ^7.0.7 - Frontend build tool and dev server
- Laravel Pint ^1.24 - PHP code style fixer
- Laravel Breeze ^2.3 - Authentication scaffolding
- Laravel Sail ^1.41 - Docker development environment
- Laravel Tinker ^2.10.1 - REPL for Laravel
- Alpine.js ^3.15.8 - Lightweight JavaScript framework for interactivity
- Tailwind CSS ^3.1.0 - Utility-first CSS framework
- Tailwind Typography ^0.5.19 - Typography plugin
- Tailwind Forms ^0.5.11 - Form styling plugin
## Key Dependencies
- laravel/sanctum ^4.3 - API authentication and tokens
- barryvdh/laravel-dompdf ^3.1 - PDF generation from Blade views
- league/commonmark ^2.8 - Markdown parsing with GitHub Flavored Markdown support
- livewire/livewire ^4.2 - Dynamic UI components without building a separate API
- laravel/pail ^1.2.2 - Log monitoring
- laravel/framework ^12.0 - Core Laravel framework
- concurrently ^9.0.1 - Run multiple npm scripts simultaneously
- laravel-vite-plugin ^2.0.0 - Vite integration with Laravel
- axios ^1.11.0 - HTTP client for AJAX requests
## Configuration
- Environment variables via `.env` file
- Key configs: `config/app.php`, `config/database.php`, `config/sanctum.php`, `config/dompdf.php`, `config/livewire.php`
- Example config: `.env.example`
- `vite.config.js` - Vite configuration with Laravel plugin
- `tailwind.config.js` - Tailwind CSS customization with custom color palette
- `phpunit.xml` - PHPUnit configuration with SQLite in-memory database for testing
- `composer.json` - PHP dependencies and autoloading
- `package.json` - Node dependencies and build scripts
## Platform Requirements
- PHP ^8.2
- Composer 2.x
- Node.js + npm
- SQLite (default) or MySQL/PostgreSQL
- PHP ^8.2
- Composer dependencies installed
- Node.js + npm for asset building
- Database: SQLite, MySQL, PostgreSQL, or SQL Server
<!-- GSD:stack-end -->

<!-- GSD:conventions-start source:CONVENTIONS.md -->
## Conventions

## Naming Patterns
- PascalCase (e.g., `IssueController`, `IssueService`, `IssuePolicy`)
- Models: Singular, PascalCase (e.g., `Issue`, `User`, `Department`)
- Controllers: `{Resource}Controller` pattern (e.g., `IssueController`)
- Services: `{Resource}Service` pattern (e.g., `IssueService`)
- Policies: `{Resource}Policy` pattern (e.g., `IssuePolicy`)
- Livewire Components: PascalCase (e.g., `Index`, `Form`, `Show`)
- camelCase (e.g., `getFilteredIssues`, `closeIssue`, `toggleUserStatus`)
- Boolean/check methods: `is*()`, `has*()`, `can*()` (e.g., `isClosed()`, `hasPermission()`, `can('issues.view')`)
- Action methods: imperative verbs (e.g., `create()`, `update()`, `delete()`, `close()`, `reopen()`)
- camelCase (e.g., `$issue`, `$userId`, `$department_ids`)
- Public Livewire properties: snake_case or camelCase (e.g., `$search`, `$department_id`, `$sortField`)
- Boolean properties: `is*`, `has*` (e.g., `$isEditing`, `$isActive`, `$showResetPasswordModal`)
- UPPER_SNAKE_CASE (not commonly used, prefer config)
- Tables: plural snake_case (e.g., `issues`, `department_issue`, `permission_role`)
- Columns: snake_case (e.g., `created_at`, `assigned_to_user_id`, `issue_type_id`)
- Foreign keys: `{table}_id` (e.g., `issue_type_id`, `assigned_to_user_id`)
- Pivot tables: alphabetical, snake_case (e.g., `department_issue`, `issue_issue_type`)
- Indexes: follow column names
## Code Style
- PSR-12 coding standard (Laravel default)
- No Laravel Pint configuration detected - using defaults
- Indentation: 4 spaces (PHP standard)
- Line length: no strict limit enforced
- No ESLint/Prettier for PHP (these are for frontend)
- PHPStan or similar static analysis: Not detected
- Used on public methods in models and services
- Format: single-line description for simple methods
- Return types: always declared (PHP 8.1+ return type declarations)
## Import Organization
- Standard Laravel aliases: none custom detected
- Use fully qualified class names with `use` statements
## Error Handling
- Validation: Form Request classes with `failedValidation()` override for API JSON responses
- Authorization: Policy classes with `can()` checks in controllers
- Exceptions: Not extensively using try-catch in controllers - relies on Laravel's global handlers
## Logging
- Activity logging via custom `ActivityLog` model in services
- Database-backed audit trail for issue changes
## Comments
- Above public methods in models/services (PHPDoc)
- Complex business logic (e.g., change tracking in IssueService)
- TODO/FIXME: Not extensively found in sampled code
- Not applicable (PHP project)
## Function Design
- Controllers: Methods typically 10-30 lines
- Services: Methods 10-40 lines (business logic)
- Models: Relationship methods 1-5 lines
- Type hints always used
- Array data for complex inputs (e.g., `create(array $data)`)
- Specific parameters for simple actions (e.g., `close(Issue $issue, ?string $note = null)`)
- Always declare return types
- Models: return model instances or relationships
- Services: return models, collections, or boolean
- Controllers: return resources or JSON responses
## Module Design
- Not applicable (PHP uses namespaces and autoloading)
- Not applicable (PHP concept)
## Laravel-Specific Conventions
- Traits: `HasFactory`, `SoftDeletes`, `Notifiable`
- Mass assignment: `$fillable` property (not guarded)
- Casts: `protected function casts(): array` (PHP 8.1+ syntax)
- Relationships: typed return declarations
- API controllers: return JsonResource or JsonResponse
- Type-hinted request objects (Form Request)
- RESTful resource naming
- Schema::hasTable() checks before creating (defensive pattern)
- Foreign key constraints with constrained()
- Indexes on frequently queried columns
- Public properties: form fields, filters, UI state
- Computed properties: `#[Computed]` attribute
- Event listeners: `#[On('event-name')]` attribute
- Query string binding: `protected $queryString` property
- Property attributes: `#[Rule(['required', 'string'])]`
- Manual validation in save() methods
#[Rule(['required', 'string', 'max:255'])]
#[Rule(['required', 'in:urgent,high,medium,low'])]
- Transform models to JSON arrays
- Conditional loading with `whenLoaded()`, `whenCounted()`
- Date formatting with null-safe operators
- Business logic layer
- Database transactions with `DB::transaction()`
- Activity logging for audit trail
- Single responsibility per service
- PHP 8.1+ `definition(): array` syntax
- State modifiers for specific conditions (e.g., `open()`, `closed()`, `urgent()`)
- Relationship creation in `afterCreating()` callbacks
- Call order: dependencies first (roles before users)
- `firstOrCreate()` for idempotent seeding
- Permission synchronization to roles
<!-- GSD:conventions-end -->

<!-- GSD:architecture-start source:ARCHITECTURE.md -->
## Architecture

## Pattern Overview
- Traditional Laravel MVC foundation enhanced with Livewire for dynamic UI
- Service layer for business logic encapsulation
- Policy-based authorization with role/permission system
- API Resources for JSON transformation layer
- Activity logging pattern for audit trails
## Layers
- Purpose: Eloquent ORM models with relationships, scopes, and business methods
- Location: `app/Models/`
- Contains: Domain models (User, Issue, Department, Role, Permission, IssueComment, etc.)
- Depends on: Database tables via migrations
- Used by: Controllers, Services, Livewire components, Policies
- Purpose: Encapsulate complex business operations and cross-cutting concerns
- Location: `app/Services/`
- Contains: IssueService, ReportService, ExportService
- Depends on: Models, Facades (DB, Auth)
- Used by: Livewire components, Controllers
- Purpose: Handle HTTP requests and coordinate responses
- Location: `app/Http/Controllers/`
- Contains: Api controllers (IssueController, DepartmentController, etc.), Auth controllers
- Depends on: Requests, Resources, Models, Services
- Used by: Routes (api.php, web.php)
- Purpose: Handle dynamic frontend interactions without leaving Laravel
- Location: `app/Livewire/`
- Contains: Issues/*, Admin/*, Reports/*, Statistics/*, Graphs/*
- Depends on: Services, Models, Policies
- Used by: Web routes (web.php)
- Purpose: Centralize authorization logic separate from business logic
- Location: `app/Policies/`
- Contains: IssuePolicy, UserPolicy, DepartmentPolicy, etc.
- Depends on: User model, domain models
- Used by: Controllers, Livewire components via `authorize()` calls
- Purpose: Transform models into consistent JSON API responses
- Location: `app/Http/Resources/`
- Contains: IssueResource, UserResource, DepartmentResource, etc.
- Depends on: Models, nested Resources
- Used by: API Controllers
- Purpose: Encapsulate validation rules and authorization for requests
- Location: `app/Http/Requests/`
- Contains: Api/*/*Request.php classes
- Depends on: Validation rules
- Used by: Controllers via type-hinting
## Data Flow
- Server-side state: Eloquent models with soft deletes
- Component state: Livewire public properties (automatically synced)
- Query state: URL query string bindings for shareable filters
- Session state: Flash messages for user feedback
## Key Abstractions
- Purpose: Core domain model representing a hotel/DM issue
- Examples: `app/Models/Issue.php`, `app/Models/IssueComment.php`
- Pattern: Active Record with soft deletes, scopes (`open()`, `closed()`), state methods (`close()`, `reopen()`)
- Purpose: Hierarchical permission system
- Examples: `app/Models/Role.php`, `app/Models/Permission.php`, `app/Models/User.php`
- Pattern: Many-to-many relationships (users←→roles←→permissions), helper methods (`hasPermission()`, `hasRole()`)
- Purpose: Audit trail for all issue state changes
- Examples: `app/Models/ActivityLog.php`, usage in `IssueService::logActivity()`
- Pattern: Polymorphic relationship (`subject_type`, `subject_id`), automatic metadata capture (IP, user agent)
- Purpose: Reusable filtering logic for issues
- Examples: `IssueService::getFilteredIssues()`, `Issues\Index::getIssues()`
- Pattern: Array-based filter criteria, conditional `whereHas()` for relationships, dynamic ordering
- Purpose: Consistent API response format
- Examples: `app/Http/Resources/IssueResource.php`
- Pattern: Conditional loading (`$this->whenLoaded()`), nested resources, date formatting, relationship counts
## Entry Points
- Location: `routes/web.php`
- Triggers: Browser navigation to web routes
- Responsibilities: Route Livewire components, authentication routes, admin panel routes
- Middleware: `auth`, `verified` on authenticated routes
- Location: `routes/api.php`
- Triggers: External API consumers or internal AJAX/fetch calls
- Responsibilities: RESTful resource endpoints (issues, users, departments, roles, etc.)
- Middleware: `auth` (Sanctum tokens or session)
- Location: `routes/console.php`, `app/Console/Commands/`
- Triggers: `php artisan` commands
- Responsibilities: Scheduled tasks, custom maintenance commands
- Location: `routes/auth.php`
- Triggers: Login/register/logout flows
- Responsibilities: Laravel Breeze authentication scaffolding (email verification, password reset)
## Error Handling
- **Validation errors:** Form Request classes return 422 JSON with detailed error messages
- **Authorization errors:** Policies return false → Laravel throws `403 Forbidden`
- **Model not found:** Route model binding → `404 Not Found` automatically
- **Transaction failures:** Service layer wraps in `DB::transaction()` → automatic rollback on exception
- **Soft deletes:** Deleted models excluded from queries by default, accessible via `withTrashed()`
- Service methods log activity before success
- Failed transactions don't create partial records
- No activity log entries for failed operations
## Cross-Cutting Concerns
- Implementation: Session-based auth for web, token-based for API
- User model: `App\Models\User` extends `Authenticatable`
- Active user check: `is_active` boolean field
- Implementation: `App\Policies\*` classes checked via `authorize()` or `$user->can()`
- Permission format: Dot notation (e.g., `issues.view`, `issues.update.own`)
- Role hierarchy: SuperAdmin role has all permissions
- API: `App\Http\Requests\Api\*` classes with `rules()` method
- Livewire: `#[Rule(['required'])]` attributes on component properties
- Implementation: `ActivityLog` model with polymorphic subject
- Captured: Actor, action, description, IP address, user agent
- Not logged: Authentication attempts (handled by Laravel), read-only operations
- Eager loading: `with(['departments', 'issueTypes', 'createdBy'])`
- Indexes: Defined in migrations (status, priority, assigned_to_user_id, closed_at)
- N+1 prevention: Resource transformation checks `$this->whenLoaded()`
<!-- GSD:architecture-end -->

<!-- GSD:workflow-start source:GSD defaults -->
## GSD Workflow Enforcement

Before using Edit, Write, or other file-changing tools, start work through a GSD command so planning artifacts and execution context stay in sync.

Use these entry points:
- `/gsd:quick` for small fixes, doc updates, and ad-hoc tasks
- `/gsd:debug` for investigation and bug fixing
- `/gsd:execute-phase` for planned phase work

Do not make direct repo edits outside a GSD workflow unless the user explicitly asks to bypass it.
<!-- GSD:workflow-end -->



<!-- GSD:profile-start -->
## Developer Profile

> Profile not yet configured. Run `/gsd:profile-user` to generate your developer profile.
> This section is managed by `generate-claude-profile` -- do not edit manually.
<!-- GSD:profile-end -->
