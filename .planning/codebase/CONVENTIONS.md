# Coding Conventions

**Analysis Date:** 2026-03-28

## Naming Patterns

**Classes:**
- PascalCase (e.g., `IssueController`, `IssueService`, `IssuePolicy`)
- Models: Singular, PascalCase (e.g., `Issue`, `User`, `Department`)
- Controllers: `{Resource}Controller` pattern (e.g., `IssueController`)
- Services: `{Resource}Service` pattern (e.g., `IssueService`)
- Policies: `{Resource}Policy` pattern (e.g., `IssuePolicy`)
- Livewire Components: PascalCase (e.g., `Index`, `Form`, `Show`)

**Methods/Functions:**
- camelCase (e.g., `getFilteredIssues`, `closeIssue`, `toggleUserStatus`)
- Boolean/check methods: `is*()`, `has*()`, `can*()` (e.g., `isClosed()`, `hasPermission()`, `can('issues.view')`)
- Action methods: imperative verbs (e.g., `create()`, `update()`, `delete()`, `close()`, `reopen()`)

**Variables/Properties:**
- camelCase (e.g., `$issue`, `$userId`, `$department_ids`)
- Public Livewire properties: snake_case or camelCase (e.g., `$search`, `$department_id`, `$sortField`)
- Boolean properties: `is*`, `has*` (e.g., `$isEditing`, `$isActive`, `$showResetPasswordModal`)

**Constants:**
- UPPER_SNAKE_CASE (not commonly used, prefer config)

**Database:**
- Tables: plural snake_case (e.g., `issues`, `department_issue`, `permission_role`)
- Columns: snake_case (e.g., `created_at`, `assigned_to_user_id`, `issue_type_id`)
- Foreign keys: `{table}_id` (e.g., `issue_type_id`, `assigned_to_user_id`)
- Pivot tables: alphabetical, snake_case (e.g., `department_issue`, `issue_issue_type`)
- Indexes: follow column names

## Code Style

**Formatting:**
- PSR-12 coding standard (Laravel default)
- No Laravel Pint configuration detected - using defaults
- Indentation: 4 spaces (PHP standard)
- Line length: no strict limit enforced

**Linting:**
- No ESLint/Prettier for PHP (these are for frontend)
- PHPStan or similar static analysis: Not detected

**PHPDoc:**
- Used on public methods in models and services
- Format: single-line description for simple methods
- Return types: always declared (PHP 8.1+ return type declarations)

```php
/**
 * Get the departments that belong to the issue.
 */
public function departments(): BelongsToMany
{
    return $this->belongsToMany(Department::class, 'department_issue')
        ->withTimestamps();
}
```

## Import Organization

**Order:**
1. PHP native imports (use statements)
2. Framework imports (Illuminate, Laravel)
3. Third-party imports
4. Application imports (App\*)

**Path Aliases:**
- Standard Laravel aliases: none custom detected
- Use fully qualified class names with `use` statements

```php
use App\Models\Issue;
use App\Models\Department;
use App\Services\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
```

## Error Handling

**Patterns:**
- Validation: Form Request classes with `failedValidation()` override for API JSON responses
- Authorization: Policy classes with `can()` checks in controllers
- Exceptions: Not extensively using try-catch in controllers - relies on Laravel's global handlers

**Form Request Validation:**
```php
// Location: app/Http/Requests/Api/Issue/StoreIssueRequest.php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'priority' => ['required', 'in:low,medium,high,critical'],
    ];
}

protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(
        response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422)
    );
}
```

**Authorization:**
```php
// In controllers
$this->authorize('update', $issue);

// In policies
public function update(User $user, Issue $issue): bool
{
    return $user->can('issues.update');
}
```

## Logging

**Framework:** Laravel's Log facade (not extensively used in sampled code)

**Patterns:**
- Activity logging via custom `ActivityLog` model in services
- Database-backed audit trail for issue changes

```php
// Location: app/Services/IssueService.php
protected function logActivity(Issue $issue, string $action, string $description): void
{
    ActivityLog::create([
        'subject_type' => Issue::class,
        'subject_id' => $issue->id,
        'actor_user_id' => Auth::id(),
        'action' => $action,
        'meta' => [
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ],
    ]);
}
```

## Comments

**When to Comment:**
- Above public methods in models/services (PHPDoc)
- Complex business logic (e.g., change tracking in IssueService)
- TODO/FIXME: Not extensively found in sampled code

**JSDoc/TSDoc:**
- Not applicable (PHP project)

## Function Design

**Size:**
- Controllers: Methods typically 10-30 lines
- Services: Methods 10-40 lines (business logic)
- Models: Relationship methods 1-5 lines

**Parameters:**
- Type hints always used
- Array data for complex inputs (e.g., `create(array $data)`)
- Specific parameters for simple actions (e.g., `close(Issue $issue, ?string $note = null)`)

**Return Values:**
- Always declare return types
- Models: return model instances or relationships
- Services: return models, collections, or boolean
- Controllers: return resources or JSON responses

```php
// Service method pattern
public function create(array $data): Issue
{
    return DB::transaction(function () use ($data) {
        $issue = Issue::create($data);
        // ... attach relationships
        $this->logActivity($issue, 'created', 'Issue was created');
        return $issue->load(['departments', 'issueTypes']);
    });
}
```

## Module Design

**Exports:**
- Not applicable (PHP uses namespaces and autoloading)

**Barrel Files:**
- Not applicable (PHP concept)

## Laravel-Specific Conventions

**Models:**
- Traits: `HasFactory`, `SoftDeletes`, `Notifiable`
- Mass assignment: `$fillable` property (not guarded)
- Casts: `protected function casts(): array` (PHP 8.1+ syntax)
- Relationships: typed return declarations

```php
// Location: app/Models/Issue.php
class Issue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'priority',
        // ...
    ];

    protected function casts(): array
    {
        return [
            'checkin_date' => 'date',
            'closed_at' => 'datetime',
            'recovery_cost' => 'integer',
        ];
    }
}
```

**Controllers:**
- API controllers: return JsonResource or JsonResponse
- Type-hinted request objects (Form Request)
- RESTful resource naming

**Migrations:**
- Schema::hasTable() checks before creating (defensive pattern)
- Foreign key constraints with constrained()
- Indexes on frequently queried columns

```php
// Location: database/migrations/2026_03_03_194350_create_issues_table.php
public function up(): void
{
    if (!Schema::hasTable('issues')) {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('issue_type_id')->nullable()->constrained('issue_types');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            // ...
            $table->index(['status', 'created_at']);
        });
    }
}
```

**Livewire Components:**
- Public properties: form fields, filters, UI state
- Computed properties: `#[Computed]` attribute
- Event listeners: `#[On('event-name')]` attribute
- Query string binding: `protected $queryString` property

```php
// Location: app/Livewire/Issues/Index.php
class Index extends Component
{
    use WithPagination;

    public string $tab = 'all';
    public string $search = '';
    public ?int $department_id = null;

    protected $queryString = [
        'tab' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    #[Computed]
    public function departments(): array
    {
        return Department::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    #[On('issue-created')]
    public function refresh(): void
    {
        $this->resetPage();
    }
}
```

**Livewire Form Validation:**
- Property attributes: `#[Rule(['required', 'string'])]`
- Manual validation in save() methods

```php
// Location: app/Livewire/Issues/Form.php
#[Rule(['required', 'string', 'max:255'])]
public string $title = '';

#[Rule(['required', 'in:urgent,high,medium,low'])]
public string $priority = 'medium';
```

**API Resources:**
- Transform models to JSON arrays
- Conditional loading with `whenLoaded()`, `whenCounted()`
- Date formatting with null-safe operators

```php
// Location: app/Http/Resources/IssueResource.php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'issue_date' => $this->issue_date?->format('Y-m-d'),
        'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
        'comments_count' => $this->whenCounted('comments'),
    ];
}
```

**Services:**
- Business logic layer
- Database transactions with `DB::transaction()`
- Activity logging for audit trail
- Single responsibility per service

**Factories:**
- PHP 8.1+ `definition(): array` syntax
- State modifiers for specific conditions (e.g., `open()`, `closed()`, `urgent()`)
- Relationship creation in `afterCreating()` callbacks

```php
// Location: database/factories/IssueFactory.php
public function definition(): array
{
    return [
        'title' => fake()->randomElement([...]),
        'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
        'status' => fake()->randomElement(['open', 'open', 'open', 'open', 'closed']),
    ];
}

public function open(): IssueFactory
{
    return $this->state(fn (array $attributes) => [
        'status' => 'open',
        'closed_at' => null,
    ]);
}
```

**Seeders:**
- Call order: dependencies first (roles before users)
- `firstOrCreate()` for idempotent seeding
- Permission synchronization to roles

---

*Convention analysis: 2026-03-28*
