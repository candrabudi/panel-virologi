<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leak Intelligence Audit Report</title>
    <style>
        body { font-family: "Courier New", Courier, monospace; font-size: 11px; color: #1a202c; padding: 20px; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; letter-spacing: -1px; margin-bottom: 5px; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .meta { font-size: 10px; color: #4a5568; margin-top: 5px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 10px 5px; text-align: left; vertical-align: top; }
        th { background-color: #f7fafc; font-weight: bold; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; color: #4a5568; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .status-badge { padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-success { background-color: #f0fff4; color: #22543d; border: 1px solid #c6f6d5; }
        .status-failed { background-color: #fff5f5; color: #742a2a; border: 1px solid #fed7d7; }
        .breach-count { font-weight: bold; }
        .breach-alert { color: #e53e3e; }
        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 9px; color: #718096; text-align: center; text-transform: uppercase; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="logo">VIROLOGY//INTEL_SYSTEM</div>
        <div class="title">Leak Intelligence Audit Log</div>
        <div class="meta">Report Generation: {{ now()->format('d M Y H:i:s') }} | Operator: {{ auth()->user()->username ?? 'SYSTEM' }}</div>
        <div class="meta">Classification: INTERNAL RESTRICTED // DO NOT DISTRIBUTE</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Initiator</th>
                <th width="30%">Target Query</th>
                <th width="10%" style="text-align: center;">Breaches</th>
                <th width="15%">Network ID</th>
                <th width="10%">Status</th>
                <th width="15%" style="text-align: right;">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>#{{ $log->id }}</td>
                    <td>
                        <strong>{{ $log->user->username ?? 'Guest' }}</strong>
                        <div style="font-size: 9px; color: #718096;">ID: {{ $log->user_id ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <code>{{ $log->query }}</code>
                    </td>
                    <td style="text-align: center;">
                        <span class="breach-count {{ $log->leak_count > 0 ? 'breach-alert' : '' }}">{{ number_format($log->leak_count) }}</span>
                    </td>
                    <td>{{ $log->ip_address }}</td>
                    <td>
                        <span class="status-badge {{ $log->status == 'success' ? 'status-success' : 'status-failed' }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div>{{ $log->created_at->format('Y-m-d') }}</div>
                        <div style="font-size: 9px; color: #718096;">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated automatically by Virology Panel. This document contains sensitive information.
    </div>
</body>
</html>
