<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guest Issues - Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700;800&display=swap');

        @page {
            size: A4;
            margin: 8mm 8mm 8mm 8mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito Sans', 'Segoe UI', sans-serif;
            font-size: 10pt;
            color: #2d3748;
            background: #fff;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        /* Header */
        .header {
            background: #fff;
            border-bottom: 3px solid #1e40af;
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 40px;
            width: auto;
        }

        .header-title {
            font-size: 16pt;
            font-weight: 700;
            color: #1e40af;
            letter-spacing: 0.5px;
        }

        .header-date {
            font-size: 9pt;
            font-weight: 600;
            color: #4a5568;
        }

        /* Summary Cards */
        .summary-section {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .summary-card {
            flex: 1;
            text-align: center;
            padding: 12px 8px;
        }

        .summary-card.total {
            background: #667eea;
            color: #fff;
        }

        .summary-card.open {
            background: #ed8936;
            color: #fff;
        }

        .summary-card.closed {
            background: #48bb78;
            color: #fff;
        }

        .summary-label {
            font-size: 7pt;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .summary-value {
            font-size: 20pt;
            font-weight: 800;
        }

        /* Filters */
        .filters {
            background: #edf2f7;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 8pt;
            border-radius: 4px;
        }

        .filters span {
            margin-right: 15px;
        }

        .filters strong {
            font-weight: 700;
            color: #4a5568;
        }

        /* Issue Card */
        .issue-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .issue-card.open {
            border-left: 4px solid #ed8936;
        }

        .issue-card.closed {
            border-left: 4px solid #48bb78;
        }

        /* Card Header */
        .card-header {
            background: #f7fafc;
            padding: 10px 15px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .room-number {
            background: #4a5568;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 10pt;
            font-weight: 700;
        }

        .case-id {
            font-size: 8pt;
            color: #718096;
            font-weight: 600;
            margin-left: 10px;
        }

        .card-header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-badge {
            font-size: 8pt;
            color: #4a5568;
            font-weight: 500;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-badge.open {
            background: #feebc8;
            color: #c05621;
        }

        .status-badge.closed {
            background: #c6f6d5;
            color: #276749;
        }

        /* Card Body */
        .card-body {
            padding: 12px 15px;
        }

        /* Guest & Meta Info */
        .guest-info {
            display: flex;
            flex-wrap: wrap;
            column-gap: 26px;
            row-gap: 6px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .guest-info-item {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .guest-info-label {
            font-size: 7pt;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .guest-info-value {
            font-size: 9pt;
            font-weight: 600;
            color: #2d3748;
        }

        /* Description & Recovery - Stacked for maximum width */
        .content-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .section-header {
            font-size: 8pt;
            font-weight: 700;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .description-box {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 9pt;
            color: #2d3748;
            line-height: 1.5;
            min-height: 40px;
        }

        .recovery-box {
            background: #fffbeb;
            border: 1px solid #fbd38d;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 9pt;
            color: #975a16;
            line-height: 1.5;
            min-height: 40px;
        }

        /* Card Footer */
        .card-footer {
            background: #f7fafc;
            padding: 8px 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #718096;
        }

        .card-footer-label {
            font-weight: 600;
        }

        .card-footer-value {
            font-weight: 600;
            color: #4a5568;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #a0aec0;
        }

        /* Logbook Table Layout */
        .logbook-wrapper {
            margin-top: 10px;
        }

        .logbook-title-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .logbook-title {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #2d3748;
        }

        .logbook-subtitle {
            font-size: 8pt;
            color: #718096;
        }

        .logbook-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            table-layout: fixed;
        }

        .logbook-table thead {
            background: #1e40af;
            color: #fff;
        }

        .logbook-table th,
        .logbook-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 4px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .logbook-table th {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 7pt;
            text-align: left;
        }

        .logbook-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .logbook-table tbody tr {
            page-break-inside: avoid;
        }

        .logbook-table .col-case {
            width: 6%;
        }

        .logbook-table .col-date {
            width: 9%;
        }

        .logbook-table .col-status {
            width: 7%;
        }

        .logbook-table .col-room {
            width: 7%;
        }

        .logbook-table .col-guest {
            width: 14%;
        }

        .logbook-table .col-nationality {
            width: 10%;
        }

        .logbook-table .col-stay {
            width: 12%;
        }

        .logbook-table .col-department {
            width: 12%;
        }

        .logbook-table .col-description {
            width: 16%;
        }

        .logbook-table .col-recovery {
            width: 17%;
        }

        .logbook-table .col-cost {
            width: 10%;
            text-align: right;
            white-space: nowrap;
        }

        .status-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pill.open {
            background: #feebc8;
            color: #c05621;
        }

        .status-pill.closed {
            background: #c6f6d5;
            color: #276749;
        }

        .text-muted {
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <img src="{{ asset('images/logo/guestpulse_horizontal_logo.svg') }}" alt="GuestPulse Logo" class="logo">
                <span class="header-title">GUEST ISSUES - REPORT</span>
            </div>
            <div class="header-date">Date: {{ now()->format('d/m/Y') }}</div>
        </div>

        @if(!empty($filters))
            <div class="filters">
                @if(isset($filters['date_range']))
                    <span><strong>Period:</strong> {{ $filters['date_range'] }}</span>
                @else
                    @if(isset($filters['date_from']))
                        <span><strong>From:</strong> {{ $filters['date_from'] }}</span>
                    @endif
                    @if(isset($filters['date_to']))
                        <span><strong>To:</strong> {{ $filters['date_to'] }}</span>
                    @endif
                @endif
                @if(isset($filters['department']))
                    <span><strong>Department:</strong> {{ $filters['department'] }}</span>
                @endif
                @if(isset($filters['issue_type']))
                    <span><strong>Type:</strong> {{ $filters['issue_type'] }}</span>
                @endif
                @if(isset($filters['status']))
                    <span><strong>Status:</strong> {{ $filters['status'] }}</span>
                @endif
            </div>
        @endif

        @php
            $totalCases = $issues->count();
            $openCases = $issues->whereNull('closed_at')->count();
            $closedCases = $issues->whereNotNull('closed_at')->count();
        @endphp

        <div class="summary-section">
            <div class="summary-card total">
                <div class="summary-label">Total Cases</div>
                <div class="summary-value">{{ $totalCases }}</div>
            </div>
            <div class="summary-card open">
                <div class="summary-label">Open Cases</div>
                <div class="summary-value">{{ $openCases }}</div>
            </div>
            <div class="summary-card closed">
                <div class="summary-label">Closed Cases</div>
                <div class="summary-value">{{ $closedCases }}</div>
            </div>
        </div>

        <div class="logbook-wrapper">
            @if($issues->count() > 0)
                <div class="logbook-title-row">
                    <div class="logbook-title">Guest Issue Logbook</div>
                    <div class="logbook-subtitle">
                        Generated on {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>

                @foreach($issues as $issue)
                    <div class="issue-card {{ $issue->closed_at ? 'closed' : 'open' }}">
                        <div class="card-header">
                            <div style="display: flex; align-items: center;">
                                @if($issue->room_number)
                                    <span class="room-number">Room {{ $issue->room_number }}</span>
                                @endif
                                <span class="case-id">Case #{{ $issue->id }}</span>
                            </div>
                            <div class="card-header-right">
                                <span class="date-badge">{{ $issue->created_at?->format('d/m/Y') }}</span>
                                <span class="status-badge {{ $issue->closed_at ? 'closed' : 'open' }}">
                                    {{ $issue->closed_at ? 'Closed' : 'Open' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="guest-info">
                                @if($issue->name)
                                    <div class="guest-info-item">
                                        <span class="guest-info-label">Guest:</span>
                                        <span class="guest-info-value">{{ $issue->name }}</span>
                                    </div>
                                @endif

                                @if($issue->nationality)
                                    <div class="guest-info-item">
                                        <span class="guest-info-label">Nationality:</span>
                                        <span class="guest-info-value">{{ $issue->nationality }}</span>
                                    </div>
                                @endif

                                <div class="guest-info-item">
                                    <span class="guest-info-label">Stay:</span>
                                    <span class="guest-info-value">
                                        {{ $issue->checkin_date?->format('d/m/Y') ?? '-' }}
                                        &mdash;
                                        {{ $issue->checkout_date?->format('d/m/Y') ?? '-' }}
                                    </span>
                                </div>

                                @if($issue->departments->count() > 0)
                                    <div class="guest-info-item">
                                        <span class="guest-info-label">Department:</span>
                                        <span class="guest-info-value">
                                            {{ $issue->departments->pluck('name')->join(', ') }}
                                        </span>
                                    </div>
                                @endif

                                @if($issue->recovery_cost !== null)
                                    <div class="guest-info-item">
                                        <span class="guest-info-label">Cost:</span>
                                        <span class="guest-info-value">
                                            {{ number_format($issue->recovery_cost) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="content-row">
                                <div>
                                    <div class="section-header">Description</div>
                                    <div class="description-box">
                                        @if($issue->description)
                                            {!! nl2br(e($issue->description)) !!}
                                        @else
                                            <span class="text-muted">No description provided.</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <div class="section-header">Recovery Action</div>
                                    <div class="recovery-box">
                                        @if($issue->recovery)
                                            {!! nl2br(e($issue->recovery)) !!}
                                        @else
                                            <span class="text-muted">No recovery action recorded.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>No issues found matching the specified filters.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
