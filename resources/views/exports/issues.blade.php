<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Issues Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; font-size: 10px; color: #666; }
        .filters { margin-bottom: 20px; }
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
    <h1>Issues Report</h1>

    <div class="filters">
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
        @if(isset($filters['status']))
        <p><strong>Status:</strong> {{ $filters['status'] }}</p>
        @endif
        @if(isset($filters['priority']))
        <p><strong>Priority:</strong> {{ $filters['priority'] }}</p>
        @endif
        @if(isset($filters['date_from']) || isset($filters['date_to']))
        <p><strong>Date Range:</strong> {{ $filters['date_from'] ?? 'All' }} to {{ $filters['date_to'] ?? 'All' }}</p>
        @endif
        <p><strong>Total Issues:</strong> {{ $issues->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Departments</th>
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
                <td>{{ $issue->departments->pluck('name')->implode(', ') }}</td>
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
