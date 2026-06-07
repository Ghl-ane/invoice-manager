<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invoice {{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;color:#1e293b;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 16px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        {{-- Header --}}
        <tr>
          <td style="background:#0f1c2e;border-radius:12px 12px 0 0;padding:28px 36px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <span style="font-size:22px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">InvoiceX</span>
                </td>
                <td align="right">
                  <span style="background:#fbbf24;color:#0f172a;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">
                    {{ ucfirst($invoice->status) }}
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- Hero band --}}
        <tr>
          <td style="background:#fbbf24;padding:20px 36px;">
            <p style="margin:0;font-size:13px;color:#78350f;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Invoice</p>
            <p style="margin:4px 0 0;font-size:28px;font-weight:800;color:#0f172a;letter-spacing:-1px;">
              {{ $invoice->invoice_number }}
            </p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="background:#ffffff;padding:32px 36px;">

            <p style="margin:0 0 24px;font-size:15px;color:#475569;line-height:1.6;">
              Hi <strong style="color:#0f172a;">{{ $invoice->client->name }}</strong>,<br>
              Please find your invoice attached as a PDF. A summary is included below.
            </p>

            {{-- Meta --}}
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:28px;">
              <tr>
                <td style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="font-size:12px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Invoice Number</td>
                      <td align="right" style="font-size:14px;font-weight:600;color:#0f172a;">{{ $invoice->invoice_number }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="font-size:12px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Issue Date</td>
                      <td align="right" style="font-size:14px;font-weight:600;color:#0f172a;">
                        {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding:14px 20px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="font-size:12px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Due Date</td>
                      <td align="right" style="font-size:14px;font-weight:700;color:#dc2626;">
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            {{-- Line items --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;padding:0 0 8px;border-bottom:2px solid #e2e8f0;">Description</td>
                <td align="center" style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;padding:0 0 8px;border-bottom:2px solid #e2e8f0;">Qty</td>
                <td align="right" style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;padding:0 0 8px;border-bottom:2px solid #e2e8f0;">Amount</td>
              </tr>
              @foreach ($invoice->items as $item)
              <tr>
                <td style="padding:10px 0;font-size:14px;color:#334155;border-bottom:1px solid #f1f5f9;">{{ $item->description }}</td>
                <td align="center" style="padding:10px 0;font-size:14px;color:#64748b;border-bottom:1px solid #f1f5f9;">{{ $item->quantity }}</td>
                <td align="right" style="padding:10px 0;font-size:14px;color:#334155;font-weight:500;border-bottom:1px solid #f1f5f9;">
                  {{ $invoice->symbol }}{{ number_format($item->subtotal, 2) }}
                </td>
              </tr>
              @endforeach
              {{-- Total row --}}
              <tr>
                <td colspan="2" style="padding:14px 0 0;font-size:15px;font-weight:700;color:#0f172a;">Total Due</td>
                <td align="right" style="padding:14px 0 0;font-size:20px;font-weight:800;color:#0f172a;">
                  {{ $invoice->symbol }}{{ number_format($invoice->total, 2) }}
                  <span style="font-size:13px;color:#94a3b8;font-weight:500;">{{ $invoice->currency }}</span>
                </td>
              </tr>
            </table>

            @if ($invoice->notes)
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#fffbeb;border-left:3px solid #fbbf24;border-radius:0 6px 6px 0;padding:12px 16px;">
                  <p style="margin:0;font-size:13px;color:#92400e;"><strong>Notes:</strong> {{ $invoice->notes }}</p>
                </td>
              </tr>
            </table>
            @endif

            <p style="margin:0;font-size:14px;color:#64748b;line-height:1.6;">
              The full invoice PDF is attached to this email. If you have any questions, please reply to this message.
            </p>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f8fafc;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 12px 12px;padding:20px 36px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#94a3b8;">
              Sent via <strong style="color:#64748b;">InvoiceX</strong> by {{ $invoice->user->name }}
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
