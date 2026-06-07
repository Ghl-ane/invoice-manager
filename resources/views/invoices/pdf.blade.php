<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        /* DomPDF only supports basic CSS — no flexbox, no grid */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            /* ↑ Important: use DejaVu Sans — it supports Arabic/special chars */
            font-size: 13px;
            color: #1e293b;
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 3px solid #fbbf24;
            padding-bottom: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #fbbf24;
        }

        .invoice-title {
            font-size: 20px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Use tables for layout in PDFs — not divs */
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px 0;
        }

        .label {
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            font-weight: bold;
            color: #1e293b;
            margin-top: 3px;
        }

        /* Status badge */
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-paid    { background: #d1fae5; color: #065f46; }
        .status-sent    { background: #dbeafe; color: #1e40af; }
        .status-draft   { background: #f1f5f9; color: #475569; }
        .status-overdue { background: #fee2e2; color: #991b1b; }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background: #f8fafc;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table .text-right { text-align: right; }

        .total-row {
            background: #fbbf24;
        }

        .total-row td {
            padding: 12px;
            font-weight: bold;
            font-size: 15px;
        }

        /* Notes */
        .notes {
            background: #f8fafc;
            border-left: 3px solid #fbbf24;
            padding: 12px 16px;
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="logo">InvoiceX</div>
        <div class="invoice-title">{{ $invoice->invoice_number }}</div>
    </div>

    {{-- INFO SECTION --}}
    {{-- Use table for two-column layout in DomPDF --}}
    <table class="info-table">
        <tr>
            {{-- Left: Bill To --}}
            <td width="50%">
                <div class="label">Bill To</div>
                <div class="value">{{ $invoice->client->name }}</div>
                <div>{{ $invoice->client->email }}</div>
                @if($invoice->client->phone)
                    <div>{{ $invoice->client->phone }}</div>
                @endif
                @if($invoice->client->address)
                    <div>{{ $invoice->client->address }}</div>
                @endif
            </td>

            {{-- Right: Invoice Details --}}
            <td width="50%" style="text-align: right;">
                <div class="label">Status</div>
                <div class="value">
                    <span class="status status-{{ $invoice->status }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>

                <div style="margin-top: 15px;">
                    <div class="label">Issue Date</div>
                    <div class="value">
                        {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <div class="label">Due Date</div>
                    <div class="value">
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- LINE ITEMS TABLE --}}
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ $invoice->symbol }}{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ $invoice->symbol }}{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Total Amount</td>
                <td class="text-right">{{ $invoice->symbol }}{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- NOTES --}}
    @if($invoice->notes)
    <div class="notes">
        <strong>Notes:</strong> {{ $invoice->notes }}
    </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        Generated by InvoiceX &bull; {{ now()->format('M d, Y') }}
    </div>

</body>
</html>