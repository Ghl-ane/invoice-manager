<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }} · InvoiceX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    {{-- Top bar --}}
    <header class="bg-[#0f1c2e] py-4 px-6">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-md bg-amber-400 flex items-center justify-center">
                    <span class="text-slate-900 font-bold text-xs">IX</span>
                </div>
                <span class="font-display text-lg font-bold text-white tracking-tight">InvoiceX</span>
            </div>
            <span class="text-xs text-slate-400">Shared invoice · view only</span>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-10">

        @php
            $colors = [
                'draft'   => 'bg-slate-100 text-slate-600',
                'sent'    => 'bg-blue-100 text-blue-600',
                'paid'    => 'bg-emerald-100 text-emerald-600',
                'overdue' => 'bg-red-100 text-red-600',
            ];
        @endphp

        {{-- Invoice card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Amber top stripe --}}
            <div class="h-1.5 bg-amber-400"></div>

            <div class="p-8">

                {{-- Header --}}
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Invoice</p>
                        <h1 class="font-display text-3xl font-bold text-slate-800">{{ $invoice->invoice_number }}</h1>
                        <p class="text-sm text-slate-400 mt-1">Issued {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $colors[$invoice->status] ?? '' }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>

                {{-- From / To --}}
                <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest mb-2">From</p>
                        <p class="font-semibold text-slate-800">{{ $invoice->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest mb-2">Bill To</p>
                        <p class="font-semibold text-slate-800">{{ $invoice->client->name }}</p>
                        <p class="text-sm text-slate-500">{{ $invoice->client->email }}</p>
                        @if ($invoice->client->phone)
                            <p class="text-sm text-slate-500">{{ $invoice->client->phone }}</p>
                        @endif
                        @if ($invoice->client->address)
                            <p class="text-sm text-slate-500">{{ $invoice->client->address }}</p>
                        @endif
                    </div>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Issue Date</p>
                        <p class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Due Date</p>
                        <p class="font-medium {{ $invoice->status === 'overdue' ? 'text-red-600' : 'text-slate-700' }}">
                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                            @if ($invoice->status === 'overdue')
                                <span class="text-xs text-red-400 ml-1">Overdue</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Line items --}}
                <table class="w-full text-sm mb-6">
                    <thead>
                        <tr class="text-xs text-slate-400 uppercase tracking-wide border-b border-slate-200">
                            <th class="text-left pb-3">Description</th>
                            <th class="text-center pb-3 w-16">Qty</th>
                            <th class="text-right pb-3 w-28">Unit Price</th>
                            <th class="text-right pb-3 w-28">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="py-3 text-slate-700">{{ $item->description }}</td>
                                <td class="py-3 text-center text-slate-500">{{ $item->quantity }}</td>
                                <td class="py-3 text-right text-slate-500">{{ $invoice->symbol }}{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 text-right font-medium text-slate-700">{{ $invoice->symbol }}{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Total --}}
                <div class="flex justify-end mb-6">
                    <div class="bg-slate-50 rounded-xl px-6 py-4 text-right min-w-48">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Total Due</p>
                        <p class="font-display text-3xl font-bold text-slate-800">
                            {{ $invoice->symbol }}{{ number_format($invoice->total, 2) }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1">{{ $invoice->currency }}</p>
                    </div>
                </div>

                @if ($invoice->notes)
                    <div class="border-l-4 border-amber-400 bg-amber-50 rounded-r-lg px-4 py-3">
                        <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide mb-1">Notes</p>
                        <p class="text-sm text-amber-800">{{ $invoice->notes }}</p>
                    </div>
                @endif

            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-8">
            Sent via <strong class="text-slate-500">InvoiceX</strong> · This link expires 7 days after it was created.
        </p>

    </main>

</body>
</html>
