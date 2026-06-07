@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name)

@section('header-actions')
    <a href="{{ route('invoices.create') }}"
        class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold text-sm px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Invoice
    </a>
@endsection

@section('content')

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="stat-card bg-white rounded-xl p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Clients</p>
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-display text-slate-800">{{ $totalClients }}</p>
            <a href="{{ route('clients.index') }}" class="text-xs text-blue-500 hover:underline mt-1 block">View all →</a>
        </div>

        <div class="stat-card bg-white rounded-xl p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Invoices</p>
                <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-display text-slate-800">{{ $totalInvoices }}</p>
            <a href="{{ route('invoices.index') }}" class="text-xs text-violet-500 hover:underline mt-1 block">View all
                →</a>
        </div>

        <div class="stat-card bg-white rounded-xl p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Revenue Collected</p>
                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-display text-slate-800">≈${{ number_format($totalRevenue, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">USD equivalent · paid invoices</p>
        </div>

        <div class="stat-card bg-white rounded-xl p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending Amount</p>
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-display text-slate-800">≈${{ number_format($pendingAmount, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">USD equivalent · awaiting payment</p>
        </div>

    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-slate-700 text-sm">Monthly Revenue</h3>
                <p class="text-xs text-slate-400 mt-0.5">Paid invoices · USD equivalent · last 6 months</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-sm bg-amber-400 inline-block"></span>
                <span class="text-xs text-slate-400">Revenue (USD)</span>
            </div>
        </div>
        <div class="relative" style="height:220px">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Recent Invoices --}}
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-slate-700 text-sm">Recent Invoices</h3>
            <a href="{{ route('invoices.index') }}" class="text-xs text-amber-500 hover:underline">View all →</a>
        </div>

        @if ($recentInvoices->isEmpty())
            <div class="text-center py-12">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-slate-400 text-sm">No invoices yet</p>
                <a href="{{ route('invoices.create') }}"
                    class="mt-3 inline-block text-xs text-amber-500 hover:underline">Create your first invoice →</a>
            </div>
        @else
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                        <th class="px-6 py-3 text-left">Invoice #</th>
                        <th class="px-6 py-3 text-left">Client</th>
                        <th class="px-6 py-3 text-left">Due Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($recentInvoices as $invoice)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                    class="font-medium text-slate-700 hover:text-amber-500">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-3 text-slate-500">{{ $invoice->client->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-slate-500">
                                {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $colors = [
                                        'draft' => 'bg-slate-100 text-slate-600',
                                        'sent' => 'bg-blue-100 text-blue-600',
                                        'paid' => 'bg-emerald-100 text-emerald-600',
                                        'overdue' => 'bg-red-100 text-red-600',
                                    ];
                                @endphp
                                <span
                                    class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colors[$invoice->status] ?? '' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-slate-700">
                                {{ $invoice->symbol }}{{ number_format($invoice->total, 2) }}
                                <span class="text-xs text-slate-400 ml-1">{{ $invoice->currency }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels = @json($revenueChart['labels']);
    const data   = @json($revenueChart['data']);

    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Revenue (USD)',
                data,
                backgroundColor: 'rgba(251,191,36,0.85)',
                borderColor:     'rgba(251,191,36,1)',
                borderWidth:     0,
                borderRadius:    6,
                borderSkipped:   false,
            }],
        },
        options: {
            responsive:          true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' $' + ctx.parsed.y.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }),
                    },
                },
            },
            scales: {
                x: {
                    grid:  { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                },
                y: {
                    beginAtZero: true,
                    grid:  { color: '#f1f5f9' },
                    ticks: {
                        color: '#94a3b8',
                        font:  { size: 11 },
                        callback: v => '$' + v.toLocaleString(),
                    },
                },
            },
        },
    });
}());
</script>
@endpush

@endsection
