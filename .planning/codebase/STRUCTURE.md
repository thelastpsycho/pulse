# Codebase Structure

**Analysis Date:** 2026-03-28

## Directory Layout

```
pulse/                          # Project root
├── app/                         # Application core code
│   ├── Console/                # Artisan commands
│   │   └── Commands/           # Custom command classes
│   ├── Http/                   # HTTP layer
│   │   ├── Controllers/        # Request handlers
│   │   │   ├── Api/            # RESTful API controllers
│   │   │   └── Auth/           # Authentication controllers (Breeze)
│   │   ├── Requests/           # Form request validation
│   │   │   ├── Api/            # API-specific request validators
│   │   │   │   ├── Department/
│   │   │   │   ├── Issue/
│   │   │   │   ├── IssueCategory/
│   │   │   │   ├── IssueComment/
│   │   │   │   ├── IssueType/
│   │   │   │   ├── Role/
│   │   │   │   ├── SavedFilter/
│   │   │   │   ├── User/
│   │   │   │   └── DmLogBook/
│   │   │   └── Auth/           # Authentication request validators
│   │   └── Resources/          # API JSON transformers
│   ├── Livewire/               # Livewire components
│   │   ├── Admin/              # Admin panel components
│   │   │   ├── Departments/    # Department CRUD
│   │   │   ├── IssueTypes/     # Issue type CRUD
│   │   │   ├── Roles/          # Role management
│   │   │   └── Users/          # User management
│   │   ├── Graphs/             # Chart/visualization components
│   │   ├── Issues/             # Issue management components
│   │   ├── Reports/            # Report generation components
│   │   └── Statistics/         # Statistics dashboard
│   ├── Models/                 # Eloquent models
│   ├── Policies/               # Authorization policies
│   ├── Providers/              # Service providers
│   ├── Services/               # Business logic services
│   └── View/                   # View components
│       └── Components/         # Blade class components
├── bootstrap/                  # Framework bootstrap files
├── config/                     # Configuration files
├── database/                   # Database layer
│   ├── factories/              # Model factories for testing
│   ├── migrations/             # Database schema migrations
│   └── seeders/                # Database seeders
├── public/                     # Public web root
├── resources/                  # Frontend resources
│   ├── css/                    # Stylesheets (Tailwind)
│   ├── js/                     # JavaScript (Alpine.js)
│   └── views/                  # Blade templates
│       ├── auth/               # Authentication views (Breeze)
│       ├── components/         # Reusable Blade components
│       ├── exports/            # Export templates (PDF/CSV)
│       ├── layouts/            # Layout templates
│       ├── livewire/           # Livewire component views
│       │   ├── admin/          # Admin component views
│       │   ├── graphs/         # Graph component views
│       │   ├── issues/         # Issue component views
│       │   ├── reports/        # Report component views
│       │   └── statistics/     # Statistics component views
│       └── profile/            # User profile views
├── routes/                     # Route definitions
│   ├── api.php                 # API routes
│   ├── auth.php                # Authentication routes
│   ├── console.php             # CLI routes
│   └── web.php                 # Web routes
├── storage/                    # Application storage
├── tests/                      # Test files
│   ├── Feature/                # Feature/Integration tests
│   │   └── Auth/               # Authentication tests
│   └── Unit/                   # Unit tests
├── vendor/                     # Composer dependencies
├── .env                        # Environment configuration (not in git)
├── .env.example                # Environment template
├── artisan                     # CLI entry point
├── composer.json               # PHP dependencies
├── package.json                # NPM dependencies
├── phpunit.xml                 # PHPUnit configuration
├── tailwind.config.js          # Tailwind CSS configuration
└── vite.config.js              # Vite build configuration
```

## Directory Purposes

**app/Console/Commands:**
- Purpose: Custom Artisan commands for scheduled tasks or maintenance
- Contains: Command classes with `signature` and `handle` methods
- Key files: (currently empty, add new commands here)

**app/Http/Controllers/Api:**
- Purpose: RESTful API endpoints for external/integrated access
- Contains: Resource controllers (index, store, show, update, destroy)
- Key files: `IssueController.php`, `UserController.php`, `DepartmentController.php`, `RoleController.php`, `ReportController.php`, `ActivityLogController.php`, `DmLogBookController.php`

**app/Http/Requests:**
- Purpose: Form request validation and authorization
- Contains: Request classes with `rules()` and `authorize()` methods
- Organization: Nested by domain (Issue, User, Department, etc.)

**app/Http/Resources:**
- Purpose: Transform Eloquent models to consistent JSON API responses
- Contains: JsonResource classes with `toArray()` methods
- Key files: `IssueResource.php`, `UserResource.php`, `DepartmentResource.php`, `RoleResource.php`, `IssueTypeResource.php`, `IssueCommentResource.php`, `ActivityLogResource.php`

**app/Livewire/Admin:**
- Purpose: Administrative interface components for managing system data
- Contains: CRUD components for Users, Roles, Departments, IssueTypes
- Organization: Each domain has `Index.php` (listing) and `Form.php` (create/edit)
- Views: Mirrored structure in `resources/views/livewire/admin/`

**app/Livewire/Issues:**
- Purpose: Core issue tracking functionality
- Contains: `Index.php` (filterable list), `Form.php` (create/edit), `Show.php` (detail view)
- Views: `resources/views/livewire/issues/`

**app/Livewire/Reports:**
- Purpose: Report generation and export functionality
- Contains: `Index.php` (report selection), `Monthly.php`, `Yearly.php`, `Logbook.php`
- Views: `resources/views/livewire/reports/`

**app/Models:**
- Purpose: Eloquent ORM models representing database entities
- Contains: Domain models with relationships, scopes, casts
- Key files: `User.php`, `Issue.php`, `IssueComment.php`, `Department.php`, `Role.php`, `Permission.php`, `IssueType.php`, `IssueCategory.php`, `SavedFilter.php`, `ActivityLog.php`, `DmLogBook.php`

**app/Policies:**
- Purpose: Authorization logic separated from business logic
- Contains: Policy classes with methods for each action (view, create, update, delete, etc.)
- Key files: `IssuePolicy.php`, `UserPolicy.php`, `DepartmentPolicy.php`, `RolePolicy.php`

**app/Services:**
- Purpose: Business logic layer that orchestrates model operations
- Contains: Reusable services for complex operations
- Key files: `IssueService.php` (issue CRUD with activity logging), `ReportService.php` (report aggregation queries), `ExportService.php` (CSV/PDF export)

**app/View/Components:**
- Purpose: Blade class components for layout and reusable UI
- Contains: `AppLayout.php`, `GuestLayout.php`
- Usage: `<x-app-layout>` and `<x-guest-layout>` in Blade templates

**database/migrations:**
- Purpose: Version-controlled database schema definitions
- Contains: Timestamped migration files for tables and indexes
- Organization: Chronological, grouped by feature creation
- Key files: `create_users_table.php`, `create_issues_table.php`, `create_roles_table.php`, `create_permissions_table.php`, pivot table migrations

**resources/views:**
- Purpose: Blade templates for rendering HTML
- Organization: Mirrors Livewire structure, plus layouts and auth
- Key directories: `layouts/`, `livewire/`, `auth/`, `components/`

**routes:**
- Purpose: Define URL mappings to controllers and components
- Contains: `web.php` (Livewire routes), `api.php` (RESTful API), `auth.php` (authentication)
- Middleware: Applied at route group level (auth, verified, throttle)

## Key File Locations

**Entry Points:**
- `routes/web.php`: Web routes (Livewire components, dashboard, admin panel)
- `routes/api.php`: API routes (RESTful resources)
- `routes/auth.php`: Authentication routes (login, register, password reset)
- `bootstrap/app.php`: Application bootstrap configuration

**Configuration:**
- `config/auth.php`: Authentication configuration
- `config/permission.php`: Spatie permission configuration (if using package)
- `.env`: Environment-specific settings (DB, mail, etc.)
- `composer.json`: PHP dependencies and autoloading
- `package.json`: Frontend dependencies

**Core Logic:**
- `app/Services/IssueService.php`: Issue business logic (create, update, close, reopen, delete)
- `app/Services/ReportService.php`: Report aggregation and statistics
- `app/Services/ExportService.php`: CSV/PDF export functionality

**Testing:**
- `tests/Feature/`: Integration tests (HTTP requests, authorization)
- `tests/Unit/`: Unit tests (models, services)
- `phpunit.xml`: PHPUnit configuration

## Naming Conventions

**Files:**
- Models: Singular PascalCase (e.g., `Issue.php`, `IssueComment.php`)
- Controllers: Singular PascalCase + "Controller" (e.g., `IssueController.php`)
- Livewire components: Singular PascalCase (e.g., `Form.php`, `Index.php`, `Show.php`)
- Migrations: YYYY_MM_DD_HHMMSS_description (e.g., `2026_03_03_194350_create_issues_table.php`)
- Policies: Singular PascalCase + "Policy" (e.g., `IssuePolicy.php`)
- Resources: Singular PascalCase + "Resource" (e.g., `IssueResource.php`)
- Requests: Singular PascalCase + "Request" (e.g., `StoreIssueRequest.php`)
- Services: Singular PascalCase + "Service" (e.g., `IssueService.php`)

**Directories:**
- Controllers: Plural (e.g., `Api/`, `Auth/`)
- Livewire domains: Plural (e.g., `Issues/`, `Admin/`, `Reports/`)
- Requests: Organized by domain in plural folders (e.g., `Issue/`, `User/`)
- Views: Plural matching Livewire structure (e.g., `livewire/issues/`)

**Database Tables:**
- Plural snake_case (e.g., `issues`, `issue_comments`, `role_user`)
- Pivot tables: Singular names in alphabetical order (e.g., `department_issue`, `issue_issue_type`)

**Routes:**
- Web: kebab-case dot notation (e.g., `issues.index`, `admin.users.create`)
- API: kebab-case resource names (e.g., `api.issues.index`, `api.roles.update`)

## Where to Add New Code

**New Feature (e.g., "Maintenance Requests"):**
- Primary code: `app/Models/MaintenanceRequest.php`
- Service: `app/Services/MaintenanceRequestService.php`
- Livewire: `app/Livewire/MaintenanceRequests/Index.php`, `Form.php`, `Show.php`
- API Controller: `app/Http/Controllers/Api/MaintenanceRequestController.php`
- Request Validators: `app/Http/Requests/Api/MaintenanceRequest/StoreMaintenanceRequestRequest.php`
- Resource: `app/Http/Resources/MaintenanceRequestResource.php`
- Policy: `app/Policies/MaintenanceRequestPolicy.php`
- Migration: `database/migrations/YYYY_MM_DD_HHMMSS_create_maintenance_requests_table.php`
- Tests: `tests/Feature/MaintenanceRequestTest.php`

**New Admin Section (e.g., "Locations"):**
- Livewire components: `app/Livewire/Admin/Locations/Index.php`, `Form.php`
- Views: `resources/views/livewire/admin/locations/index.blade.php`, `form.blade.php`
- Routes: Add to `routes/web.php` under `Route::prefix('admin')` group
- Navigation: Update `resources/views/layouts/app.blade.php` sidebar

**New API Resource:**
- Model: `app/Models/NewResource.php`
- Migration: `database/migrations/..._create_new_resources_table.php`
- Controller: `app/Http/Controllers/Api/NewResourceController.php`
- Resource transformer: `app/Http/Resources/NewResourceResource.php`
- Request validators: `app/Http/Requests/Api/NewResource/`
- Route: `Route::apiResource('new-resources', NewResourceController::class)` in `routes/api.php`

**New Report Type:**
- Service method: Add to `app/Services/ReportService.php` (e.g., `customReport()`)
- Livewire component: `app/Livewire/Reports/Custom.php`
- View: `resources/views/livewire/reports/custom.blade.php`
- Route: `Route::get('/reports/custom', Custom::class)` in `routes/web.php`
- Navigation: Add to `resources/views/livewire/reports/index.blade.php`

**Utilities:**
- Shared helpers: Create `app/Helpers/helper.php` and autoload in `composer.json`
- Global scopes: Add as traits in `app/Models/Scopes/`
- Shared validation rules: Add to `app/Rules/` as custom rule classes

## Special Directories

**node_modules/:**
- Purpose: NPM dependencies (not committed)
- Generated: Yes
- Committed: No (in .gitignore)

**vendor/:**
- Purpose: Composer dependencies (not committed)
- Generated: Yes
- Committed: No (in .gitignore)

**storage/:
- Purpose: Application-generated files (logs, cache, uploads)
- Generated: Yes
- Committed: No (in .gitignore)

**bootstrap/cache/:**
- Purpose: Framework bootstrap cache
- Generated: Yes
- Committed: No (in .gitignore)

**.planning/:**
- Purpose: Planning documentation (GSD framework)
- Generated: Yes (by Claude)
- Committed: Yes (for team collaboration)

---

*Structure analysis: 2026-03-28*
