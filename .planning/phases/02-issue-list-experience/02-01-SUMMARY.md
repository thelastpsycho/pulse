---
phase: 02-issue-list-experience
plan: 01
type: execute
subsystem: Issue List Experience
tags: [ui-component, responsive-design, view-toggle]
wave: 1
dependency_graph:
  requires: []
  provides: [view-toggle-component, table-view-layout, mobile-responsive-cards]
  affects: [issues-index-page]
tech_stack:
  added: []
  patterns: [responsive-breakpoints, touch-targets, css-variables, alpine-state-management]
key_files:
  created:
    - resources/views/components/view-toggle.blade.php
  modified:
    - app/Livewire/Issues/Index.php
    - resources/views/livewire/issues/index.blade.php
    - resources/css/app.css
decisions: []
metrics:
  duration: "5 minutes"
  completed_date: "2026-03-28"
---

# Phase 02 Plan 01: View Toggle and Responsive Table Layout Summary

## One-Liner
Implemented view toggle component with table/board switching, responsive table layout with inline actions, and mobile-optimized card layout with 44px touch targets.

## Implementation Overview

This plan successfully created a view toggle component that allows users to switch between table and board views, implemented an enhanced responsive table view for desktop, and ensured mobile displays maintain touch-friendly card layouts.

### Tasks Completed

**Task 1: Create view toggle component**
- Created `resources/views/components/view-toggle.blade.php` with segmented control design
- Two tabs: "Table View" and "Board View" per UI-SPEC copywriting
- Alpine.js state management with `wire:model.live` binding to Livewire component
- ARIA attributes for accessibility (`role="tablist"`, `aria-selected`)
- Keyboard navigation support (arrow keys, Enter to select)
- Inline SVG icons (grid for table, columns for board)
- Active tab highlighting with bg-primary and bottom indicator line

**Task 2: Add view mode property to Issues Index component**
- Added `public string $viewMode = 'table'` property to `Index.php`
- Added to `$queryString` array for URL persistence across navigation
- Created `setViewMode()` method with validation (restricts to 'table' and 'kanban')
- Ensures only valid view modes are accepted, preventing invalid states

**Task 3: Integrate view toggle and create enhanced table view layout**
- Added view-toggle component to page header between heading and action buttons
- Created responsive table view for desktop (>= 768px breakpoint):
  - 7 columns: Select, Issue, Status, Priority, Department, Assigned, Actions
  - Inline action buttons (close, reopen, view) in Actions column
  - Hover effects on table rows
  - Status and priority badges using existing badge component
- Preserved mobile card layout with enhancements:
  - Added `hidden md:block` to table wrapper (desktop only)
  - Added `block md:hidden` to mobile card wrapper (mobile only)
  - Enhanced mobile action buttons with 44px height (h-11) for touch targets
- Conditional rendering ensures only one layout displays at a time

**Task 4: Add responsive CSS styles for table and card layouts**
- Added `.table-row-hover` utility for smooth hover transitions
- Added `@media (max-width: 640px)` block with mobile-specific styles:
  - `.mobile-card` for card container styling
  - `.mobile-card-actions` for action button container
  - `.mobile-action-button` with h-11 (44px minimum touch target)
- Added `.inline-action-button` focus styles with proper ring indicators
- All styles use CSS variables for dark mode compatibility

## Deviations from Plan

### Auto-fixed Issues

None - plan executed exactly as written.

## Technical Details

### View Toggle Component Design
- **Pattern**: Segmented control (connected tabs)
- **State Management**: Alpine.js local state with Livewire property binding
- **Accessibility**: Full ARIA support with keyboard navigation
- **Visual Feedback**: Active tab has bg-primary, shadow, and bottom indicator line
- **Icons**: Inline SVG icons (Heroicon-style) for visual clarity

### Responsive Breakpoints
- **Mobile**: < 768px (md:) - displays card layout with stacked information
- **Desktop**: >= 768px (md:) - displays table view with inline actions
- Breakpoints align with Tailwind CSS defaults for consistency

### Touch Targets
- Mobile action buttons: 44px height (h-11) - meets WCAG 2.1 AA guidelines
- Inline action buttons: 32px (p-2) on desktop for compact display
- All buttons have proper hover and focus states

### URL Persistence
- View mode persists via query parameter (`?viewMode=table` or `?viewMode=kanban`)
- Users can share links with specific view mode
- Page refresh maintains selected view

## Known Stubs

None - all functionality is fully implemented and wired.

## Testing Notes

### Manual Testing Required
1. **View Toggle Functionality**:
   - Click "Table View" → verify table displays on desktop, cards on mobile
   - Click "Board View" → will display Kanban board (implemented in plan 02-02a)
   - Refresh page → verify view preference persists via URL parameter

2. **Table View on Desktop**:
   - Verify all 7 columns display correctly
   - Test inline action buttons (close, reopen, view) trigger correct actions
   - Verify hover effects on rows work smoothly
   - Check status and priority badges display with correct colors

3. **Mobile Responsive Layout**:
   - On mobile width (< 768px): verify cards display, table is hidden
   - Verify action buttons are minimum 44px height
   - Test touch targets are easy to tap
   - Verify information is stacked and readable

4. **Accessibility**:
   - Tab key navigates through table rows and view toggle
   - Focus indicators visible on all interactive elements
   - ARIA attributes present on view toggle component

5. **Dark Mode**:
   - Table displays correctly in both light and dark modes
   - Mobile cards use correct background colors via CSS variables
   - All text is readable with proper contrast

## Files Modified

### Created
- `resources/views/components/view-toggle.blade.php` - View toggle component with Alpine.js state management

### Modified
- `app/Livewire/Issues/Index.php` - Added `$viewMode` property with query string binding and validation
- `resources/views/livewire/issues/index.blade.php` - Integrated view toggle, created responsive table layout, enhanced mobile cards
- `resources/css/app.css` - Added responsive styles for table rows and mobile action buttons

## Next Steps

This plan provides the foundation for view switching. The next plan (02-02a) will implement the Kanban board view that users can toggle to using the component created here.
