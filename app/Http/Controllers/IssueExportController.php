<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Services\ExportService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IssueExportController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ExportService $exportService)
    {
    }

    public function exportPDF(Issue $issue)
    {
        $this->authorize('view', $issue);

        return $this->exportService->exportIssue($issue);
    }

    public function exportOpenIssuesPDF()
    {
        $this->authorize('issues.export.open');

        $issues = Issue::open()
            ->with(['departments', 'issueTypes', 'createdBy', 'updatedBy', 'closedBy', 'assignedTo', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->exportService->exportOpenIssuesPDF($issues);
    }
}
