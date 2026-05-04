@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('subtitle', 'Invoice for ' . $invoice->client->name)

@section('header-actions')
    <a href="{{ route('invoices.edit', $invoice) }}"
        class="text-sm text-slate-600 hover:text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-100 transition-colors border border-slate-200">
        Edit
    </a>
    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete this invoice?')" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="text-sm text-red-500 hover:text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors border border-red-200">
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
        'overdue' => 'bg-red-100 text-red-600'
    ];
@endphp

<div class="grid grid-cols-3 gap-6">

    {{-- Invoice Main --}}
    <div class="col-span-2 space-y-5">

        {{-- Header Info --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="font-display text-2xl text-slate-800">{{ $invoice->invoice_number }}</h2>
                    <p class="text-slate-400 text-sm mt-1">Created {{ $invoice->created_at->format('M d, Y') }}</p>
                </div>
                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $colors[$invoice->status] ?? '' }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>

            <div class="grid grid-cols-3 gap-6 text-sm">
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
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="px-6 py-3 text-slate-700">{{ $item->description }}</td>
                        <td class="px-6 py-3 text-center text-slate-500">{{ $item->quantity }}</td>
                        <td class="px-6 py-3 text-right text-slate-500">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-6 py-3 text-right font-medium text-slate-700">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-slate-200 bg-slate-50">
                        <td colspan="3" class="px-6 py-3 text-right font-semibold text-slate-700">Total</td>
                        <td class="px-6 py-3 text-right font-bold text-lg text-slate-800">${{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($invoice->notes)
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
            <p class="font-display text-4xl text-slate-800">${{ number_format($invoice->total, 2) }}</p>
            <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-medium {{ $colors[$invoice->status] ?? '' }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <p class="text-xs text-slate-400 uppercase tracking-wide mb-3">Update Status</p>
            <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                <input type="hidden" name="issue_date" value="{{ $invoice->issue_date }}">
                <input type="hidden" name="due_date" value="{{ $invoice->due_date }}">
                <input type="hidden" name="notes" value="{{ $invoice->notes }}">
                @foreach($invoice->items as $i => $item)
                    <input type="hidden" name="items[{{ $i }}][description]" value="{{ $item->description }}">
                    <input type="hidden" name="items[{{ $i }}][quantity]" value="{{ $item->quantity }}">
                    <input type="hidden" name="items[{{ $i }}][unit_price]" value="{{ $item->unit_price }}">
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

@endsection