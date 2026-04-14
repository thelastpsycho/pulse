---
phase: 02-issue-list-experience
plan: 02b
type: execute
wave: 3
depends_on:
  - 02-02a
files_modified:
  - app/Livewire/Issues/Index.php
  - resources/views/livewire/issues/index.blade.php
autonomous: true
requirements:
  - LIST-01
  - LIST-06

must_haves:
  truths:
    - "User can see Kanban board with three columns: Open, In Progress, Closed"
    - "User can drag issue cards between columns to change status"
    - "Dropped cards show 300ms success flash animation"
    - "On mobile (< 768px), columns stack vertically with horizontal scroll"
    - "On desktop (≥ 768px), columns display side-by-side with gap-6"
    - "Drag-and-drop works with mouse and touch interactions"
    - "Keyboard navigation: Space to grab, Arrow keys to move, Enter to drop"
  artifacts:
    - path: "app/Livewire/Issues/Index.php"
      provides: "Methods for Kanban drag-and-drop and filtered issue data"
      contains: "kanbanIssues computed property, updateIssueStatus() listener"
    - path: "resources/views/livewire/issues/index.blade.php"
      provides: "Kanban board integration with conditional rendering"
      contains: "@if(\$viewMode === 'kanban')"
  key_links:
    - from: "resources/views/livewire/issues/index.blade.php"
      to: "resources/views/components/kanban-board.blade.php"
      via: "x-kanban-board component with issue data"
      pattern: "<x-kanban-board\\s+:openIssues="
    - from: "resources/js/kanban.js"
      to: "app/Livewire/Issues/Index.php"
      via: "Livewire events for drag-and-drop"
      pattern: "\\$wire\\.emit\\('updateIssueStatus'\\)"
---

<objective>
Integrate Kanban board components into Issues Index with Livewire data binding and event handling.

Purpose: Connect Kanban components to Livewire backend, enabling real-time status updates via drag-and-drop.
Output: Fully functional Kanban board with drag-and-drop, filtered by current search criteria, responsive on mobile and desktop.
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
@resources/views/components/kanban-board.blade.php
@resources/views/components/kanban-column.blade.php
@resources/views/components/kanban-card.blade.php
@resources/js/kanban.js
@resources/views/livewire/issues/index.blade.php
@app/Livewire/Issues/Index.php
@app/Models/Issue.php
@app/Services/IssueService.php
</context>

<tasks>

<task type="auto">
  <name>Task 1: Add Kanban-specific methods to Issues Index component</name>
  <files>app/Livewire/Issues/Index.php</files>
  <read_first>
    - app/Livewire/Issues/Index.php (existing component methods)
    - resources/js/kanban.js (emits these Livewire events)
  </read_first>
  <action>
    Add Kanban-related methods to Index.php:

    1. Add computed property to get issues by status:
    ```php
    #[Computed]
    public function kanbanIssues(): array
    {
        $issues = $this->getIssues()->get();

        return [
            'open' => $issues->filter(fn($issue) => $issue->status === 'open'),
            'in_progress' => $issues->filter(fn($issue) => $issue->status === 'in_progress'),
            'closed' => $issues->filter(fn($issue) => $issue->status === 'closed'),
        ];
    }
    ```

    2. Add Livewire listeners for drag-and-drop events:
    ```php
    #[On('dragStart')]
    public function onDragStart(array $data): void
    {
        // Optional: Track drag start for analytics
        // Currently no action needed
    }

    #[On('dragEnd')]
    public function onDragEnd(array $data): void
    {
        // Optional: Track drag end for analytics
        // Currently no action needed
    }

    #[On('updateIssueStatus')]
    public function updateIssueStatus(array $data): void
    {
        $issueId = $data['issueId'];
        $newStatus = $data['newStatus'];

        $issue = Issue::findOrFail($issueId);
        $this->authorize('update', $issue);

        // Map status to issue state
        match($newStatus) {
            'open' => $this->issueService->reopen($issue),
            'in_progress' => $this->issueService->update($issue, ['status' => 'in_progress']),
            'closed' => $this->issueService->close($issue),
            default => null,
        };

        // Refresh the list
        $this->dispatch('issue-updated');
    }
    ```

    3. Update getIssues() to respect Kanban filters:
    - Already respects filters via $this->tab, $this->search, etc.
    - No changes needed — Kanban uses same filtered result set

    These methods handle the Livewire events emitted by SortableJS, translating drag-and-drop actions into issue status changes.
  </action>
  <verify>
    <automated>grep -n "updateIssueStatus\|kanbanIssues" app/Livewire/Issues/Index.php</automated>
  </verify>
  <done>
    - kanbanIssues computed property added
    - Returns array with 'open', 'in_progress', 'closed' keys
    - Filters issues by status from getIssues() result
    - onDragStart() listener added with #[On('dragStart')]
    - onDragEnd() listener added with #[On('dragEnd')]
    - updateIssueStatus() listener added with #[On('updateIssueStatus')]
    - updateIssueStatus() finds issue, authorizes, calls service method
    - Match expression maps status to service methods (reopen, update, close)
    - issue-updated event dispatched after status change
  </done>
</task>

<task type="auto">
  <name>Task 2: Integrate Kanban board into Issues Index view</name>
  <files>resources/views/livewire/issues/index.blade.php</files>
  <read_first>
    - resources/views/livewire/issues/index.blade.php (current layout)
    - resources/views/components/kanban-board.blade.php (Kanban component)
    - app/Livewire/Issues/Index.php (kanbanIssues computed property)
  </read_first>
  <action>
    Modify index.blade.php to integrate Kanban board:

    1. Add conditional rendering after the table/card list section:
    ```blade
    <!-- Kanban Board (conditional) -->
    @if($viewMode === 'kanban')
        <x-kanban-board
            :openIssues="$this->kanbanIssues['open']"
            :progressIssues="$this->kanbanIssues['in_progress']"
            :closedIssues="$this->kanbanIssues['closed']"
        />
    @endif
    ```

    2. Update existing table/card list to only show when viewMode is 'table':
    ```blade
    <!-- Table/Card List (conditional) -->
    @if($viewMode === 'table')
        <!-- Existing table/card code here -->
    @endif
    ```

    3. Add Alpine.js event listeners for keyboard drag-and-drop (optional enhancement):
    ```blade
    <div x-data="{
        keyboardDrag: null,
        handleKeyboardDrag(event, issueId, currentColumn) {
            if (event.key === ' ') {
                event.preventDefault();
                this.keyboardDrag = { issueId, currentColumn };

                // Announce to screen reader
                const card = event.target.closest('.kanban-card');
                card?.setAttribute('aria-grabbed', 'true');
            }

            if (this.keyboardDrag && event.key === 'ArrowRight') {
                event.preventDefault();
                // Move to next column (implementation would need column refs)
            }

            if (this.keyboardDrag && event.key === 'ArrowLeft') {
                event.preventDefault();
                // Move to previous column
            }

            if (this.keyboardDrag && event.key === 'Enter') {
                event.preventDefault();
                // Drop in current column
                @this.call('updateIssueStatus', {
                    issueId: this.keyboardDrag.issueId,
                    newStatus: currentColumn
                });
                this.keyboardDrag = null;
            }

            if (event.key === 'Escape') {
                this.keyboardDrag = null;
                // Cancel drag
                const card = event.target.closest('.kanban-card');
                card?.setAttribute('aria-grabbed', 'false');
            }
        }
    }">
        <!-- Kanban board here -->
    </div>
    ```

    This ensures:
    - Only one view displays at a time (table OR Kanban)
    - Kanban board receives filtered issues via computed property
    - Keyboard navigation support for accessibility
  </action>
  <verify>
    <automated>grep -n "kanban-board" resources/views/livewire/issues/index.blade.php</automated>
  </verify>
  <done>
    - Kanban board conditionally rendered when viewMode === 'kanban'
    - Table/list view conditionally rendered when viewMode === 'table'
    - Kanban board receives open, progress, and closed issues
    - Issues sourced from $this->kanbanIssues computed property
    - Views never display simultaneously
    - Alpine.js keyboard drag handlers added
    - Keyboard shortcuts: Space (grab), Arrows (move), Enter (drop), Escape (cancel)
  </done>
</task>

<task type="auto">
  <name>Task 3: Add Kanban keyboard navigation and accessibility enhancements</name>
  <files>resources/views/livewire/issues/index.blade.php, resources/js/kanban.js</files>
  <read_first>
    - resources/views/livewire/issues/index.blade.php (current integration)
    - resources/js/kanban.js (current keyboard support)
    - .planning/phases/02-issue-list-experience/02-UI-SPEC.md (accessibility requirements)
  </read_first>
  <action>
    Enhance keyboard navigation and accessibility:

    1. Update kanban-card component in resources/views/components/kanban-card.blade.php:
    - Add better keyboard event handlers
    - Improve ARIA attributes
    - Add screen reader announcements

    Modify the card div:
    ```blade
    <div
        draggable="true"
        class="kanban-card group relative bg-surface border border-border rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200 cursor-move"
        data-issue-id="{{ $issue->id }}"
        x-data="{
            dragged: false,
            handleKeyDown(event) {
                const card = event.currentTarget;
                const column = card.closest('.kanban-column')?.getAttribute('data-column');

                switch(event.key) {
                    case ' ':
                    case 'Enter':
                        event.preventDefault();
                        this.dragged = true;
                        card.setAttribute('aria-grabbed', 'true');
                        card.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
                        // Announce to screen reader
                        this.$dispatch('announce', 'Dragging issue {{ $issue->id }}');
                        break;
                    case 'Escape':
                        event.preventDefault();
                        this.dragged = false;
                        card.setAttribute('aria-grabbed', 'false');
                        card.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
                        this.$dispatch('announce', 'Drag cancelled');
                        break;
                    case 'ArrowRight':
                        if (this.dragged && column !== 'closed') {
                            event.preventDefault();
                            // Move to next column
                            @this.call('updateIssueStatus', {
                                issueId: {{ $issue->id }},
                                newStatus: column === 'open' ? 'in_progress' : 'closed'
                            });
                        }
                        break;
                    case 'ArrowLeft':
                        if (this.dragged && column !== 'open') {
                            event.preventDefault();
                            // Move to previous column
                            @this.call('updateIssueStatus', {
                                issueId: {{ $issue->id }},
                                newStatus: column === 'closed' ? 'in_progress' : 'open'
                            });
                        }
                        break;
                }
            }
        }"
        @dragstart="$wire.emit('dragStart', {{ $issue->id }}); dragged = true"
        @dragend="$wire.emit('dragEnd', {{ $issue->id }}); dragged = false"
        @keydown.window="handleKeyDown($event)"
        tabindex="0"
        role="button"
        aria-label="Issue: {{ $issue->title }}, Status: {{ $issue->status }}, Press Space or Enter to drag, Arrow keys to move, Escape to cancel"
        :aria-grabbed="dragged ? 'true' : 'false'"
    >
    <!-- Rest of card content -->
    </div>
    ```

    2. Add live region for screen reader announcements to index.blade.php:
    ```blade
    <!-- Live region for accessibility announcements -->
    <div
        x-data="{ announce: '' }"
        @announce.window="announce = $event.detail; setTimeout(() => announce = '', 1000)"
        aria-live="polite"
        aria-atomic="true"
        class="sr-only"
        x-text="announce"
    ></div>
    ```

    3. Add .sr-only utility class to resources/css/app.css:
    ```css
    /* Screen reader only */
    .sr-only {
        @apply absolute w-px h-px p-0 -m-px overflow-hidden whitespace-nowrap border-0;
        clip: rect(0, 0, 0, 0);
    }
    ```

    These enhancements provide:
    - Full keyboard navigation (Space/Enter to grab, Arrows to move, Escape to cancel)
    - Screen reader announcements for drag state
    - Visual feedback for keyboard drag (ring indicator)
    - Proper ARIA attributes (aria-grabbed, aria-label)
  </action>
  <verify>
    <automated>grep -n "aria-grabbed\|handleKeyDown\|announce" resources/views/components/kanban-card.blade.php resources/views/livewire/issues/index.blade.php</automated>
  </verify>
  <done>
    - kanban-card has handleKeyDown method with full keyboard support
    - Space/Enter enters drag mode with visual ring indicator
    - Arrow keys move card between columns when dragging
    - Escape cancels drag and removes visual indicator
    - aria-grabbed attribute updates dynamically
    - Live region added for screen reader announcements
    - .sr-only utility class added to app.css
    - All keyboard interactions prevent default browser behavior
  </done>
</task>

</tasks>

<verification>
After completing all tasks:

1. Test Kanban board rendering:
   - Navigate to issues page
   - Click "Board View" toggle
   - Verify three columns display: Open, In Progress, Closed
   - Verify column backgrounds have different opacity levels
   - Verify issues are filtered by current search criteria

2. Test drag-and-drop (mouse):
   - Drag a card from "Open" to "In Progress"
   - Verify card shows 50% opacity while dragging
   - Verify drop zone shows dashed border
   - Verify card shows 300ms success flash after drop
   - Refresh page → verify status change persisted

3. Test drag-and-drop (touch):
   - On mobile device or browser dev tools (touch emulation)
   - Touch and drag card to different column
   - Verify touch target is at least 44px
   - Verify smooth dragging animation

4. Test keyboard navigation:
   - Tab to focus on a card
   - Press Space → aria-grabbed="true", ring indicator appears
   - Press Arrow Right → card moves to next column
   - Press Arrow Left → card moves to previous column
   - Press Escape → drag cancelled, ring indicator removed
   - Verify screen reader announces drag state changes

5. Test responsive layout:
   - Desktop (≥ 768px): Columns side-by-side
   - Mobile (< 768px): Columns stack vertically
   - Each column full width on mobile

6. Test authorization:
   - Try dragging card without update permission → should fail
   - Verify policy check: $this->authorize('update', $issue)

7. Test dark mode:
   - Switch to light mode
   - Verify Kanban board displays correctly
   - Verify column backgrounds are visible
   - Verify drop zone border is visible

8. Test view toggle:
   - Switch between table and Kanban views
   - Verify filters persist across view changes
   - Verify selected issues persist (if applicable)
</verification>

<success_criteria>
- [ ] Kanban board displays with three columns
- [ ] Cards can be dragged between columns using mouse
- [ ] Cards can be dragged between columns using touch
- [ ] Dropped cards show 300ms success flash animation
- [ ] Status changes persist after page refresh
- [ ] Keyboard navigation works (Space, Arrows, Enter, Escape)
- [ ] Mobile layout: columns stack vertically
- [ ] Desktop layout: columns side-by-side
- [ ] Authorization checked before status changes
- [ ] Both light and dark modes display correctly
- [ ] Screen reader announcements work
- [ ] View toggle switches between table and Kanban
- [ ] Filters apply to Kanban board
</success_criteria>

<output>
After completion, create `.planning/phases/02-issue-list-experience/02-02b-SUMMARY.md`
</output>
