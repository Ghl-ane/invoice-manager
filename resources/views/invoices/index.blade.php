@extends('layouts.app')
@section('title', 'Invoices')
@section('subtitle', $invoices->total() . ' total invoices')

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
    {{-- Search & Filter Bar --}}
    <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap gap-3 mb-4">

        {{-- Search input --}}
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice # or client..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent" />
        </div>

        {{-- Status filter --}}
        <select name="status"
            class="text-sm border border-slate-200 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-amber-400 text-slate-600">
            <option value="">All Statuses</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
        </select>

        {{-- Date from --}}
        <input type="date" name="from" value="{{ request('from') }}"
            class="text-sm border border-slate-200 rounded-lg px-3 py-2
                  focus:outline-none focus:ring-2 focus:ring-amber-400 text-slate-600" />

        {{-- Date to --}}
        <input type="date" name="to" value="{{ request('to') }}"
            class="text-sm border border-slate-200 rounded-lg px-3 py-2
                  focus:outline-none focus:ring-2 focus:ring-amber-400 text-slate-600" />

        {{-- Buttons --}}
        <button type="submit"
            class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold
                   text-sm px-4 py-2 rounded-lg transition-colors">
            Search
        </button>

        @if (request()->hasAny(['search', 'status', 'from', 'to']))
            <a href="{{ route('invoices.index') }}"
                class="text-sm px-4 py-2 rounded-lg border border-slate-200
                  text-slate-500 hover:bg-slate-50 transition-colors">
                Clear
            </a>
        @endif

    </form>

    <div class="bg-white rounded-xl border border-slate-200">
        @if ($invoices->isEmpty())
            <div class="text-center py-16">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>

                @if (request()->hasAny(['search', 'status', 'from', 'to']))
                    {{-- Search/filter returned nothing --}}
                    <p class="text-slate-500 font-medium mb-1">No invoices found</p>
                    <p class="text-slate-400 text-sm mb-3">No results match your search or filters</p>
                    <a href="{{ route('invoices.index') }}" class="text-amber-500 text-sm hover:underline">Clear filters
                        →</a>
                @else
                    {{-- Truly empty --}}
                    <p class="text-slate-400 text-sm mb-3">No invoices yet</p>
                    <a href="{{ route('invoices.create') }}" class="text-amber-500 text-sm hover:underline">Create your
                        first invoice →</a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                        <th class="px-6 py-3 text-left whitespace-nowrap">Invoice #</th>
                        <th class="px-6 py-3 text-left">Client</th>
                        <th class="px-6 py-3 text-left">Issue Date</th>
                        <th class="px-6 py-3 text-left">Due Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($invoices as $invoice)
                        @php
                            $colors = [
                                'draft' => 'bg-slate-100 text-slate-600',
                                'sent' => 'bg-blue-100 text-blue-600',
                                'paid' => 'bg-emerald-100 text-emerald-600',
                                'overdue' => 'bg-red-100 text-red-600',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                    class="font-medium text-slate-700 hover:text-amber-500">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $invoice->client->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colors[$invoice->status] ?? '' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-700">
                                {{ $invoice->symbol }}{{ number_format($invoice->total, 2) }}
                                <span class="text-xs text-slate-400 ml-1">{{ $invoice->currency }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="text-xs text-slate-500 hover:text-amber-500 px-3 py-1.5 rounded-lg hover:bg-amber-50 transition-colors">View</a>
                                    <a href="{{ route('invoices.edit', $invoice) }}"
                                        class="text-xs text-slate-500 hover:text-blue-500 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">Edit</a>
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                        onsubmit="confirmDelete(this); return false;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-xs text-slate-500 hover:text-red-500 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            @if ($invoices->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $invoices->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
