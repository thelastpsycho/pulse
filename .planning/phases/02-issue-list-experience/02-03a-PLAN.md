---
phase: 02-issue-list-experience
plan: 03a
type: execute
wave: 2
depends_on:
  - 02-01
files_modified:
  - resources/views/components/bulk-action-toolbar.blade.php
  - resources/views/components/quick-view-modal.blade.php
  - resources/views/components/checkbox-select.blade.php
autonomous: true
requirements:
  - LIST-03
  - LIST-04

must_haves:
  truths:
    - "User can select multiple issues using checkboxes"
    - "User can apply bulk actions (close, reopen, delete) to selected issues"
    - "Bulk action toolbar appears when 1+ issues selected"
    - "User can click 'View Details' to see issue in modal without leaving list"
    - "Quick view modal shows issue details, description, and recent activity"
    - "Modal closes on backdrop click, X button, or Escape key"
  artifacts:
    - path: "resources/views/components/bulk-action-toolbar.blade.php"
      provides: "Toolbar with bulk action buttons and selection count"
      exports: ["bulk-action-toolbar"]
    - path: "resources/views/components/quick-view-modal.blade.php"
      provides: "Modal for viewing issue details without navigation"
      exports: ["quick-view-modal"]
    - path: "resources/views/components/checkbox-select.blade.php"
      provides: "Checkbox component for bulk selection"
      exports: ["checkbox-select"]
  key_links:
    - from: "resources/views/components/bulk-action-toolbar.blade.php"
      to: "app/Livewire/Issues/Index.php"
      via: "wire:click=\"bulkClose\""
      pattern: "wire:click=\\\"bulkClose\\\""
    - from: "resources/views/components/quick-view-modal.blade.php"
      to: "app/Livewire/Issues/Index.php"
      via: "wire:click=\"openQuickView({{ $issue->id }})\""
      pattern: "wire:click=\\\"openQuickView\\("
---

<objective>
Create bulk action toolbar, quick view modal, and checkbox components for enhanced issue list productivity.

Purpose: Enable users to perform batch operations on multiple issues and preview issue details without leaving the list page.
Output: Bulk action toolbar with multi-select, quick view modal with issue details, checkbox component for selection.
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
@resources/views/components/primary-button.blade.php
@resources/views/components/secondary-button.blade.php
@resources/views/components/danger-button.blade.php
@resources/views/components/success-button.blade.php
@resources/views/components/modal.blade.php
@resources/views/components/badge.blade.php
@resources/views/livewire/issues/index.blade.php
@app/Livewire/Issues/Index.php
@resources/css/app.css
</context>

<tasks>

<task type="auto">
  <name>Task 1: Create checkbox select component for bulk selection</name>
  <files>resources/views/components/checkbox-select.blade.php</files>
  <read_first>
    - resources/views/components/badge.blade.php (for attribute merging patterns)
    - resources/css/app.css (for focus styles and CSS variables)
  </read_first>
  <action>
    Create checkbox-select.blade.php component:

    Component signature:
    ```php
    @props([
        'value',           // Issue ID
        'checked' => false, // Checked state
        'label' => '',     // Optional label (for screen readers)
    ])
    ```

    Component structure:
    ```blade
    <label class="relative inline-flex items-center justify-center cursor-pointer group">
        <input
            type="checkbox"
            value="{{ $value }}"
            {{ $checked ? 'checked' : '' }}
            class="peer h-5 w-5 rounded-md border-2 border-border/50 bg-surface-2/50 text-primary transition-all duration-200 focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 focus:ring-offset-background cursor-pointer"
            @if($label)
                aria-label="{{ $label }}"
            @endif
        />
        <!-- Custom checkmark icon -->
        <svg class="pointer-events-none absolute h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>

        <!-- Selected state indicator (ring) -->
        <div class="pointer-events-none absolute inset-0 rounded-md border-2 border-transparent peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/20 transition-all duration-200"></div>

        {{ $slot }}
    </label>
    ```

    Styling requirements per UI-SPEC:
    - Default: border-border/50 bg-surface-2/50
    - Checked: bg-primary with white checkmark
    - Focus: focus:ring-2 focus:ring-primary/20
    - Selected state: border-primary with ring-2 ring-primary/20
    - Touch target: minimum 44px (h-5 w-5 checkbox, label provides padding)

    Accessibility:
    - aria-label when provided
    - Peer-checked state for ARIA
    - Focus ring visible on keyboard navigation
    - Cursor pointer on label for larger click target
  </action>
  <verify>
    <automated>test -f resources/views/components/checkbox-select.blade.php && grep -q "type=\"checkbox\"" resources/views/components/checkbox-select.blade.php</automated>
  </verify>
  <done>
    - Component file created at resources/views/components/checkbox-select.blade.php
    - Component accepts value, checked, and label props
    - Checkbox renders with h-5 w-5 (20px minimum touch target)
    - Custom checkmark SVG appears when checked
    - Selected state shows border-primary with ring-2
    - Focus styles: focus:ring-2 focus:ring-primary/20
    - aria-label attribute included when $label provided
    - Label wraps checkbox for larger click target
  </done>
</task>

<task type="auto">
  <name>Task 2: Create bulk action toolbar component</name>
  <files>resources/views/components/bulk-action-toolbar.blade.php</files>
  <read_first>
    - resources/views/components/success-button.blade.php (for close button styling)
    - resources/views/components/danger-button.blade.php (for delete button styling)
    - resources/views/components/secondary-button.blade.php (for secondary actions)
    - resources/css/app.css (for gradient border and button styles)
  </read_first>
  <action>
    Create bulk-action-toolbar.blade.php component:

    Component signature:
    ```php
    @props([
        'selectedCount' => 0,
        'canClose' => false,
        'canDelete' => false,
    ])
    ```

    Component structure:
    ```blade
    @if($selectedCount > 0)
    <div class="gradient-border rounded-2xl p-4 animate-fade-in fixed top-20 left-4 right-4 z-40 md:relative md:top-0 md:left-0 md:right-0 md:z-0">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <!-- Selection count -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-primary/20">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-text">{{ $selectedCount }} issue{{ $selectedCount > 1 ? 's' : '' }} selected</p>
                    <p class="text-xs text-muted">Apply bulk actions below</p>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-col gap-2 sm:flex-row md:gap-3">
                @if($canClose)
                <button wire:click="closeSelected" class="btn btn-success inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Close Selected</span>
                </button>
                @endif

                <button wire:click="reopenSelected" class="btn btn-primary inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reopen</span>
                </button>

                @if($canDelete)
                <button wire:click="deleteSelected" class="btn btn-danger inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Delete</span>
                </button>
                @endif

                <button wire:click="clearSelection" class="btn btn-secondary inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Clear</span>
                </button>
            </div>
        </div>
    </div>
    @endif
    ```

    Responsive behavior per UI-SPEC:
    - Mobile: Fixed at bottom (top-20 left-4 right-4), stacked buttons
    - Desktop: Relative position (md:relative), horizontal buttons
    - Gradient border for visual emphasis

    Action buttons:
    - Close: Success button (green gradient)
    - Reopen: Primary button (indigo gradient)
    - Delete: Danger button (red gradient)
    - Clear: Secondary button (surface with border)

    Only renders when $selectedCount > 0.
  </action>
  <verify>
    <automated>test -f resources/views/components/bulk-action-toolbar.blade.php && grep -q "selectedCount" resources/views/components/bulk-action-toolbar.blade.php</automated>
  </verify>
  <done>
    - Component file created at resources/views/components/bulk-action-toolbar.blade.php
    - Component accepts selectedCount, canClose, canDelete props
    - Selection count displays with icon and text
    - Four action buttons: Close, Reopen, Delete, Clear
    - Close button uses btn-success class
    - Reopen button uses btn-primary class
    - Delete button uses btn-danger class
    - Clear button uses btn-secondary class
    - Mobile layout: Fixed position, stacked buttons (flex-col)
    - Desktop layout: Relative position, horizontal buttons (md:flex-row)
    - Only renders when selectedCount > 0
  </done>
</task>

<task type="auto">
  <name>Task 3: Create quick view modal component</name>
  <files>resources/views/components/quick-view-modal.blade.php</files>
  <read_first>
    - resources/views/components/modal.blade.php (for modal structure patterns)
    - resources/views/components/badge.blade.php (for status badges)
    - resources/views/livewire/issues/index.blade.php (for modal integration)
  </read_first>
  <action>
    Create quick-view-modal.blade.php component:

    Component signature:
    ```php
    @props([
        'issue' => null,
        'show' => false,
    ])
    ```

    Component structure:
    ```blade
    @if($show && $issue)
    <div
        x-data="{
            show: {{ $show ? 'true' : 'false' }},
            close() {
                this.show = false;
                @this.closeQuickView();
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
        @click.self="close()"
        @keydown.escape="close()"
        role="dialog"
        aria-modal="true"
        aria-labelledby="quick-view-title"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50"></div>

        <!-- Modal content -->
        <div class="relative w-full max-w-2xl rounded-2xl border border-border bg-surface shadow-2xl max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border/50 bg-surface/95 backdrop-blur-sm px-6 py-4">
                <div class="flex items-center gap-3">
                    <h2 id="quick-view-title" class="text-xl font-semibold text-text">
                        #{{ $issue->id }}: {{ $issue->title }}
                    </h2>
                    @if($issue->isClosed())
                    <x-badge variant="success">Closed</x-badge>
                    @else
                    <x-badge variant="muted">Open</x-badge>
                    @endif
                </div>
                <button @click="close()" class="p-2 text-muted hover:text-text rounded-lg hover:bg-surface-2 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 space-y-4">
                <!-- Priority and badges -->
                <div class="flex flex-wrap items-center gap-2">
                    <x-badge variant="{{ match($issue->priority) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        default => 'muted'
                    }}">
                        {{ ucfirst($issue->priority) }}
                    </x-badge>
                    @foreach($issue->departments->take(3) as $department)
                    <x-badge variant="muted">{{ $department->name }}</x-badge>
                    @endforeach
                    @if($issue->departments->count() > 3)
                    <x-badge variant="muted">+{{ $issue->departments->count() - 3 }}</x-badge>
                    @endif
                </div>

                <!-- Description -->
                @if($issue->description)
                <div>
                    <h3 class="text-sm font-semibold text-text mb-2">Description</h3>
                    <div class="text-sm text-muted whitespace-pre-wrap">{{ $issue->description }}</div>
                </div>
                @endif

                <!-- Details grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-muted">Created by:</span>
                        <span class="ml-2 text-text">{{ $issue->createdBy->name ?? 'Unknown' }}</span>
                    </div>
                    <div>
                        <span class="text-muted">Created at:</span>
                        <span class="ml-2 text-text">{{ $issue->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-muted">Assigned to:</span>
                        <span class="ml-2 text-text">{{ $issue->assignedTo?->name ?? 'Unassigned' }}</span>
                    </div>
                    <div>
                        <span class="text-muted">Due date:</span>
                        <span class="ml-2 text-text">{{ $issue->due_date?->format('M d, Y') ?? 'No due date' }}</span>
                    </div>
                    @if($issue->location)
                    <div class="sm:col-span-2">
                        <span class="text-muted">Location:</span>
                        <span class="ml-2 text-text">{{ $issue->location }}</span>
                    </div>
                    @endif
                </div>

                <!-- Recent activity (last 3 comments) -->
                @if($issue->comments && $issue->comments->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-text mb-2">Recent Activity</h3>
                    <div class="space-y-2">
                        @foreach($issue->comments->take(-3) as $comment)
                        <div class="rounded-lg bg-surface-2/50 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-text">{{ $comment->user->name }}</span>
                                <span class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-muted">{{ \Illuminate\Support\Str::limit($comment->content, 150) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-border/50 bg-surface/95 backdrop-blur-sm px-6 py-4">
                <button @click="close()" class="btn btn-secondary">
                    Close
                </button>
                <a href="{{ route('issues.show', $issue) }}" class="btn btn-primary">
                    View Full Details
                </a>
            </div>
        </div>
    </div>
    @endif
    ```

    Modal behavior per UI-SPEC:
    - Open: 200ms fade-in + scale-in animation
    - Close: 150ms fade-out + scale-out animation
    - Trigger: Backdrop click, X button, Escape key
    - Content: Read-only issue details (no editing)
    - Max width: 600px on desktop, 100% on mobile with margin-4
    - Sticky header and footer with backdrop-blur

    Accessibility:
    - role="dialog"
    - aria-modal="true"
    - aria-labelledby for title
    - Focus trap (Alpine.js handles via x-show)
    - Escape key: Close modal
  </action>
  <verify>
    <automated>test -f resources/views/components/quick-view-modal.blade.php && grep -q "role=\"dialog\"" resources/views/components/quick-view-modal.blade.php</automated>
  </verify>
  <done>
    - Component file created at resources/views/components/quick-view-modal.blade.php
    - Component accepts $issue and $show props
    - Modal only renders when $show && $issue
    - Header displays issue ID, title, status badge
    - Close button (X) in header
    - Body displays priority, department badges, description
    - Details grid shows created by, created at, assigned to, due date, location
    - Recent activity shows last 3 comments
    - Footer has Close and View Full Details buttons
    - Animations: 200ms enter, 150ms leave
    - Closes on backdrop click, X button, Escape key
    - role="dialog", aria-modal="true" present
    - aria-labelledby references title ID
  </done>
</task>

</tasks>

<verification>
After completing all tasks:

1. Test component rendering:
   - Components compile without errors
   - checkbox-select.blade.php renders with checkbox and checkmark
   - bulk-action-toolbar.blade.php has all four action buttons
   - quick-view-modal.blade.php has complete modal structure

2. Test checkbox component:
   - Checkbox displays with default styling
   - Checkmark appears when checked
   - Focus ring visible on keyboard navigation
   - Touch target is adequate (minimum 44px)

3. Test bulk action toolbar component:
   - Toolbar only shows when selectedCount > 0
   - Selection count displays correctly
   - All four buttons present (Close, Reopen, Delete, Clear)
   - Mobile layout: Fixed position, stacked buttons
   - Desktop layout: Relative position, horizontal buttons

4. Test quick view modal component:
   - Modal structure complete with header, body, footer
   - Animations defined (200ms enter, 150ms leave)
   - Close triggers present (X button, backdrop, Escape)
   - Accessibility attributes present (role, aria-modal, aria-labelledby)
   - Responsive layout (max-w-2xl on desktop, 100% on mobile)

Note: Full integration and testing happens in plan 02-03b.
</verification>

<success_criteria>
- [ ] Checkbox component created with proper styling
- [ ] Bulk action toolbar component created with all buttons
- [ ] Quick view modal component created with complete structure
- [ ] All components have proper ARIA attributes
- [ ] All components are responsive
- [ ] All components use design system variables
- [ ] Components compile without errors
- [ ] Components are self-contained and testable
</success_criteria>

<output>
After completion, create `.planning/phases/02-issue-list-experience/02-03a-SUMMARY.md`
</output>
