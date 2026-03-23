<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\IssueType;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LogbookExportController extends Controller
{
    public function export(Request $request)
    {
        $filters = [];

        if ($request->date_from) {
            $filters['date_from'] = $request->date_from;
        }
        if ($request->date_to) {
            $filters['date_to'] = $request->date_to;
        }
        if ($request->department_id) {
            $filters['department_id'] = $request->department_id;
        }
        if ($request->issue_type_id) {
            $filters['issue_type_id'] = $request->issue_type_id;
        }
        if ($request->status) {
            $filters['status'] = $request->status;
        }

        $reportService = app(ReportService::class);
        $issues = $reportService->logbookReport($filters);

        $departments = Department::orderBy('name')->get();
        $issueTypes = IssueType::orderBy('name')->get();

        $filterLabels = [];
        if ($request->date_from && $request->date_to) {
            $filterLabels['date_range'] = $request->date_from . ' - ' . $request->date_to;
        } elseif ($request->date_from) {
            $filterLabels['date_from'] = $request->date_from;
        } elseif ($request->date_to) {
            $filterLabels['date_to'] = $request->date_to;
        }
        if ($request->department_id) {
            $dept = $departments->firstWhere('id', $request->department_id);
            $filterLabels['department'] = $dept?->name;
        }
        if ($request->issue_type_id) {
            $type = $issueTypes->firstWhere('id', $request->issue_type_id);
            $filterLabels['issue_type'] = $type?->name;
        }
        if ($request->status) {
            $filterLabels['status'] = ucfirst($request->status);
        }

        $pdf = PDF::loadView('exports.logbook', [
            'issues' => $issues,
            'filters' => $filterLabels,
        ]);

        return $pdf->download('logbook-' . now()->format('Y-m-d') . '.pdf');
    }
}
