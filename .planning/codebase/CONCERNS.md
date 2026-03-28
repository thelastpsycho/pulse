# Codebase Concerns

**Analysis Date:** 2026-03-28

## Tech Debt

**ReportService Code Duplication:**
- Issue: `app/Services/ReportService.php` (503 lines) contains massive duplication across `dateRangeReport()`, `monthlyReport()`, and `yearlyReport()` methods
- Files: `app/Services/ReportService.php`
- Impact: Every report query pattern is repeated 3-4 times with minor variations. The COALESCE(DATE()) pattern appears 16+ times. Adding a new filter requires changes in 3+ places.
- Fix approach: Extract query builder into a private method with filter parameters. Use a query scope pattern for the common date filtering logic.

**Hardcoded API Key in Command:**
- Issue: DeepSeek API key fallback hardcoded in `CategorizeIssuesWithDeepSeek.php`
- Files: `app/Console/Commands/CategorizeIssuesWithDeepSeek.php:45`
- Impact: Default fallback value `'sk-c25fcc0bfe8444c0bd363b42cb1962f4'` is exposed in source code
- Fix approach: Remove the hardcoded fallback. Require explicit API key via environment variable or command option.

**Massive Nationality Array:**
- Issue: 132-country array hardcoded in Livewire component
- Files: `app/Livewire/Issues/Form.php:214-244`
- Impact: Bloates component, makes updates difficult, should be in a database or config file
- Fix approach: Move to `config/nationalities.php` or database table for easier maintenance.

**Legacy Migration Command:**
- Issue: `MigrateIssuesFromDmlog.php` command suggests incomplete migration from legacy system
- Files: `app/Console/Commands/MigrateIssuesFromDmlog.php`
- Impact: Codebase carries legacy migration logic that should be removed after migration is complete
- Fix approach: Remove if migration is complete, or add documentation explaining when this can be deleted.

## Known Bugs

**Created By Type Mismatch:**
- Symptoms: `Issue.created_by` is varchar but references `users.id` (integer)
- Files: `app/Models/Issue.php:63-68`, `database/migrations/2026_03_03_194350_create_issues_table.php`
- Trigger: When comparing `$issue->created_by === $user->id` (IssuePolicy.php:27, 53, 65) - string vs integer comparison
- Workaround: PHP's loose equality works but this is a data integrity issue
- Fix approach: Migration to convert `created_by` to integer type, add foreign key constraint.

**Activity Log Actor Type:**
- Symptoms: `ActivityLog.actor_user_id` references users but no foreign key
- Files: `app/Models/ActivityLog.php`
- Trigger: Orphaned activity logs if user is deleted
- Workaround: Soft deletes on users prevents this
- Fix approach: Add foreign key with ON DELETE SET NULL or soft delete on activity logs.

## Security Considerations

**Raw SQL in ReportService:**
- Risk: 27 instances of `selectRaw()`, `groupByRaw()`, `whereRaw()`, and `DB::raw()` in ReportService
- Files: `app/Services/ReportService.php`
- Current mitigation: All uses parameterize user input correctly (using `?` placeholders)
- Recommendations: Consider using query scopes or builder patterns to reduce raw SQL. Ensure any new raw SQL is reviewed for injection risks.

**DeepSeek API Key Storage:**
- Risk: API key stored in environment variable, no rotation strategy
- Files: `.env.example:DEEPSEEK_API_KEY` (referenced), `app/Console/Commands/CategorizeIssuesWithDeepSeek.php:45`
- Current mitigation: Environment variable not committed to git
- Recommendations: Implement API key rotation. Consider using encrypted secrets management. Add rate limiting to prevent API abuse.

**Mass Assignment Protection:**
- Risk: `$fillable` array in Issue model includes many fields
- Files: `app/Models/Issue.php:16-39`
- Current mitigation: Form requests and Livewire validation rules restrict input
- Recommendations: Consider using `$guarded` instead. Review each fillable field for necessity.

**Authorization Check Gaps:**
- Risk: Some operations may lack authorization checks
- Files: `app/Policies/IssuePolicy.php` covers create/update/delete/close/reopen
- Current mitigation: Policies are properly defined and used
- Recommendations: Verify all controller actions use `authorize()` or policy middleware.

## Performance Bottlenecks

**ReportService Query Performance:**
- Problem: Multiple separate queries for aggregations in each report method
- Files: `app/Services/ReportService.php`
- Cause: Each aggregation (byStatus, byPriority, byDepartment, byIssueType, byCategory, avgCloseTime, dailyTrend) runs a separate query
- Improvement path: Use single query with multiple subqueries or database views for complex reports. Consider caching report results.

**Livewire Computed Properties:**
- Problem: Computed properties query database on every access
- Files: `app/Livewire/Issues/Index.php:202-234` (departments, issueTypes, users properties)
- Cause: No caching on computed properties that rarely change
- Improvement path: Cache department/user/issue type lists. Use Livewire's `Lazy` method for expensive computations.

**N+1 Query Risk in Index:**
- Problem: Potential N+1 queries when loading issues with relationships
- Files: `app/Livewire/Issues/Index.php`, `app/Services/IssueService.php:157`
- Cause: `IssueService::getFilteredIssues()` eager loads relationships but some Livewire components may not
- Improvement path: Verify all queries use `with(['departments', 'issueTypes', 'createdBy', 'assignedTo'])`. Monitor with query logger.

**CSV Export Performance:**
- Problem: ExportService loads all matching issues into memory
- Files: `app/Services/ExportService.php`
- Cause: No chunking for large exports
- Improvement path: Use lazy collections or chunked processing for exports over 1000 records.

## Fragile Areas

**Issue Model Relationships:**
- Files: `app/Models/Issue.php`
- Why fragile: Has both many-to-many (departments, issueTypes) and many-to-one (issueType) relationships for issue types. This dual relationship pattern can cause confusion.
- Safe modification: Always use `issueTypes()` for multiple types, `issueType()` only for primary type. Document which to use when.
- Test coverage: Minimal - ensure tests cover both relationship paths.

**Status State Management:**
- Files: `app/Models/Issue.php:130-173`, `app/Policies/IssuePolicy.php:45,73,81`
- Why fragile: Status is just a string ('open', 'closed', 'resolved'). No enum or state machine. Easy to add new status values and miss updating policies/scopes.
- Safe modification: Add all new status values to scopes and policies immediately. Consider using Laravel Enums.
- Test coverage: Needs tests for status transitions and policy checks for each status.

**Date Field Confusion:**
- Files: `app/Models/Issue.php:44-46`
- Why fragile: Three date fields (checkin_date, checkout_date, issue_date) plus created_at/closed_at. Business logic depends on correct interpretation.
- Safe modification: Always document which date field represents what in business logic.
- Test coverage: Add tests for date edge cases (null dates, date ranges, date ordering).

**Livewire Component State:**
- Files: Multiple Livewire components, especially `app/Livewire/Issues/Index.php` (349 lines)
- Why fragile: Public properties can be modified from anywhere, no explicit state management
- Safe modification: Use `#[Locked]` properties for values that shouldn't change. Use `#[Computed]` for derived values.
- Test coverage: Needs integration tests for Livewire component state changes.

## Scaling Limits

**Database Connection:**
- Current capacity: Single SQLite database by default (`.env.example:DB_CONNECTION=sqlite`)
- Limit: SQLite not suitable for production. Concurrent writes will lock. No built-in replication.
- Scaling path: Migrate to MySQL/PostgreSQL for production. Add connection pooling. Consider read replicas for reporting queries.

**Queue Configuration:**
- Current capacity: Database queue driver (`QUEUE_CONNECTION=database`)
- Limit: Queue workers poll database. Not ideal for high volume. No dedicated queue server.
- Scaling path: Switch to Redis or RabbitMQ for queues. Add multiple queue workers. Implement job batching for bulk operations.

**Report Generation:**
- Current capacity: Reports run on-demand with no caching
- Limit: Complex reports (ReportService) run multiple aggregation queries. Will slow down as data grows.
- Scaling path: Implement report caching. Pre-compute reports via scheduled jobs. Use materialized views for common aggregations.

**File Storage:**
- Current capacity: Local filesystem (`FILESYSTEM_DISK=local`)
- Limit: Single server storage. No CDN. Backup/restore complexity.
- Scaling path: Switch to S3-compatible storage for production. Add CDN for assets. Implement automated backups.

## Dependencies at Risk

**Laravel 12 (Beta):**
- Risk: Laravel 12 is bleeding edge. APIs may change before stable release.
- Impact: Breaking changes may require significant refactoring
- Migration plan: Pin to specific version. Watch Laravel 12 release notes. Plan upgrade path to Laravel 12 stable.

**Livewire v4:**
- Risk: Major version release from v3. New patterns and best practices.
- Impact: Some components may not follow v4 best practices (e.g., attribute usage).
- Migration plan: Review Livewire v4 upgrade guide. Audit components for new patterns.

**DomPDF:**
- Risk: PDF generation library with known limitations (CSS support, memory usage)
- Impact: Large reports may fail. Styling inconsistencies.
- Migration plan: Consider alternative like Puppeteer or wkhtmltopdf for complex PDFs. Add memory limits for PDF generation.

**No External API SDKs:**
- Risk: DeepSeek API called directly via curl without SDK
- Impact: Manual error handling, no retry logic, no request signing
- Migration plan: Create dedicated API client class with proper error handling, retries, and rate limiting.

## Missing Critical Features

**Database Indexes:**
- Problem: No explicit indexes defined in migrations for common query patterns
- What's missing: Composite indexes on (status, created_at), (priority, status), foreign keys
- Blocks: Performance optimization as data grows
- Priority: High

**Request Validation:**
- Problem: Some API controllers rely on form requests but Livewire components use inline validation
- What's missing: Consistent validation rules across API and Livewire endpoints
- Blocks: DRY principle, maintenance burden
- Priority: Medium

**Error Handling:**
- Problem: No global exception handler for API errors
- What's missing: Standardized JSON error responses, error logging, user-friendly messages
- Blocks: Good API UX, debugging
- Priority: Medium

**Rate Limiting:**
- Problem: No rate limiting on API endpoints or export functionality
- What's missing: Throttling on issue creation, export requests, API calls
- Blocks: Protection against abuse, resource exhaustion
- Priority: High

**Audit Trail:**
- Problem: Activity log exists but no audit UI for reviewing changes
- What's missing: Admin view to see who changed what and when
- Blocks: Compliance, debugging, accountability
- Priority: Low

## Test Coverage Gaps

**Untested Services:**
- What's not tested: ReportService, IssueService, ExportService business logic
- Files: `app/Services/*.php`
- Risk: Regression bugs when refactoring report generation or issue management
- Priority: High

**Untested Policies:**
- What's not tested: Authorization logic in IssuePolicy and other policies
- Files: `app/Policies/*.php`
- Risk: Unauthorized access if policy logic changes
- Priority: High

**Untested Livewire Components:**
- What's not tested: All Livewire component interactions
- Files: `app/Livewire/**/*.php`
- Risk: UI bugs, state management issues, broken workflows
- Priority: Medium

**Untested Console Commands:**
- What's not tested: DeepSeek categorization command, migration command
- Files: `app/Console/Commands/*.php`
- Risk: Batch operations fail silently, data corruption
- Priority: Medium

**Missing Browser Tests:**
- What's not tested: End-to-end user workflows
- Risk: Integration issues between components, broken user journeys
- Priority: Low

## Configuration Concerns

**Debug Mode in Production:**
- Issue: `.env.example` has `APP_DEBUG=true` which may be copied to production
- Files: `.env.example:4`
- Impact: Exposes stack traces and sensitive information in production
- Fix approach: Change example to `APP_DEBUG=false`. Add deployment check to verify debug is off.

**Default Session Driver:**
- Issue: Session stored in database by default
- Files: `.env.example:30`
- Impact: Extra database load. Session table cleanup required.
- Fix approach: Consider Redis for sessions in production. Add session pruning job.

**No Queue Worker Configuration:**
- Issue: Queue configured but no supervisor/systemd config for workers
- Files: `.env.example:38` defines queue but no worker process management
- Impact: Queue jobs won't run without manual worker startup
- Fix approach: Add Laravel Horizon or supervisor configuration for production.

---

*Concerns audit: 2026-03-28*
