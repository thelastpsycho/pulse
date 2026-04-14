<?php

namespace App\Exports;

use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IssuesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->with(['departments', 'issueTypes', 'createdBy', 'assignedTo'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Status',
            'Priority',
            'Issue Date',
            'Departments',
            'Issue Types',
            'Assigned To',
            'Created By',
            'Created At',
            'Closed At',
        ];
    }

    public function map($issue): array
    {
        return [
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
