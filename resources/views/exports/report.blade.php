<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report - {{ $type == 'month' ? 'Monthly' : 'Yearly' }} {{ $period }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #333; }
        h2 { color: #666; font-size: 16px; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; font-size: 10px; color: #666; }
        .summary { margin-bottom: 30px; }
        .summary-item { display: inline-block; margin-right: 30px; }
        .summary-label { font-weight: bold; }
        .priority-urgent { color: #d32f2f; font-weight: bold; }
        .priority-high { color: #f57c00; }
        .priority-medium { color: #fbc02d; }
        .priority-low { color: #388e3c; }
        .status-closed { color: #388e3c; }
        .status-open { color: #1976d2; }
        .status-in_progress { color: #f57c00; }
    </style>
</head>
<body>
    <h1>{{ $type == 'month' ? 'Monthly' : 'Yearly' }} Report - {{ $period }}</h1>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Generated:</span> {{ $generated_at }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Issues:</span> {{ $issues->count() }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Closed:</span> {{ $issues->where('status', 'closed')->count() }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Open:</span> {{ $issues->where('status', '!=', 'closed')->count() }}
        </div>
    </div>

    <h2>Issues List</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Assigned To</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issues as $issue)
            <tr>
                <td>{{ $issue->id }}</td>
                <td>{{ \Str::limit($issue->title, 50) }}</td>
                <td class="status-{{ $issue->status }}">{{ ucfirst(str_replace('_', ' ', $issue->status)) }}</td>
                <td class="priority-{{ $issue->priority }}">{{ ucfirst($issue->priority) }}</td>
                <td>{{ $issue->departments->first()?->name ?? 'N/A' }}</td>
                <td>{{ $issue->assignedTo?->name ?? 'Unassigned' }}</td>
                <td>{{ $issue->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Pulse Issue Tracking System - Generated on {{ $generated_at }}</p>
    </div>
</body>
</html>
