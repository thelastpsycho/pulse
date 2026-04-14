---
phase: 02-issue-list-experience
plan: 03b
type: execute
wave: 4
depends_on:
  - 02-03a
files_modified:
  - app/Livewire/Issues/Index.php
  - resources/views/livewire/issues/index.blade.php
autonomous: true
requirements:
  - LIST-02
  - LIST-03
  - LIST-04

must_haves:
  truths:
    - "User can save current filter combination with custom name"
    - "User can click saved filter chip to apply filters instantly"
    - "User can delete saved filters via X button on chip hover"
    - "User can select multiple issues using checkboxes"
    - "User can apply bulk actions (close, reopen, delete) to selected issues"
    - "Bulk action toolbar appears when 1+ issues selected"
    - "User can click 'View Details' to see issue in modal without leaving list"
    - "Quick view modal shows issue details, description, and recent activity"
    - "Modal closes on backdrop click, X button, or Escape key"
  artifacts:
    - path: "app/Livewire/Issues/Index.php"
      provides: "Methods for bulk actions, quick view, and saved filters"
      contains: "bulkClose(), bulkReopen(), bulkDelete(), openQuickView(), savedFilters property"
    - path: "resources/views/livewire/issues/index.blade.php"
      provides: "Integration of bulk toolbar, quick view modal, and saved filters"
      contains: "bulk-action-toolbar, quick-view-modal, saved filter chips"
  key_links:
    - from: "resources/views/livewire/issues/index.blade.php"
      to: "resources/views/components/bulk-action-toolbar.blade.php"
      via: "bulk action toolbar integration"
      pattern: "<x-bulk-action-toolbar\\s+:selectedCount="
    - from: "resources/views/livewire/issues/index.blade.php"
      to: "resources/views/components/quick-view-modal.blade.php"
      via: "quick view modal integration"
      pattern: "<x-quick-view-modal\\s+:issue="
    - from: "resources/views/livewire/issues/index.blade.php"
      to: "app/Livewire/Issues/Index.php"
      via: "saved filter chips rendered inline"
      pattern: "@foreach\\(\\\$this->savedFilters as \\\$savedFilter\\)"
---

<objective>
Integrate bulk action toolbar, quick view modal, and saved filter management into Issues Index view.

Purpose: Enable users to save frequently-used filter combinations, perform batch operations on multiple issues, and preview issue details without leaving the list page.
Output: Fully integrated bulk actions, quick view modal, and saved filter chips with create/delete functionality.
</objective>

<execution_context>
@$HOME/.claude/get-shit-done/workflows/execute-plan.md
@$HOME/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/PROJECT.md
@.planning/ROADMAP.md
@.planning/STATE.md
@.planning/phases/02-issue-list-experience/02-UI-SPEC.md
@resources/views/components/bulk-action-toolbar.blade.php
@resources/views/components/quick-view-modal.blade.php
@resources/views/components/checkbox-select.blade.php
@resources/views/livewire/issues/index.blade.php
@app/Livewire/Issues/Index.php
@app/Models/SavedFilter.php
@resources/css/app.css
</context>

<tasks>

<task type="auto">
  <name>Task 1: Add quick view and bulk selection methods to Issues Index</name>
  <files>app/Livewire/Issues/Index.php</files>
  <read_first>
    - app/Livewire/Issues/Index.php (existing methods)
    - resources/views/components/quick-view-modal.blade.php (uses these methods)
    - resources/views/components/bulk-action-toolbar.blade.php (uses these methods)
  </read_first>
  <action>
    Add quick view and bulk selection methods to Index.php:

    1. Add properties for quick view modal:
    ```php
    public ?int $quickViewIssueId = null;
    public bool $showQuickView = false;
    ```

    2. Add quick view methods:
    ```php
    public function openQuickView(int $issueId): void
    {
        $issue = Issue::findOrFail($issueId);
        $this->authorize('view', $issue);

        $this->quickViewIssueId = $issueId;
        $this->showQuickView = true;
    }

    public function closeQuickView(): void
    {
        $this->quickViewIssueId = null;
        $this->showQuickView = false;
    }

    #[Computed]
    public function quickViewIssue(): ?Issue
    {
        if (!$this->quickViewIssueId) {
            return null;
        }

        return Issue::with(['comments.user', 'createdBy', 'assignedTo', 'departments'])
            ->find($this->quickViewIssueId);
    }
    ```

    3. Add clear selection method (reusing existing functionality):
    ```php
    public function clearSelection(): void
    {
        $this->selectedIssues = [];
        $this->selectAll = false;
    }
    ```

    Note: closeSelected(), reopenSelected(), and deleteSelected() already exist in the component from prior implementation. Verify they exist and work correctly.

    These methods enable:
    - Opening quick view modal for any issue
    - Closing modal via button, backdrop, or Escape key
    - Loading issue with relationships for modal display
    - Clearing selection for bulk toolbar
  </action>
  <verify>
    <automated>grep -n "openQuickView\|closeQuickView\|clearSelection" app/Livewire/Issues/Index.php</automated>
  </verify>
  <done>
    - $quickViewIssueId property added
    - $showQuickView property added
    - openQuickView() method created with authorization check
    - closeQuickView() method created
    - quickViewIssue computed property created with eager loading
    - clearSelection() method created (or verified existing)
    - All methods have proper return type declarations
  </done>
</task>

<task type="auto">
  <name>Task 2: Integrate components into Issues Index view</name>
  <files>resources/views/livewire/issues/index.blade.php</files>
  <read_first>
    - resources/views/livewire/issues/index.blade.php (current layout)
    - resources/views/components/bulk-action-toolbar.blade.php (toolbar component)
    - resources/views/components/quick-view-modal.blade.php (modal component)
    - resources/views/components/checkbox-select.blade.php (checkbox component)
  </read_first>
  <action>
    Modify index.blade.php to integrate new components:

    1. Add bulk action toolbar after filters (REPLACE existing bulk action bar):
    ```blade
    <!-- Bulk Action Toolbar -->
    <x-bulk-action-toolbar
        :selectedCount="count($selectedIssues)"
        :canClose="auth()->check() && auth()->user()->can('close', \App\Models\Issue::class)"
        :canDelete="auth()->check() && auth()->user()->can('delete', \App\Models\Issue::class)"
    />
    ```

    2. Add "View Details" buttons to table and card views:
    - In table Actions column:
    ```blade
    <button
        wire:click="openQuickView({{ $issue->id }})"
        class="inline-action-button text-muted hover:text-primary"
        title="Quick view"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </button>
    ```

    - In mobile card view, add to action buttons:
    ```blade
    <button
        wire:click="openQuickView({{ $issue->id }})"
        class="mobile-action-button bg-primary text-white hover:bg-primary/90"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <span>View</span>
    </button>
    ```

    - In Kanban cards, add click handler to card:
    ```blade
    <div
        class="kanban-card ..."
        @click="openQuickView({{ $issue->id }})"
    >
        <!-- Card content -->
    </div>
    ```

    3. Add quick view modal at end of component (before closing div):
    ```blade
    <!-- Quick View Modal -->
    <x-quick-view-modal
        :issue="$this->quickViewIssue"
        :show="$showQuickView"
    />
    ```

    4. Update existing checkboxes to use checkbox-select component:
    - Replace inline checkboxes with:
    ```blade
    <x-checkbox-select
        :value="$issue->id"
        :checked="in_array($issue->id, $selectedIssues)"
        label="Select issue {{ $issue->id }}"
    />
    ```

    This integrates all three new components into the issue list.
  </action>
  <verify>
    <automated>grep -n "bulk-action-toolbar\|quick-view-modal\|checkbox-select" resources/views/livewire/issues/index.blade.php</automated>
  </verify>
  <done>
    - Bulk action toolbar component integrated
    - Toolbar receives selectedCount, canClose, canDelete props
    - Quick view button added to table Actions column
    - Quick view button added to mobile card actions
    - Kanban cards clickable to open quick view
    - Quick view modal rendered at end of component
    - Modal receives $this->quickViewIssue and $showQuickView props
    - Existing checkboxes replaced with checkbox-select component
    - All components render without errors
  </done>
</task>

<task type="auto">
  <name>Task 3: Enhance saved filter display with chips</name>
  <files>resources/views/livewire/issues/index.blade.php</files>
  <read_first>
    - resources/views/livewire/issues/index.blade.php (existing saved filters section)
    - app/Livewire/Issues/Index.php (savedFilters computed property)
  </read_first>
  <action>
    Enhance saved filters section in index.blade.php:

    The saved filters section already exists (lines 154-180 in current file). Enhance it with:

    1. Make saved filter chips more prominent:
    ```blade
    <!-- Saved Filters (Enhanced) -->
    @if(count($this->savedFilters) > 0)
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-muted flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    Saved Filters
                </span>
            </div>
            <div class="flex flex-wrap gap-2 overflow-x-auto pb-2 sm:pb-0">
                @foreach($this->savedFilters as $savedFilter)
                <div class="group relative inline-flex flex-shrink-0">
                    <button
                        wire:click="loadFilter({{ $savedFilter['id'] }})"
                        class="inline-flex items-center gap-2 rounded-lg border border-border/50 bg-surface-2/50 px-4 py-2 text-sm font-medium text-text transition-all duration-200 hover:border-primary/50 hover:bg-surface-2 hover:shadow-sm"
                    >
                        <svg class="h-3.5 w-3.5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        <span class="max-w-[150px] truncate">{{ $savedFilter['name'] }}</span>
                    </button>
                    <button
                        wire:click="deleteFilter({{ $savedFilter['id'] }})"
                        class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-danger text-white opacity-0 transition-all duration-200 group-hover:opacity-100 hover:bg-danger/80"
                        title="Delete filter '{{ $savedFilter['name'] }}'"
                    >
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    @endif
    ```

    2. Add "Save Filter" button to active filters section (already exists, ensure it's prominent):
    ```blade
    @if($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to)
        <div class="mt-4 flex flex-wrap items-center gap-2">
            <!-- Existing filter chips -->

            <!-- Save Filter Button (Enhanced) -->
            @if(auth()->check() && auth()->user()->can('viewAny', \App\Models\Issue::class))
            <button
                wire:click="openSaveFilterModal"
                class="ml-2 inline-flex items-center gap-1.5 rounded-lg border border-primary/50 bg-primary/10 px-3 py-1.5 text-sm font-semibold text-primary hover:bg-primary/20 transition-all duration-200"
                title="Save current filters"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
                Save Filter
            </button>
            @endif
        </div>
    @endif
    ```

    Enhancements:
    - Section heading with icon
    - Chips with better styling (rounded-lg, px-4 py-2)
    - Delete button visible on hover (red circle with X)
    - Truncated filter names with max-width
    - Horizontal scroll on mobile for many filters
    - Save Filter button styled with primary color to draw attention
  </action>
  <verify>
    <automated>grep -n "Saved Filters" resources/views/livewire/issues/index.blade.php | head -1</automated>
  </verify>
  <done>
    - Saved filters section has heading with icon
    - Filter chips display with bookmark icon
    - Chips have rounded-lg border with bg-surface-2/50
    - Delete button appears on hover (top-right red circle)
    - Filter names truncated with max-w-[150px]
    - Horizontal scroll on mobile (overflow-x-auto)
    - Save Filter button styled with primary color
    - Save Filter button has icon and text
    - All buttons have proper hover states
  </done>
</task>

</tasks>

<verification>
After completing all tasks:

1. Test bulk action toolbar:
   - Select one issue → toolbar appears with "1 issue selected"
   - Select multiple issues → count updates
   - Click "Close Selected" → verify authorization and success message
   - Click "Reopen" → verify authorization and success message
   - Click "Delete" → verify authorization and success message
   - Click "Clear" → selection cleared, toolbar disappears

2. Test quick view modal:
   - Click "View Details" button in table → modal opens
   - Click issue title in mobile card → modal opens
   - Click Kanban card → modal opens
   - Verify modal shows: ID, title, status, priority, departments, description
   - Verify details grid shows: created by, created at, assigned to, due date
   - Verify recent activity shows last 3 comments
   - Click X button → modal closes
   - Click backdrop → modal closes
   - Press Escape key → modal closes
   - Click "View Full Details" → navigates to issue detail page

3. Test checkbox selection:
   - Click checkbox → issue selected
   - Click "Select All" checkbox → all issues selected
   - Uncheck one issue → select all unchecked
   - Selection persists when changing tabs

4. Test saved filters:
   - Apply filters (e.g., department: Front Desk)
   - Click "Save Filter" → modal opens
   - Enter filter name → save
   - Verify chip appears in saved filters section
   - Click chip → filters applied, list refreshes
   - Hover over chip → delete button appears
   - Click delete → filter removed

5. Test responsive layouts:
   - Desktop: Bulk toolbar horizontal at top
   - Mobile: Bulk toolbar fixed at bottom, stacked buttons
   - Mobile: Saved filters scroll horizontally
   - Mobile: Quick view modal full-width with margin

6. Test accessibility:
   - Tab key navigates through all interactive elements
   - Focus indicators visible (ring-2 ring-primary/20)
   - Escape key closes modal
   - aria-labels present on checkboxes
   - role="dialog" and aria-modal on modal

7. Test authorization:
   - Without 'close' permission → Close button hidden
   - Without 'delete' permission → Delete button hidden
   - Try bulk action without permission → 403 error
</verification>

<success_criteria>
- [ ] Bulk action toolbar appears when issues selected
- [ ] Bulk close, reopen, delete actions work correctly
- [ ] Clear selection button deselects all issues
- [ ] Quick view modal opens from table, cards, and Kanban
- [ ] Modal displays all issue details correctly
- [ ] Modal closes via X, backdrop, and Escape key
- [ ] Checkboxes select/deselect issues
- [ ] Saved filters display as chips below filters
- [ ] Save Filter modal opens and saves filters
- [ ] Clicking saved filter chip applies filters
- [ ] Deleting saved filter removes chip
- [ ] All components work in both light and dark modes
- [ ] Mobile layouts are touch-friendly (44px minimum targets)
</success_criteria>

<output>
After completion, create `.planning/phases/02-issue-list-experience/02-03b-SUMMARY.md`
</output>
