<?php

namespace App\Livewire\Issues;

use App\Models\Issue;
use App\Models\IssueComment;
use App\Services\IssueService;
use App\Services\ExportService;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Show extends Component
{
    public Issue $issue;

    #[Rule(['required', 'string'])]
    public string $comment = '';

    public bool $showCloseModal = false;
    public bool $showReopenModal = false;
    public bool $showDeleteModal = false;
    public bool $showCategorizeModal = false;
    public ?string $closeNote = null;

    // Categorization fields
    public array $department_ids = [];
    public array $issue_type_ids = [];

    protected IssueService $issueService;

    public function boot(IssueService $issueService): void
    {
        $this->issueService = $issueService;
    }

    public function mount(Issue $issue): void
    {
        $this->authorize('view', $issue);
        $this->issue = $issue->load(['departments', 'issueTypes', 'createdBy', 'assignedTo', 'comments.user']);
    }

    public function openCloseModal(): void
    {
        $this->showCloseModal = true;
    }

    public function openReopenModal(): void
    {
        $this->showReopenModal = true;
    }

    public function closeModals(): void
    {
        $this->showCloseModal = false;
        $this->showReopenModal = false;
        $this->showDeleteModal = false;
        $this->showCategorizeModal = false;
        $this->closeNote = null;
    }

    public function openCategorizeModal(): void
    {
        $this->authorize('categorize', $this->issue);
        $this->department_ids = $this->issue->departments->pluck('id')->toArray();
        $this->issue_type_ids = $this->issue->issueTypes->pluck('id')->toArray();
        $this->showCategorizeModal = true;
    }

    public function categorize(): void
    {
        $this->authorize('categorize', $this->issue);

        $this->validate([
            'department_ids' => ['required', 'array', 'min:1'],
            'department_ids.*' => ['exists:departments,id'],
            'issue_type_ids' => ['required', 'array', 'min:1'],
            'issue_type_ids.*' => ['exists:issue_types,id'],
        ]);

        // Sync departments
        $this->issue->departments()->sync($this->department_ids);

        // Sync issue types
        $this->issue->issueTypes()->sync($this->issue_type_ids);

        // Also update primary issue_type_id if provided
        if (!empty($this->issue_type_ids)) {
            $this->issue->update(['issue_type_id' => $this->issue_type_ids[0]]);
        }

        $this->issue->load(['departments', 'issueTypes']);
        $this->closeModals();

        session()->flash('success', 'Issue categorized successfully.');
    }

    public function render()
    {
        $activityLog = $this->issueService->getActivityLog($this->issue);

        return view('livewire.issues.show', [
            'activityLog' => $activityLog,
        ])
            ->layout('layouts.app')
            ->title('Issue #' . $this->issue->id);
    }

    public function addComment(): void
    {
        $this->validate();

        IssueComment::create([
            'issue_id' => $this->issue->id,
            'user_id' => auth()->id(),
            'body' => $this->comment,
        ]);

        $this->comment = '';
        $this->issue->load('comments.user');

        session()->flash('success', 'Comment added successfully.');
    }

    public function closeIssue(): void
    {
        $this->authorize('close', $this->issue);

        $this->issueService->close($this->issue, $this->closeNote);
        $this->issue = $this->issue->fresh()->load(['departments', 'issueTypes', 'createdBy', 'assignedTo', 'comments.user']);
        $this->closeModals();

        session()->flash('success', 'Issue closed successfully.');
    }

    public function reopenIssue(): void
    {
        $this->authorize('reopen', $this->issue);

        $this->issueService->reopen($this->issue);
        $this->issue = $this->issue->fresh()->load(['departments', 'issueTypes', 'createdBy', 'assignedTo', 'comments.user']);
        $this->closeModals();

        session()->flash('success', 'Issue reopened successfully.');
    }

    public function deleteComment(IssueComment $comment): void
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        $this->issue->load('comments.user');

        session()->flash('success', 'Comment deleted successfully.');
    }

    public function deleteIssue()
    {
        $this->authorize('delete', $this->issue);
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        $this->authorize('delete', $this->issue);

        $this->issueService->delete($this->issue);

        return $this->redirectRoute('issues.index');
    }

    public function getPriorityBadgeProperty(): string
    {
        return match ($this->issue->priority) {
            'urgent' => 'badge-danger',
            'high' => 'badge-warning',
            default => 'badge-muted',
        };
    }

    public function getStatusBadgeProperty(): string
    {
        return $this->issue->status === 'open' ? 'badge-success' : 'badge-muted';
    }

    public function exportPDF(ExportService $exportService)
    {
        $this->authorize('view', $this->issue);

        // Redirect to a controller route for PDF download
        return redirect()->route('issues.export.pdf', $this->issue);
    }

    public function getDepartmentsProperty(): array
    {
        return \App\Models\Department::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getIssueTypesProperty(): array
    {
        return \App\Models\IssueType::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
