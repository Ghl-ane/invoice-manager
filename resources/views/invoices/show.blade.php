@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('subtitle', 'Invoice for ' . $invoice->client->name)

@section('header-actions')
    <a href="{{ route('invoices.share-link', $invoice) }}"
        class="text-sm text-slate-600 hover:text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-100 transition-colors border border-slate-200 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
        </svg>
        Share
    </a>
    <a href="{{ route('invoices.edit', $invoice) }}"
        class="text-sm text-slate-600 hover:text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-100 transition-colors border border-slate-200">
        Edit
    </a>
    <a href="{{ route('invoices.pdf', $invoice) }}"
        class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Download PDF
    </a>

    {{-- Send to client --}}
    <form id="send-invoice-form" action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline">
        @csrf
        <button type="button"
            onclick="openSendModal()"
            @if(! $invoice->client->email) disabled title="Client has no email address" @endif
            class="bg-amber-400 hover:bg-amber-500 disabled:opacity-40 disabled:cursor-not-allowed text-slate-900 font-semibold text-sm px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Send to Client
        </button>
    </form>

    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="confirmDelete(this); return false;">
        @csrf @method('DELETE')
        <button type="submit"
            class="text-sm text-red-500 hover:text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors border border-red-200">
            Delete
        </button>
    </form>
@endsection

@section('content')

    @php
        $colors = [
            'draft'   => 'bg-slate-100 text-slate-600',
            'sent'    => 'bg-blue-100 text-blue-600',
            'paid'    => 'bg-emerald-100 text-emerald-600',
            'overdue' => 'bg-red-100 text-red-600',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Invoice Main --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Header Info --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="font-display text-2xl text-slate-800">{{ $invoice->invoice_number }}</h2>
                        <p class="text-slate-400 text-sm mt-1">Created {{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $invoice->currency }}</span>
                        <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $colors[$invoice->status] ?? '' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Client</p>
                        <p class="font-medium text-slate-700">{{ $invoice->client->name }}</p>
                        <p class="text-slate-500">{{ $invoice->client->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Issue Date</p>
                        <p class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Due Date</p>
                        <p class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Line Items --}}
            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-700 text-sm">Line Items</h3>
                </div>
                <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-right">Unit Price</th>
                            <th class="px-6 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="px-6 py-3 text-slate-700">{{ $item->description }}</td>
                                <td class="px-6 py-3 text-center text-slate-500">{{ $item->quantity }}</td>
                                <td class="px-6 py-3 text-right text-slate-500">
                                    {{ $invoice->symbol }}{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-6 py-3 text-right font-medium text-slate-700">
                                    {{ $invoice->symbol }}{{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-slate-200 bg-slate-50">
                            <td colspan="3" class="px-6 py-3 text-right font-semibold text-slate-700">Total</td>
                            <td class="px-6 py-3 text-right font-bold text-lg text-slate-800">
                                {{ $invoice->symbol }}{{ number_format($invoice->total, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>

                @if ($invoice->notes)
                    <div class="px-6 py-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Notes</p>
                        <p class="text-sm text-slate-600">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Right Sidebar --}}
        <div class="col-span-1 space-y-4">

            {{-- Amount --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-2">Total Amount</p>
                <p class="font-display text-4xl text-slate-800">
                    {{ $invoice->symbol }}{{ number_format($invoice->total, 2) }}
                </p>
                <p class="text-xs text-slate-400 mt-1">{{ $invoice->currency }}</p>
                <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-medium {{ $colors[$invoice->status] ?? '' }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>

            {{-- Currency Converter --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-3">Convert Total</p>
                <select id="convert-to"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 mb-3"
                    onchange="convertTotal(this)">
                    @foreach ($currencies as $code => $data)
                        @if ($code !== $invoice->currency)
                            <option value="{{ $code }}"
                                data-symbol="{{ $data['symbol'] }}"
                                data-rate="{{ $data['rate'] }}">
                                {{ $code }} — {{ $data['name'] }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <div id="converted-amount" class="text-center py-2">
                    <p class="text-2xl font-bold text-amber-500" id="converted-value">—</p>
                    <p class="text-xs text-slate-400 mt-1" id="converted-rate"></p>
                </div>
                <p class="text-xs text-slate-300 mt-3 text-center">Indicative rate · not financial advice</p>
            </div>

            {{-- Update Status --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-3">Update Status</p>
                <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="client_id"  value="{{ $invoice->client_id }}">
                    <input type="hidden" name="issue_date" value="{{ $invoice->issue_date }}">
                    <input type="hidden" name="due_date"   value="{{ $invoice->due_date }}">
                    <input type="hidden" name="currency"   value="{{ $invoice->currency }}">
                    <input type="hidden" name="notes"      value="{{ $invoice->notes }}">
                    @foreach ($invoice->items as $i => $item)
                        <input type="hidden" name="items[{{ $i }}][description]" value="{{ $item->description }}">
                        <input type="hidden" name="items[{{ $i }}][quantity]"    value="{{ $item->quantity }}">
                        <input type="hidden" name="items[{{ $i }}][unit_price]"  value="{{ $item->unit_price }}">
                    @endforeach
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer">
                        <option value="draft"   {{ $invoice->status === 'draft'   ? 'selected' : '' }}>Draft</option>
                        <option value="sent"    {{ $invoice->status === 'sent'    ? 'selected' : '' }}>Sent</option>
                        <option value="paid"    {{ $invoice->status === 'paid'    ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </form>
            </div>

        </div>
    </div>

    <script>
        const invoiceTotal    = {{ $invoice->total }};
        const invoiceCurrency = '{{ $invoice->currency }}';
        const fromRate        = {{ collect($currencies)->firstWhere(fn($d, $c) => $c === $invoice->currency)['rate'] ?? 1.0 }};

        function convertTotal(select) {
            const toRate   = parseFloat(select.options[select.selectedIndex].dataset.rate);
            const symbol   = select.options[select.selectedIndex].dataset.symbol;
            const toCode   = select.value;
            const inUsd    = invoiceTotal / fromRate;
            const result   = inUsd * toRate;
            const rate     = toRate / fromRate;

            document.getElementById('converted-value').textContent =
                symbol + result.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            document.getElementById('converted-rate').textContent =
                `1 ${invoiceCurrency} ≈ ${rate.toFixed(4)} ${toCode}`;
        }

        // Run on load so value shows immediately
        const sel = document.getElementById('convert-to');
        if (sel) convertTotal(sel);

        function openSendModal()  { document.getElementById('send-modal').classList.remove('hidden'); }
        function closeSendModal() { document.getElementById('send-modal').classList.add('hidden'); }
        function submitSendForm() { document.getElementById('send-invoice-form').submit(); }
    </script>

    {{-- Share link modal --}}
    @if (session('share_url'))
    <div id="share-modal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeShareModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 text-center mb-1">Share Link Ready</h3>
            <p class="text-sm text-slate-500 text-center mb-4">This link is valid for 7 days. Anyone with it can view the invoice.</p>
            <div class="flex gap-2 mb-4">
                <input id="share-url-input" type="text" readonly
                    value="{{ session('share_url') }}"
                    class="flex-1 text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-slate-600 focus:outline-none select-all">
                <button onclick="copyShareUrl()"
                    class="px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold text-sm rounded-lg transition-colors whitespace-nowrap">
                    Copy
                </button>
            </div>
            <p id="copy-feedback" class="text-xs text-emerald-500 text-center hidden mb-2">Copied to clipboard!</p>
            <button onclick="closeShareModal()"
                class="w-full px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                Done
            </button>
        </div>
    </div>
    <script>
        function closeShareModal() { document.getElementById('share-modal').classList.add('hidden'); }
        function copyShareUrl() {
            const input = document.getElementById('share-url-input');
            navigator.clipboard.writeText(input.value).then(() => {
                const fb = document.getElementById('copy-feedback');
                fb.classList.remove('hidden');
                setTimeout(() => fb.classList.add('hidden'), 2000);
            });
        }
    </script>
    @endif

    {{-- Send confirmation modal --}}
    <div id="send-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeSendModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">

            @php $mailReady = ! in_array(config('mail.default'), ['log', 'array']); @endphp

            <div class="w-12 h-12 {{ $mailReady ? 'bg-amber-100' : 'bg-orange-100' }} rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 {{ $mailReady ? 'text-amber-500' : 'text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-slate-800 text-center mb-1">Send Invoice?</h3>
            <p class="text-sm text-slate-500 text-center mb-1">
                This will email <strong class="text-slate-700">{{ $invoice->invoice_number }}</strong> to:
            </p>
            <p class="text-sm font-medium text-amber-600 text-center mb-4">{{ $invoice->client->email }}</p>

            @if (! $mailReady)
                <div class="bg-orange-50 border border-orange-200 rounded-xl px-4 py-3 mb-4">
                    <p class="text-xs font-semibold text-orange-700 mb-1">⚠ Email not configured</p>
                    <p class="text-xs text-orange-600 leading-relaxed">
                        <code class="bg-orange-100 px-1 rounded">MAIL_MAILER=log</code> is set — the invoice will be
                        written to <code class="bg-orange-100 px-1 rounded">storage/logs/laravel.log</code> instead of
                        delivered. Set a real mailer in <code class="bg-orange-100 px-1 rounded">.env</code> to send
                        actual emails.
                    </p>
                </div>
            @else
                <p class="text-xs text-slate-400 text-center mb-4">The PDF will be attached automatically.</p>
            @endif

            <div class="flex gap-3">
                <button onclick="closeSendModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button onclick="submitSendForm()"
                    class="flex-1 px-4 py-2.5 {{ $mailReady ? 'bg-amber-400 hover:bg-amber-500' : 'bg-orange-400 hover:bg-orange-500' }} text-slate-900 rounded-xl text-sm font-semibold transition-colors">
                    {{ $mailReady ? 'Send Now' : 'Send to Log' }}
                </button>
            </div>
        </div>
    </div>

@endsection
