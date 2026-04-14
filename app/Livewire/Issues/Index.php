<?php

namespace App\Livewire\Issues;

use App\Models\Issue;
use App\Models\Department;
use App\Models\IssueType;
use App\Models\User;
use App\Models\SavedFilter;
use App\Services\IssueService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $tab = 'all'; // all, open or closed
    public string $search = '';
    public ?int $department_id = null;
    public ?int $issue_type_id = null;
    public ?int $category_id = null;
    public ?string $priority = null;
    public ?int $assigned_to = null;
    public ?string $date_from = null;
    public ?string $date_to = null;
    public string $order_by = 'issue_date';
    public string $order_dir = 'desc';
    public string $viewMode = 'table';

    public array $selectedIssues = [];
    public bool $selectAll = false;
    public string $savedFilterName = '';
    public bool $showSaveFilterModal = false;
    public ?int $quickViewIssueId = null;
    public bool $showQuickView = false;

    protected $queryString = [
        'tab' => ['except' => 'all'],
        'search' => ['except' => ''],
        'department_id' => ['except' => ''],
        'issue_type_id' => ['except' => ''],
        'category_id' => ['except' => ''],
        'priority' => ['except' => ''],
        'assigned_to' => ['except' => ''],
        'viewMode' => ['except' => 'table'],
    ];

    protected IssueService $issueService;

    public function boot(IssueService $issueService): void
    {
        $this->issueService = $issueService;
    }

    public function render()
    {
        $issues = $this->getIssues();

        return view('livewire.issues.index', [
            'issues' => $issues,
        ])
            ->layout('layouts.app')
            ->title('Issues');
    }

    protected function getIssues()
    {
        $filters = [
            'status' => $this->tab === 'all' ? null : ($this->tab === 'open' ? 'open' : 'closed'),
            'search' => $this->search,
            'department_id' => $this->department_id,
            'issue_type_id' => $this->issue_type_id,
            'category_id' => $this->category_id,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'order_by' => $this->order_by,
            'order_dir' => $this->order_dir,
        ];

        return $this->issueService->getFilteredIssues($filters);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        if (!in_array($mode, ['table', 'kanban'])) {
            return;
        }
        $this->viewMode = $mode;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedIssues = $this->getIssues()
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedIssues = [];
        }
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'department_id',
            'issue_type_id',
            'category_id',
            'priority',
            'assigned_to',
            'date_from',
            'date_to',
        ]);
        $this->resetPage();
    }

    public function closeSelected(): void
    {
        $this->authorize('close', Issue::class);

        $issues = Issue::whereIn('id', $this->selectedIssues)->open()->get();

        foreach ($issues as $issue) {
            $this->issueService->close($issue);
        }

        $this->selectedIssues = [];
        $this->selectAll = false;

        session()->flash('success', "{$issues->count()} issue(s) closed successfully.");
    }

    public function reopenSelected(): void
    {
        $this->authorize('reopen', Issue::class);

        $issues = Issue::whereIn('id', $this->selectedIssues)->closed()->get();

        foreach ($issues as $issue) {
            $this->issueService->reopen($issue);
        }

        $this->selectedIssues = [];
        $this->selectAll = false;

        session()->flash('success', "{$issues->count()} issue(s) reopened successfully.");
    }

    public function deleteSelected(): void
    {
        $this->authorize('delete', Issue::class);

        $count = Issue::whereIn('id', $this->selectedIssues)->delete();

        $this->selectedIssues = [];
        $this->selectAll = false;

        session()->flash('success', "{$count} issue(s) deleted successfully.");
    }

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

    public function clearSelection(): void
    {
        $this->selectedIssues = [];
        $this->selectAll = false;
    }

    public function closeIssue(int $issueId): void
    {
        $issue = Issue::findOrFail($issueId);
        $this->authorize('close', $issue);

        $this->issueService->close($issue);

        // Dispatch event to refresh the component
        $this->dispatch('issue-closed');

        session()->flash('success', 'Issue closed successfully.');
    }

    public function reopenIssue(int $issueId): void
    {
        $issue = Issue::findOrFail($issueId);
        $this->authorize('reopen', $issue);

        $this->issueService->reopen($issue);

        // Dispatch event to refresh the component
        $this->dispatch('issue-reopened');

        session()->flash('success', 'Issue reopened successfully.');
    }

    public function deleteIssue(int $issueId): void
    {
        $issue = Issue::findOrFail($issueId);
        $this->authorize('delete', $issue);

        $this->issueService->delete($issue);

        // Reset page and dispatch event to refresh
        $this->resetPage();
        $this->dispatch('issue-deleted');

        session()->flash('success', 'Issue deleted successfully.');
    }

    #[Computed]
    public function departments(): array
    {
        return Department::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    #[Computed]
    public function issueTypes(): array
    {
        return IssueType::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    #[Computed]
    public function users(): array
    {
        return User::orderBy('name')
            ->where('is_active', true)
            ->pluck('name', 'id')
            ->toArray();
    }

    #[Computed]
    public function priorities(): array
    {
        return [
            'urgent' => 'Urgent',
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low',
        ];
    }

    #[On('issue-created')]
    #[On('issue-updated')]
    #[On('issue-closed')]
    #[On('issue-reopened')]
    #[On('issue-deleted')]
    public function refresh(): void
    {
        // Reset pagination to ensure fresh data
        $this->resetPage();
    }

    public function exportCSV(\App\Services\ExportService $exportService)
    {
        $this->authorize('viewAny', Issue::class);

        // Get all issues matching current filters (without pagination)
        $filters = [
            'status' => $this->tab === 'all' ? null : ($this->tab === 'open' ? 'open' : 'closed'),
            'search' => $this->search,
            'department_id' => $this->department_id,
            'issue_type_id' => $this->issue_type_id,
            'category_id' => $this->category_id,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'order_by' => $this->order_by,
            'order_dir' => $this->order_dir,
        ];

        $issues = $this->issueService->getFilteredIssues($filters, usePagination: false);

        return $exportService->exportIssuesToCSV($issues);
    }

    public function openSaveFilterModal(): void
    {
        $this->showSaveFilterModal = true;
    }

    public function closeSaveFilterModal(): void
    {
        $this->showSaveFilterModal = false;
        $this->savedFilterName = '';
    }

    public function saveFilter(): void
    {
        $this->validate([
            'savedFilterName' => 'required|string|max:255',
        ]);

        SavedFilter::create([
            'user_id' => auth()->id(),
            'name' => $this->savedFilterName,
            'filters' => [
                'tab' => $this->tab,
                'search' => $this->search,
                'department_id' => $this->department_id,
                'issue_type_id' => $this->issue_type_id,
                'category_id' => $this->category_id,
                'priority' => $this->priority,
                'assigned_to' => $this->assigned_to,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
            ],
        ]);

        $this->closeSaveFilterModal();

        session()->flash('success', 'Filter saved successfully.');
    }

    public function loadFilter(int $filterId): void
    {
        $savedFilter = SavedFilter::where('user_id', auth()->id())
            ->where('id', $filterId)
            ->firstOrFail();

        $filters = $savedFilter->filters;

        $this->tab = $filters['tab'] ?? 'all';
        $this->search = $filters['search'] ?? '';
        $this->department_id = $filters['department_id'] ?? null;
        $this->issue_type_id = $filters['issue_type_id'] ?? null;
        $this->category_id = $filters['category_id'] ?? null;
        $this->priority = $filters['priority'] ?? null;
        $this->assigned_to = $filters['assigned_to'] ?? null;
        $this->date_from = $filters['date_from'] ?? null;
        $this->date_to = $filters['date_to'] ?? null;

        $this->resetPage();

        session()->flash('success', "Filter '{$savedFilter->name}' loaded successfully.");
    }

    public function deleteFilter(int $filterId): void
    {
        $savedFilter = SavedFilter::where('user_id', auth()->id())
            ->where('id', $filterId)
            ->firstOrFail();

        $savedFilter->delete();

        session()->flash('success', 'Filter deleted successfully.');
    }

    #[Computed]
    public function savedFilters(): array
    {
        return SavedFilter::where('user_id', auth()->id())
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    #[Computed]
    public function kanbanIssues(): array
    {
        $filters = [
            'status' => null, // Get all statuses for Kanban
            'search' => $this->search,
            'department_id' => $this->department_id,
            'issue_type_id' => $this->issue_type_id,
            'category_id' => $this->category_id,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'order_by' => $this->order_by,
            'order_dir' => $this->order_dir,
        ];

        // Limit Kanban to most recent 150 issues for performance
        // With 3,469 total issues, loading all would be too slow
        $issues = Issue::with(['departments', 'issueTypes', 'createdBy', 'assignedTo'])
            ->orderBy('issue_date', 'desc')
            ->limit(150)
            ->get();

        // Apply filters client-side for small dataset
        if (!empty($filters['search'])) {
            $issues = $issues->filter(fn($issue) => stripos($issue->title, $filters['search']) !== false);
        }
        if (!empty($filters['department_id'])) {
            $issues = $issues->filter(fn($issue) => $issue->departments->contains('id', $filters['department_id']));
        }
        if (!empty($filters['priority'])) {
            $issues = $issues->where('priority', $filters['priority']);
        }

        return [
            'open' => $issues->filter(fn($issue) => $issue->status === 'open')->values(),
            'in_progress' => $issues->filter(fn($issue) => $issue->status === 'in_progress')->values(),
            'closed' => $issues->filter(fn($issue) => $issue->status === 'closed')->take(20)->values(), // Limit closed issues
        ];
    }

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
}
