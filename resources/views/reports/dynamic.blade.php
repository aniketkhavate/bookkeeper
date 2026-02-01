<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">
        {{ ucfirst($report_type) }} Report ({{ ucfirst($date_type) }})
    </h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Status</th>
                <th>Total Bill</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entries as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry->customer->name ?? '-' }}</td>
                <td>{{ $entry->service->name ?? '-' }}</td>
                <td>{{ ucfirst($entry->status) }}</td>
                <td>{{ $entry->total_bill }}</td>
                <td>{{ $entry->created_at->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">
                    No records found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>