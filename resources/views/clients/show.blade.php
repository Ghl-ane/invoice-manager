@extends('layouts.app')
@section('title', $client->name)
@section('subtitle', $client->email)

@section('header-actions')
    <a href="{{ route('clients.edit', $client) }}" class="text-sm text-slate-600 hover:text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-100 transition-colors border border-slate-200">
        Edit Client
    </a>
    <a href="{{ route('invoices.create') }}" class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold text-sm px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Invoice
    </a>
@endsection

@section('content')

<div class="grid grid-cols-3 gap-6">

    {{-- Client Info --}}
    <div class="col-span-1">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-display text-2xl">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">{{ $client->name }}</h3>
                    <p class="text-xs text-slate-400">Client since {{ $client->created_at->format('M Y') }}</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="text-slate-600">{{ $client->email }}</span>
                </div>
                @if($client->phone)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="text-slate-600">{{ $client->phone }}</span>
                </div>
                @endif
                @if($client->address)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-slate-600">{{ $client->address }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Client Invoices --}}
    <div class="col-span-2">
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-700 text-sm">Invoices for {{ $client->name }}</h3>
            </div>

            @if($client->invoices->isEmpty())
            <div class="text-center py-12">
                <p class="text-slate-400 text-sm">No invoices for this client yet.</p>
            </div>
            @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                        <th class="px-6 py-3 text-left">Invoice #</th>
                        <th class="px-6 py-3 text-left">Issue Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($client->invoices as $invoice)
                    @php
                        $colors = ['draft'=>'bg-slate-100 text-slate-600','sent'=>'bg-blue-100 text-blue-600','paid'=>'bg-emerald-100 text-emerald-600','overdue'=>'bg-red-100 text-red-600'];
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3">
                            <a href="{{ route('invoices.show', $invoice) }}" class="font-medium text-slate-700 hover:text-amber-500">{{ $invoice->invoice_number }}</a>
                        </td>
                        <td class="px-6 py-3 text-slate-500">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colors[$invoice->status] ?? '' }}">{{ ucfirst($invoice->status) }}</span>
                        </td>
                        <td class="px-6 py-3 text-right font-semibold text-slate-700">${{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

@endsection