# Architecture

**Analysis Date:** 2026-03-28

## Pattern Overview

**Overall:** MVC with Livewire-powered reactive components

**Key Characteristics:**
- Traditional Laravel MVC foundation enhanced with Livewire for dynamic UI
- Service layer for business logic encapsulation
- Policy-based authorization with role/permission system
- API Resources for JSON transformation layer
- Activity logging pattern for audit trails

## Layers

**Models (Data Layer):**
- Purpose: Eloquent ORM models with relationships, scopes, and business methods
- Location: `app/Models/`
- Contains: Domain models (User, Issue, Department, Role, Permission, IssueComment, etc.)
- Depends on: Database tables via migrations
- Used by: Controllers, Services, Livewire components, Policies

**Services (Business Logic Layer):**
- Purpose: Encapsulate complex business operations and cross-cutting concerns
- Location: `app/Services/`
- Contains: IssueService, ReportService, ExportService
- Depends on: Models, Facades (DB, Auth)
- Used by: Livewire components, Controllers

**Controllers (HTTP Request Handling):**
- Purpose: Handle HTTP requests and coordinate responses
- Location: `app/Http/Controllers/`
- Contains: Api controllers (IssueController, DepartmentController, etc.), Auth controllers
- Depends on: Requests, Resources, Models, Services
- Used by: Routes (api.php, web.php)

**Livewire Components (Reactive UI Layer):**
- Purpose: Handle dynamic frontend interactions without leaving Laravel
- Location: `app/Livewire/`
- Contains: Issues/*, Admin/*, Reports/*, Statistics/*, Graphs/*
- Depends on: Services, Models, Policies
- Used by: Web routes (web.php)

**Policies (Authorization Layer):**
- Purpose: Centralize authorization logic separate from business logic
- Location: `app/Policies/`
- Contains: IssuePolicy, UserPolicy, DepartmentPolicy, etc.
- Depends on: User model, domain models
- Used by: Controllers, Livewire components via `authorize()` calls

**API Resources (Transformation Layer):**
- Purpose: Transform models into consistent JSON API responses
- Location: `app/Http/Resources/`
- Contains: IssueResource, UserResource, DepartmentResource, etc.
- Depends on: Models, nested Resources
- Used by: API Controllers

**Form Requests (Validation Layer):**
- Purpose: Encapsulate validation rules and authorization for requests
- Location: `app/Http/Requests/`
- Contains: Api/*/*Request.php classes
- Depends on: Validation rules
- Used by: Controllers via type-hinting

## Data Flow

**Issue Creation Flow (Web UI):**

1. User navigates to `/issues/create` → `App\Livewire\Issues\Form` component mounts
2. User fills form → Livewire validates real-time using `#[Rule]` attributes
3. User submits → `Form::save()` method invokes `IssueService::create()`
4. Service wraps in `DB::transaction()` for atomicity
5. Issue created, departments/types attached via pivot sync
6. ActivityLog entry created for audit trail
7. Component redirects to issue detail page with flash message

**Issue Listing Flow (API):**

1. Client requests `GET /api/issues?status=open&priority=high`
2. Route maps to `IssueController::index()` with auth middleware
3. Controller applies query filters based on request parameters
4. Eloquent query eager loads relationships (departments, issueTypes, createdBy)
5. Results paginated (default 15 per page)
6. Issues transformed via `IssueResource::collection()`
7. JSON response returned with pagination metadata

**Issue Close Flow:**

1. User clicks "Close" → `Issues\Index::closeIssue()` called
2. Policy check: `authorize('close', $issue)` → `IssuePolicy::close()`
3. Service invoked: `IssueService::close($issue)` wraps in transaction
4. Issue status updated to 'closed', `closed_at` and `closed_by_user_id` set
5. Activity logged: "Issue was closed"
6. Event dispatched: `issue-closed`
7. Other components listen via `#[On('issue-closed')]` to refresh

**Authorization Flow:**

1. Authenticated user makes request
2. Controller/Livewire calls `authorize('action', $model)`
3. Laravel resolves Policy class (e.g., `IssuePolicy`)
4. Policy method checks permissions via `$user->can()` or `$user->hasRole()`
5. Returns boolean - true allows, false throws `403 Forbidden`

**Report Generation Flow:**

1. User accesses `/reports/monthly`
2. `Reports\Monthly` Livewire component mounts
3. Component calls `ReportService::monthlyReport($year, $month, $categoryId)`
4. Service builds raw SQL queries for aggregations (by status, department, type)
5. Data returned as structured array
6. Blade view renders charts using aggregated data

**State Management:**
- Server-side state: Eloquent models with soft deletes
- Component state: Livewire public properties (automatically synced)
- Query state: URL query string bindings for shareable filters
- Session state: Flash messages for user feedback

## Key Abstractions

**Issue Entity:**
- Purpose: Core domain model representing a hotel/DM issue
- Examples: `app/Models/Issue.php`, `app/Models/IssueComment.php`
- Pattern: Active Record with soft deletes, scopes (`open()`, `closed()`), state methods (`close()`, `reopen()`)

**Role-Based Access Control (RBAC):**
- Purpose: Hierarchical permission system
- Examples: `app/Models/Role.php`, `app/Models/Permission.php`, `app/Models/User.php`
- Pattern: Many-to-many relationships (users←→roles←→permissions), helper methods (`hasPermission()`, `hasRole()`)

**Activity Logging:**
- Purpose: Audit trail for all issue state changes
- Examples: `app/Models/ActivityLog.php`, usage in `IssueService::logActivity()`
- Pattern: Polymorphic relationship (`subject_type`, `subject_id`), automatic metadata capture (IP, user agent)

**Filterable Query Builder:**
- Purpose: Reusable filtering logic for issues
- Examples: `IssueService::getFilteredIssues()`, `Issues\Index::getIssues()`
- Pattern: Array-based filter criteria, conditional `whereHas()` for relationships, dynamic ordering

**Resource Transformation:**
- Purpose: Consistent API response format
- Examples: `app/Http/Resources/IssueResource.php`
- Pattern: Conditional loading (`$this->whenLoaded()`), nested resources, date formatting, relationship counts

## Entry Points

**Web Application Entry Point:**
- Location: `routes/web.php`
- Triggers: Browser navigation to web routes
- Responsibilities: Route Livewire components, authentication routes, admin panel routes
- Middleware: `auth`, `verified` on authenticated routes

**API Entry Point:**
- Location: `routes/api.php`
- Triggers: External API consumers or internal AJAX/fetch calls
- Responsibilities: RESTful resource endpoints (issues, users, departments, roles, etc.)
- Middleware: `auth` (Sanctum tokens or session)

**CLI Entry Point:**
- Location: `routes/console.php`, `app/Console/Commands/`
- Triggers: `php artisan` commands
- Responsibilities: Scheduled tasks, custom maintenance commands

**Authentication Entry Point:**
- Location: `routes/auth.php`
- Triggers: Login/register/logout flows
- Responsibilities: Laravel Breeze authentication scaffolding (email verification, password reset)

## Error Handling

**Strategy:** Form Request validation + Policy authorization + Exception handler

**Patterns:**
- **Validation errors:** Form Request classes return 422 JSON with detailed error messages
- **Authorization errors:** Policies return false → Laravel throws `403 Forbidden`
- **Model not found:** Route model binding → `404 Not Found` automatically
- **Transaction failures:** Service layer wraps in `DB::transaction()` → automatic rollback on exception
- **Soft deletes:** Deleted models excluded from queries by default, accessible via `withTrashed()`

**Activity Logging on Errors:**
- Service methods log activity before success
- Failed transactions don't create partial records
- No activity log entries for failed operations

## Cross-Cutting Concerns

**Authentication:** Laravel Breeze (email/password) + Sanctum (API tokens)
- Implementation: Session-based auth for web, token-based for API
- User model: `App\Models\User` extends `Authenticatable`
- Active user check: `is_active` boolean field

**Authorization:** Policy-based with RBAC
- Implementation: `App\Policies\*` classes checked via `authorize()` or `$user->can()`
- Permission format: Dot notation (e.g., `issues.view`, `issues.update.own`)
- Role hierarchy: SuperAdmin role has all permissions

**Validation:** Form Request classes + Livewire attributes
- API: `App\Http\Requests\Api\*` classes with `rules()` method
- Livewire: `#[Rule(['required'])]` attributes on component properties

**Logging:** Activity logs for domain events
- Implementation: `ActivityLog` model with polymorphic subject
- Captured: Actor, action, description, IP address, user agent
- Not logged: Authentication attempts (handled by Laravel), read-only operations

**Query Performance:** Eager loading and database indexing
- Eager loading: `with(['departments', 'issueTypes', 'createdBy'])`
- Indexes: Defined in migrations (status, priority, assigned_to_user_id, closed_at)
- N+1 prevention: Resource transformation checks `$this->whenLoaded()`

---

*Architecture analysis: 2026-03-28*
