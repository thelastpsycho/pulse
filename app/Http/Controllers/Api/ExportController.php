<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IssuesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportIssuesCsv(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'nullable|in:open,in_progress,closed,cancelled',
            'priority' => 'nullable|in:urgent,high,medium,low',
            'department_id' => 'nullable|exists:departments,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Issue::query()
            ->with(['departments', 'issueTypes', 'createdBy', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('department_id')) {
            $query->whereHas('departments', function ($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        $issues = $query->get();

        $csvData = [];
        $csvData[] = ['ID', 'Title', 'Description', 'Status', 'Priority', 'Issue Date', 'Departments', 'Issue Types', 'Assigned To', 'Created By', 'Created At', 'Closed At'];

        foreach ($issues as $issue) {
            $csvData[] = [
                $issue->id,
                $issue->title,
                $issue->description,
                $issue->status,
                $issue->priority,
                $issue->issue_date?->format('Y-m-d'),
                $issue->departments->pluck('name')->implode(', '),
                $issue->issueTypes->pluck('name')->implode(', '),
                $issue->assignedTo?->name,
                $issue->createdBy?->name,
                $issue->created_at->format('Y-m-d H:i:s'),
                $issue->closed_at?->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'issues_' . now()->format('Y_m_d_His') . '.csv';
        $path = 'exports/' . $filename;

        $file = fopen(storage_path('app/' . $path), 'w');
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return response()->json([
            'message' => 'CSV export generated successfully',
            'filename' => $filename,
            'url' => Storage::url($path),
            'count' => $issues->count(),
        ]);
    }

    public function exportIssuesExcel(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'nullable|in:open,in_progress,closed,cancelled',
            'priority' => 'nullable|in:urgent,high,medium,low',
            'department_id' => 'nullable|exists:departments,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Issue::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('department_id')) {
            $query->whereHas('departments', function ($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        $filename = 'issues_' . now()->format('Y_m_d_His') . '.xlsx';
        $path = 'exports/' . $filename;

        Excel::store(new IssuesExport($query), $path);

        return response()->json([
            'message' => 'Excel export generated successfully',
            'filename' => $filename,
            'url' => Storage::url($path),
        ]);
    }

    public function exportIssuesPdf(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'nullable|in:open,in_progress,closed,cancelled',
            'priority' => 'nullable|in:urgent,high,medium,low',
            'department_id' => 'nullable|exists:departments,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Issue::query()
            ->with(['departments', 'issueTypes', 'createdBy', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('department_id')) {
            $query->whereHas('departments', function ($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        $issues = $query->get();

        $filename = 'issues_' . now()->format('Y_m_d_His') . '.pdf';
        $path = 'exports/' . $filename;

        $pdf = PDF::loadView('exports.issues', [
            'issues' => $issues,
            'filters' => $request->only(['status', 'priority', 'department_id', 'date_from', 'date_to']),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        Storage::put($path, $pdf->output());

        return response()->json([
            'message' => 'PDF export generated successfully',
            'filename' => $filename,
            'url' => Storage::url($path),
            'count' => $issues->count(),
        ]);
    }

    public function exportReports(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:month,year',
            'format' => 'required|in:csv,pdf',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2020|max:2099',
        ]);

        $type = $request->type;
        $format = $request->format;
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        if ($type === 'month') {
            $query = Issue::whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
            $period = "${year}-" . str_pad($month, 2, '0', STR_PAD_LEFT);
        } else {
            $query = Issue::whereYear('created_at', $year);
            $period = (string) $year;
        }

        $issues = $query->with(['departments', 'issueTypes', 'createdBy', 'assignedTo'])->get();
        $filename = "report_{$type}_{$period}_" . now()->format('Y_m_d_His') . ".{$format}";
        $path = 'exports/' . $filename;

        if ($format === 'csv') {
            $csvData = [];
            $csvData[] = ['ID', 'Title', 'Status', 'Priority', 'Department', 'Assigned To', 'Created At'];

            foreach ($issues as $issue) {
                $csvData[] = [
                    $issue->id,
                    $issue->title,
                    $issue->status,
                    $issue->priority,
                    $issue->departments->first()?->name,
                    $issue->assignedTo?->name,
                    $issue->created_at->format('Y-m-d'),
                ];
            }

            $file = fopen(storage_path('app/' . $path), 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        } elseif ($format === 'pdf') {
            $pdf = PDF::loadView('exports.report', [
                'issues' => $issues,
                'type' => $type,
                'period' => $period,
                'generated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            Storage::put($path, $pdf->output());
        }

        return response()->json([
            'message' => "Report exported successfully",
            'filename' => $filename,
            'url' => Storage::url($path),
            'count' => $issues->count(),
            'type' => $type,
            'period' => $period,
        ]);
    }
}
