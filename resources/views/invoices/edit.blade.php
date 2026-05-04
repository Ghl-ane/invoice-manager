@extends('layouts.app')
@section('title', 'Edit Invoice')
@section('subtitle', $invoice->invoice_number)

@section('content')

<form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoice-form">
@csrf @method('PUT')

<div class="grid grid-cols-3 gap-6">

    <div class="col-span-2 space-y-5">

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-700 text-sm mb-4">Invoice Details</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Client <span class="text-red-400">*</span></label>
                    <select name="client_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        <option value="draft"   {{ $invoice->status === 'draft'   ? 'selected' : '' }}>Draft</option>
                        <option value="sent"    {{ $invoice->status === 'sent'    ? 'selected' : '' }}>Sent</option>
                        <option value="paid"    {{ $invoice->status === 'paid'    ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Issue Date <span class="text-red-400">*</span></label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date) }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Due Date <span class="text-red-400">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date) }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-700 text-sm mb-4">Line Items</h3>

            <div class="grid grid-cols-12 gap-3 mb-2">
                <div class="col-span-5 text-xs font-medium text-slate-500 uppercase tracking-wide">Description</div>
                <div class="col-span-2 text-xs font-medium text-slate-500 uppercase tracking-wide">Qty</div>
                <div class="col-span-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Unit Price</div>
                <div class="col-span-2 text-xs font-medium text-slate-500 uppercase tracking-wide text-right">Subtotal</div>
            </div>

            <div id="items-container" class="space-y-3">
                @foreach($invoice->items as $i => $item)
                <div class="item-row grid grid-cols-12 gap-3 items-center">
                    <div class="col-span-5">
                        <input type="text" name="items[{{ $i }}][description]" value="{{ $item->description }}"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="items[{{ $i }}][quantity]" value="{{ $item->quantity }}" min="1"
                            class="item-qty w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="items[{{ $i }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="1"
                            class="item-price w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                    </div>
                    <div class="col-span-2 flex items-center justify-end gap-2">
                        <span class="item-subtotal text-sm font-medium text-slate-700">${{ number_format($item->subtotal, 2) }}</span>
                        <button type="button" onclick="removeItem(this)" class="text-slate-300 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="addItem()"
                class="mt-4 flex items-center gap-2 text-sm text-amber-500 hover:text-amber-600 font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Line Item
            </button>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-700 text-sm mb-4">Notes</h3>
            <textarea name="notes" rows="3"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none">{{ old('notes', $invoice->notes) }}</textarea>
        </div>

    </div>

    <div class="col-span-1">
        <div class="bg-white rounded-xl border border-slate-200 p-6 sticky top-24">
            <h3 class="font-semibold text-slate-700 text-sm mb-4">Summary</h3>
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Total</span>
                    <span id="summary-total" class="font-bold text-xl text-slate-800">${{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
            <button type="submit" class="w-full bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold text-sm py-3 rounded-lg transition-colors">
                Update Invoice
            </button>
            <a href="{{ route('invoices.show', $invoice) }}" class="w-full mt-2 block text-center text-sm text-slate-500 hover:text-slate-700 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                Cancel
            </a>
        </div>
    </div>

</div>
</form>

<script>
let itemIndex = {{ $invoice->items->count() }};

function addItem() {
    const container = document.getElementById('items-container');
    const row = document.createElement('div');
    row.className = 'item-row grid grid-cols-12 gap-3 items-center';
    row.innerHTML = `
        <div class="col-span-5">
            <input type="text" name="items[${itemIndex}][description]" placeholder="Service description"
                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1"
                class="item-qty w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${itemIndex}][unit_price]" value="0" min="0" step="0.01"
                class="item-price w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>
        <div class="col-span-2 flex items-center justify-end gap-2">
            <span class="item-subtotal text-sm font-medium text-slate-700">$0.00</span>
            <button type="button" onclick="removeItem(this)" class="text-slate-300 hover:text-red-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    `;
    container.appendChild(row);
    itemIndex++;
    attachListeners(row);
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        calculateTotal();
    }
}

function attachListeners(row) {
    row.querySelector('.item-qty').addEventListener('input', calculateTotal);
    row.querySelector('.item-price').addEventListener('input', calculateTotal);
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty   = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const sub   = qty * price;
        row.querySelector('.item-subtotal').textContent = '$' + sub.toFixed(2);
        total += sub;
    });
    document.getElementById('summary-total').textContent = '$' + total.toFixed(2);
}

document.querySelectorAll('.item-row').forEach(row => attachListeners(row));
</script>

@endsection