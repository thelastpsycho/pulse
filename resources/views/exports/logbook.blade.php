<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guest Issues - Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Fira+Sans:wght@300;400;500;600;700&display=swap');

        @page {
            size: A4;
            margin: 10mm 10mm 10mm 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Fira Sans', 'Segoe UI', sans-serif;
            font-size: 10pt;
            color: #1E3A8A;
            background: #F8FAFC;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.15);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            height: 40px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .header-title {
            font-family: 'Fira Code', monospace;
            font-size: 16pt;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .header-date {
            font-family: 'Fira Code', monospace;
            font-size: 9pt;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 14px;
            border-radius: 4px;
        }

        /* Summary Cards */
        .summary-section {
            display: flex;
            gap: 14px;
            margin-bottom: 24px;
        }

        .summary-card {
            flex: 1;
            text-align: center;
            padding: 18px 16px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }

        .summary-card.total {
            background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
            color: #ffffff;
            border: 1px solid #1E40AF;
        }

        .summary-card.open {
            background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
            color: #ffffff;
            border: 1px solid #F59E0B;
        }

        .summary-card.closed {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            color: #ffffff;
            border: 1px solid #10B981;
        }

        .summary-label {
            font-family: 'Fira Code', monospace;
            font-size: 7pt;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            opacity: 0.95;
        }

        .summary-value {
            font-family: 'Fira Code', monospace;
            font-size: 28pt;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 4px;
        }

        /* Filters */
        .filters {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-left: 4px solid #3B82F6;
            padding: 12px 18px;
            margin-bottom: 18px;
            font-size: 9pt;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .filters span {
            margin-right: 24px;
            display: inline-block;
            margin-bottom: 6px;
        }

        .filters strong {
            font-weight: 600;
            color: #1E40AF;
        }

        /* Issue Card */
        .issue-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            margin-bottom: 18px;
            page-break-inside: avoid;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .issue-card.open {
            border-left: 4px solid #F59E0B;
        }

        .issue-card.closed {
            border-left: 4px solid #10B981;
        }

        /* Card Header */
        .card-header {
            background: linear-gradient(to bottom, #F8FAFC, #F1F5F9);
            padding: 12px 16px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .room-number {
            background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 10pt;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(30, 64, 175, 0.2);
        }

        .case-id {
            font-family: 'Fira Code', monospace;
            font-size: 8pt;
            color: #64748B;
            font-weight: 500;
            margin-left: 12px;
        }

        .card-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .date-badge {
            font-family: 'Fira Code', monospace;
            font-size: 8pt;
            color: #475569;
            font-weight: 500;
            background: #F1F5F9;
            padding: 4px 10px;
            border-radius: 4px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.open {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #F59E0B;
        }

        .status-badge.closed {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #10B981;
        }

        /* Card Body */
        .card-body {
            padding: 14px 16px;
        }

        /* Guest & Meta Info - Mobile-Friendly Stacked Layout */
        .guest-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px dashed #CBD5E1;
        }

        .guest-info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        .guest-info-item {
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex: 1;
            min-width: 140px;
        }

        .guest-info-label {
            font-family: 'Fira Code', monospace;
            font-size: 7pt;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .guest-info-value {
            font-size: 9pt;
            font-weight: 500;
            color: #1E3A8A;
        }

        /* Description & Recovery - Stacked for maximum width */
        .content-row {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .section-header {
            font-family: 'Fira Code', monospace;
            font-size: 8pt;
            font-weight: 600;
            color: #3B82F6;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
        }

        .description-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-left: 3px solid #3B82F6;
            border-radius: 4px;
            padding: 12px 14px;
            font-size: 9pt;
            color: #1E3A8A;
            line-height: 1.5;
            min-height: 50px;
        }

        .recovery-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-left: 3px solid #F59E0B;
            border-radius: 4px;
            padding: 12px 14px;
            font-size: 9pt;
            color: #92400E;
            line-height: 1.5;
            min-height: 50px;
        }

        /* Card Footer */
        .card-footer {
            background: #F8FAFC;
            padding: 10px 16px;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #64748B;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-footer-label {
            font-weight: 500;
        }

        .card-footer-value {
            font-weight: 600;
            color: #1E40AF;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #94A3B8;
        }

        .empty-state p {
            font-size: 10pt;
            font-weight: 500;
        }

        /* Logbook Wrapper */
        .logbook-wrapper {
            margin-top: 8px;
        }

        .logbook-title-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #E2E8F0;
        }

        .logbook-title {
            font-family: 'Fira Code', monospace;
            font-size: 14pt;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: #1E40AF;
        }

        .logbook-subtitle {
            font-size: 8pt;
            color: #64748B;
            font-weight: 500;
        }

        .text-muted {
            color: #94A3B8;
            font-size: 9pt;
            font-style: italic;
        }

        /* New Location & Source Fields */
        .location-source-info {
            display: flex;
            gap: 20px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .location-source-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 8pt;
        }

        .location-source-label {
            font-family: 'Fira Code', monospace;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            font-size: 7pt;
        }

        .location-source-value {
            font-weight: 500;
            color: #1E3A8A;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <!-- <img src="{{ asset('images/logo/guestpulse_horizontal_logo.svg') }}" alt="GuestPulse Logo" class="logo"> -->
                <span class="header-title">GUEST ISSUES - REPORT</span>
            </div>
            <div class="header-date">Date: {{ now()->format('d/m/Y') }}</div>
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
                                <div class="guest-info-row">
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
                                </div>

                                <div class="guest-info-row">
                                    @if($issue->location)
                                        <div class="guest-info-item">
                                            <span class="guest-info-label">Location:</span>
                                            <span class="guest-info-value">{{ $issue->location }}</span>
                                        </div>
                                    @endif

                                    @if($issue->source)
                                        <div class="guest-info-item">
                                            <span class="guest-info-label">Source:</span>
                                            <span class="guest-info-value">{{ $issue->source }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="guest-info-row">
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
                                </div>

                                <div class="guest-info-row">
                                    @if($issue->room_number)
                                        <div class="guest-info-item">
                                            <span class="guest-info-label">Room:</span>
                                            <span class="guest-info-value">
                                                {{ $issue->room_number }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($issue->issueTypes->count() > 0)
                                        <div class="guest-info-item">
                                            <span class="guest-info-label">Issue Type:</span>
                                            <span class="guest-info-value">
                                                {{ $issue->issueTypes->pluck('name')->join(', ') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
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
