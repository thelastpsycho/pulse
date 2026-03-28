# Pulse

## What This Is

Pulse is an internal issue tracking system for hotel/DM (Departmental Manager) operations. Staff create, assign, track, and close issues related to hotel operations across departments. The system provides reporting and statistics for management oversight.

## Core Value

**Hotel operations teams resolve issues quickly and transparently.**

If issues can be tracked, assigned, and closed efficiently — with visibility into what needs attention — the system succeeds. Everything else supports this.

## Requirements

### Validated

<!-- Existing capabilities from codebase analysis -->

- ✓ User authentication and role-based access control — existing
- ✓ Issue CRUD (create, read, update, delete) — existing
- ✓ Issue status workflow (open → in progress → closed) — existing
- ✓ Department and issue type categorization — existing
- ✓ Issue comments and activity logging — existing
- ✓ Monthly and yearly PDF reports — existing
- ✓ Basic statistics page — existing
- ✓ Role-based permissions (SuperAdmin, Admin, User) — existing
- ✓ API endpoints for all resources — existing

### Active

<!-- Current scope - what we're building -->

**Issue List UX:**
- [ ] Kanban board view with drag-and-drop between status columns
- [ ] Saved filters (save and name filter combinations)
- [ ] Bulk actions (select multiple to close, assign, change status)
- [ ] Quick view modal (peek at issue details without leaving list)
- [ ] Enhanced table view with inline actions
- [ ] Mobile-responsive list view (card layout on small screens)

**Issue Detail Page:**
- [ ] Timeline view of activity log with user avatars
- [ ] Related issues linking (link issues together)
- [ ] Attachments gallery with drag-drop uploads and preview
- [ ] Enhanced comments with @mentions support
- [ ] Status badge pills (color-coded, easy to scan)
- [ ] Mobile-optimized detail layout

**Statistics Dashboard:**
- [ ] Interactive charts (Issue volume trends, department breakdown, priority distribution)
- [ ] KPI cards (total issues, avg resolution time, overdue count, resolution rate)
- [ ] Date range filters (presets: Today, Last 7 days, Last 30 days, Custom)
- [ ] Department and status filters
- [ ] Responsive dashboard layout
- [ ] Mobile-optimized charts and cards

**Design System:**
- [ ] Minimalist design language (clean, fast, professional)
- [ ] Responsive layouts (desktop and mobile equally)
- [ ] Dark mode support
- [ ] Consistent component library

### Out of Scope

<!-- Explicitly excluded for now -->

- Enhanced Reports (custom date ranges, comparison views, scheduled reports) — deferred to v2
- Real-time notifications (WebSocket-based alerts) — deferred to v2
- Mobile native app — web responsive is sufficient for v1
- Email notifications — existing system adequate for now

## Context

**Technical Environment:**
- Laravel 12 + Livewire 4 for reactive UI
- Alpine.js for frontend interactivity
- Tailwind CSS for styling
- MySQL database (with SQLite for local dev)
- Role-based access control using custom RBAC

**Existing Pain Points (from codebase analysis):**
- Current issue list is basic table-only, no advanced filtering
- Statistics page exists but appears minimal
- Reports are PDF-only, limited date range options
- No Kanban view or drag-and-drop status management
- No saved filters for recurring views
- Issue detail view is basic, missing timeline and visual enhancements

**User Base:**
- Hotel/DM operations staff (create and assign issues)
- Department managers (review and resolve issues)
- Administration (oversight, reports, statistics)
- Mixed usage: desktop in office, mobile/tablet in the field

## Constraints

- **Tech Stack**: Laravel + Livewire + Alpine.js + Tailwind — must stay within this ecosystem
- **Browser Support**: Modern browsers (Chrome, Edge, Safari, Firefox) — mobile and desktop
- **Performance**: Charts and dashboards must load quickly; consider server-side aggregation
- **Mobile**: Touch-friendly interfaces must work on tablets and phones
- **Offline**: No offline capability requirement (always connected)
- **Accessibility**: WCAG 2.1 AA compliance where feasible

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Minimalist design style | User preference; fast loading; professional for business use | — Pending |
| Mobile-first responsive | Staff use devices in the field; both desktop/mobile equally important | — Pending |
| Charts via library (ApexCharts/Chart.js) | Need interactive charts; Livewire-friendly integration | — Pending |
| Kanban for status workflow | Visual management; faster than table for status changes | — Pending |
| Dark mode support | Staff work various shifts; eye strain reduction | — Pending |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd:transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd:complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-03-28 after initialization*
