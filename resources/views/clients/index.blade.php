@extends('layouts.app')
@section('title', 'Clients')
@section('subtitle', $clients->total() . ' total clients')

@section('header-actions')
    <a href="{{ route('clients.create') }}"
        class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold text-sm px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Client
    </a>
@endsection

@section('content')

    <div class="bg-white rounded-xl border border-slate-200">
        @if ($clients->isEmpty())
            <div class="text-center py-16">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-slate-400 text-sm mb-3">No clients yet</p>
                <a href="{{ route('clients.create') }}" class="text-amber-500 text-sm hover:underline">Add your first client
                    →</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Phone</th>
                        <th class="px-6 py-3 text-left">Invoices</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($clients as $client)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                    <a href="{{ route('clients.show', $client) }}"
                                        class="font-medium text-slate-700 hover:text-amber-500">{{ $client->name }}</a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $client->email }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $client->phone ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    {{ $client->invoices_count ?? $client->invoices->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('clients.show', $client) }}"
                                        class="text-xs text-slate-500 hover:text-amber-500 px-3 py-1.5 rounded-lg hover:bg-amber-50 transition-colors">View</a>
                                    <a href="{{ route('clients.edit', $client) }}"
                                        class="text-xs text-slate-500 hover:text-blue-500 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">Edit</a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST"
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

            {{-- Pagination --}}
            @if ($clients->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $clients->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
