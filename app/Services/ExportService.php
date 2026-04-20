<?php

namespace App\Services;

use App\Models\Issue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export a single issue to PDF.
     */
    public function exportIssue(Issue $issue)
    {
        $issue->load(['departments', 'issueTypes', 'createdBy', 'updatedBy', 'closedBy', 'assignedTo', 'comments']);

        $pdf = PDF::loadView('exports.issue', [
            'issue' => $issue,
        ]);

        $filename = 'issue-' . $issue->id . '-' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export logbook to PDF.
     */
    public function exportLogbook(Collection $issues, array $filters = [])
    {
        $pdf = PDF::loadView('exports.logbook', [
            'issues' => $issues,
            'filters' => $filters,
        ]);

        $dateRange = '';
        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $dateRange = $filters['date_from'] . '-to-' . $filters['date_to'];
        } else {
            $dateRange = now()->format('Ymd');
        }

        $filename = 'logbook-' . $dateRange . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export monthly report to PDF.
     */
    public function exportMonthlyReport(array $reportData)
    {
        $pdf = PDF::loadView('exports.monthly-report', [
            'report' => $reportData,
        ]);

        $filename = 'monthly-report-' . $reportData['year'] . '-' . str_pad($reportData['month'], 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export yearly report to PDF.
     */
    public function exportYearlyReport(array $reportData)
    {
        $pdf = PDF::loadView('exports.yearly-report', [
            'report' => $reportData,
        ]);

        $filename = 'yearly-report-' . $reportData['year'] . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export open issues to PDF (logbook format).
     */
    public function exportOpenIssuesPDF(Collection $issues)
    {
        $pdf = PDF::loadView('exports.logbook', [
            'issues' => $issues,
            'filters' => ['status' => 'open'],
        ]);

        $filename = 'open-issues-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export issues to CSV.
     */
    public function exportIssuesToCSV(Collection $issues): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="issues-' . now()->format('Ymd-His') . '.csv"',
        ];

        $callback = function () use ($issues) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Title',
                'Description',
                'Status',
                'Priority',
                'Location',
                'Name',
                'Room Number',
                'Nationality',
                'Contact',
                'Check-in Date',
                'Check-out Date',
                'Issue Date',
                'Recovery Cost',
                'Recovery Action',
                'Departments',
                'Issue Types',
                'Assigned To',
                'Created By',
                'Created At',
                'Updated At',
            ]);

            // CSV Data
            foreach ($issues as $issue) {
                fputcsv($file, [
                    $issue->id,
                    $issue->title,
                    strip_tags($issue->description ?? ''),
                    $issue->status,
                    $issue->priority,
                    $issue->location,
                    $issue->name,
                    $issue->room_number,
                    $issue->nationality,
                    $issue->contact,
                    $issue->checkin_date?->format('Y-m-d'),
                    $issue->checkout_date?->format('Y-m-d'),
                    $issue->issue_date?->format('Y-m-d'),
                    $issue->recovery_cost,
                    strip_tags($issue->recovery ?? ''),
                    $issue->departments->pluck('name')->implode(', '),
                    $issue->issueTypes->pluck('name')->implode(', '),
                    $issue->assignedTo?->name,
                    $issue->createdBy?->name,
                    $issue->created_at?->format('Y-m-d H:i:s'),
                    $issue->updated_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
