# Testing Patterns

**Analysis Date:** 2026-03-28

## Test Framework

**Runner:**
- PHPUnit 10.x (Laravel 12 default)
- Config: `phpunit.xml`
- No Pest framework detected

**Assertion Library:**
- PHPUnit native assertions

**Run Commands:**
```bash
vendor/bin/phpunit                # Run all tests
vendor/bin/phpunit --testsuite=Feature  # Run feature tests only
vendor/bin/phpunit --testsuite=Unit     # Run unit tests only
vendor/bin/phpunit --filter=test_name   # Run specific test
vendor/bin/phpunit --coverage-html      # Generate coverage report
```

**Test Database:**
- SQLite in-memory database
- Configured in `phpunit.xml`:
  ```xml
  <env name="DB_CONNECTION" value="sqlite"/>
  <env name="DB_DATABASE" value=":memory:"/>
  ```
- Uses `RefreshDatabase` trait for clean state between tests

## Test File Organization

**Location:**
- Tests in `tests/` directory (not co-located)
- Separate `Feature/` and `Unit/` subdirectories

**Naming:**
- Test classes: `{Feature}Test.php` (e.g., `AuthenticationTest.php`)
- Test methods: `test_{action}_{expected_result}` (e.g., `test_users_can_authenticate_using_the_login_screen`)

**Structure:**
```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── AuthenticationTest.php
│   │   ├── EmailVerificationTest.php
│   │   ├── PasswordConfirmationTest.php
│   │   ├── PasswordResetTest.php
│   │   ├── PasswordUpdateTest.php
│   │   └── RegistrationTest.php
│   ├── ExampleTest.php
│   └── ProfileTest.php
├── Unit/
│   └── ExampleTest.php
└── TestCase.php
```

## Test Structure

**Suite Organization:**
```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;  // Resets database after each test

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();  // Assert user is logged in
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();  // Assert user is NOT logged in
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
```

**Patterns:**
- **Setup:** `RefreshDatabase` trait for database reset
- **Teardown:** Handled by trait
- **Assertions:** Laravel's fluent test response assertions + PHPUnit assertions

**Common Assertions:**
```php
$response->assertStatus(200);
$response->assertRedirect(route('name'));
$response->assertJson(['key' => 'value']);
$this->assertAuthenticated();
$this->assertGuest();
$this->assertDatabaseHas('issues', ['title' => 'Test Issue']);
```

## Mocking

**Framework:** PHPUnit native mocks (not extensively used in sampled tests)

**Patterns:**
- Not extensively sampled - tests appear to use real factories
- Laravel's `Bus::fake()`, `Event::fake()`, `Notification::fake()` for faking

**What to Mock:**
- External API calls (not detected in codebase)
- Mail notifications (use `Notification::fake()`)
- Events (use `Event::fake()`)

**What NOT to Mock:**
- Database queries (use factories instead)
- Models (use real models with in-memory SQLite)
- Services (test them integration-style with controllers)

## Fixtures and Factories

**Test Data:**
```php
// UserFactory.php - uses PHP 8.1+ syntax
public function definition(): array
{
    return [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password' => static::$password ??= Hash::make('password'),
        'remember_token' => Str::random(10),
    ];
}

public function unverified(): static
{
    return $this->state(fn (array $attributes) => [
        'email_verified_at' => null,
    ]);
}
```

**Location:**
- `database/factories/` - All model factories
- `database/seeders/` - Seeders for development/production data

**Factory Pattern:**
```php
// In tests
$user = User::factory()->create();                    // Single user
$users = User::factory()->count(3)->create();         // Multiple users
$unverifiedUser = User::factory()->unverified()->create();  // With state

// With relationships
$issue = Issue::factory()
    ->for(User::factory(), 'createdBy')
    ->has(Depment::factory()->count(2))
    ->create();

// Complex issue factory with callbacks
$issue = Issue::factory()
    ->configure()  // Attaches departments, types, comments
    ->urgent()     // State modifier
    ->create();
```

**State Modifiers:**
```php
// Location: database/factories/IssueFactory.php
public function open(): IssueFactory
{
    return $this->state(fn (array $attributes) => [
        'status' => 'open',
        'closed_at' => null,
        'closed_by_user_id' => null,
    ]);
}

public function closed(): IssueFactory
{
    return $this->state(fn (array $attributes) => [
        'status' => 'closed',
        'closed_at' => fake()->dateTime(),
    ]);
}

public function urgent(): IssueFactory
{
    return $this->state(fn (array $attributes) => [
        'priority' => 'urgent',
    ]);
}
```

**Relationship Factories:**
```php
// Location: database/factories/IssueFactory.php
public function configure(): IssueFactory
{
    return $this->afterCreating(function (Issue $issue) {
        // Attach random departments
        $departments = Department::inRandomOrder()
            ->limit(fake()->numberBetween(1, 3))
            ->pluck('id');
        $issue->departments()->attach($departments);

        // Attach issue types
        $types = IssueType::inRandomOrder()
            ->limit(fake()->numberBetween(1, 2))
            ->pluck('id');
        $issue->issueTypes()->attach($types);

        // Add comments
        for ($i = 0; $i < fake()->numberBetween(0, 5); $i++) {
            IssueComment::factory()->create(['issue_id' => $issue->id]);
        }
    });
}
```

**Seeders:**
- Called in `DatabaseSeeder::run()`
- Dependency order: roles → permissions → users → departments → issue categories → issue types → issues
- Idempotent using `firstOrCreate()`

```php
// Location: database/seeders/PermissionsSeeder.php
public function run(): void
{
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(
            ['name' => $permission['name']],
            ['description' => $permission['description']]
        );
    }

    // Assign permissions to roles
    $superAdmin = Role::where('name', 'SuperAdmin')->first();
    $superAdmin->permissions()->sync($allPermissionIds);
}
```

## Coverage

**Requirements:** No enforced coverage target detected

**View Coverage:**
```bash
vendor/bin/phpunit --coverage-html coverage
# Open coverage/index.html in browser
```

**Current Coverage Status:**
- **Tests present:** 10 test files
- **App files:** 98 PHP files
- **Estimated coverage:** Low (~5-10%)
- **Tested areas:**
  - Authentication (login, logout, password reset, registration)
  - Basic route rendering
  - Profile updates
- **Untested areas:**
  - Issue CRUD operations
  - Department management
  - User management (admin)
  - Role/Permission management
  - Reports and statistics
  - All Livewire components
  - All Services (IssueService, ExportService, etc.)
  - All Policies
  - API endpoints

## Test Types

**Unit Tests:**
- Minimal usage detected (only `ExampleTest.php` with `assertTrue(true)`)
- Should test: Services, Policies, Models (business logic in isolation)
- Current gap: No unit tests for business logic

**Integration/Feature Tests:**
- Focus: HTTP endpoints, authentication flows
- Use real database (SQLite in-memory)
- Test full request lifecycle

**API Testing Pattern (not implemented but recommended):**
```php
public function test_user_can_create_issue(): void
{
    $user = User::factory()->create();
    $department = Department::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/issues', [
            'title' => 'Test Issue',
            'priority' => 'high',
            'department_ids' => [$department->id],
        ]);

    $response->assertStatus(201)
        ->assertJson([
            'title' => 'Test Issue',
            'priority' => 'high',
        ]);

    $this->assertDatabaseHas('issues', [
        'title' => 'Test Issue',
        'created_by' => $user->id,
    ]);
}
```

**E2E Tests:**
- Framework: Not used (no Laravel Dusk detected)
- Browser testing: Not implemented

## Common Patterns

**Authentication in Tests:**
```php
public function test_authenticated_user_can_create_issue(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post('/issues', [...]);

    $response->assertRedirect();
}
```

**Authorization Testing:**
```php
public function test_user_without_permission_cannot_delete_issues(): void
{
    $user = User::factory()->create();  // No special permissions
    $issue = Issue::factory()->create();

    $response = $this->actingAs($user)
        ->delete("/issues/{$issue->id}");

    $response->assertForbidden(403);
}
```

**Livewire Testing (recommended pattern):**
```php
public function test_livewire_component_renders(): void
{
    Livewire::test(Issues\Index::class)
        ->assertSet('tab', 'all')
        ->assertSet('search', '')
        ->call('setTab', 'open')
        ->assertSet('tab', 'open');
}

public function test_user_can_create_issue_via_livewire(): void
{
    $user = User::factory()->create();
    $department = Department::factory()->create();

    Livewire::actingAs($user)
        ->test(Issues\Form::class)
        ->set('title', 'Test Issue')
        ->set('priority', 'high')
        ->set('department_ids', [$department->id])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('issues.show', Issue::first()));
}
```

**Service Testing (recommended pattern):**
```php
public function test_issue_service_creates_issue_with_activity_log(): void
{
    $user = User::factory()->create();
    $data = [
        'title' => 'Test Issue',
        'priority' => 'high',
        'created_by' => $user->id,
    ];

    $issue = $this->service->create($data);

    $this->assertDatabaseHas('issues', [
        'title' => 'Test Issue',
        'created_by' => $user->id,
    ]);

    $this->assertDatabaseHas('activity_logs', [
        'subject_type' => Issue::class,
        'subject_id' => $issue->id,
        'action' => 'created',
    ]);
}
```

**Policy Testing (recommended pattern):**
```php
public function test_user_can_update_own_issues(): void
{
    $user = User::factory()->create();
    $issue = Issue::factory()->create(['created_by' => $user->id]);

    $this->assertTrue($user->can('update', $issue));
}

public function test_user_cannot_update_closed_issue(): void
{
    $user = User::factory()->create();
    $issue = Issue::factory()->closed()->create();

    $this->assertFalse($user->can('update', $issue));
}
```

**Validation Testing:**
```php
public function test_create_issue_requires_title(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post('/issues', [
            'title' => '',  // Invalid
            'priority' => 'high',
        ]);

    $response->assertSessionHasErrors(['title']);
}
```

**Testing With Relationships:**
```php
public function test_issue_filters_by_department(): void
{
    $department1 = Department::factory()->create();
    $department2 = Department::factory()->create();

    $issue1 = Issue::factory()->hasAttached($department1)->create();
    $issue2 = Issue::factory()->hasAttached($department2)->create();

    $response = $this->getJson('/api/issues?department_id=' . $department1->id);

    $response->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $issue1->id);
}
```

---

*Testing analysis: 2026-03-28*
