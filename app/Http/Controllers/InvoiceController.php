<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Show all invoices
    public function index()
    {
        $invoices = Invoice::where('user_id', auth()->id())
                           ->with('client')
                           ->latest()
                           ->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    // Show create form
    public function create()
    {
        $clients = Client::where('user_id', auth()->id())->get();
        return view('invoices.create', compact('clients'));
    }

    // Save new invoice
    public function store(Request $request)
    {
        $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'notes'      => 'nullable|string',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        // Create the invoice
        $invoice = Invoice::create([
            'user_id'        => auth()->id(),
            'client_id'      => $request->client_id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'issue_date'     => $request->issue_date,
            'due_date'       => $request->due_date,
            'notes'          => $request->notes,
            'status'         => 'draft',
            'total'          => 0,
        ]);

        // Save items and calculate total
        $total = 0;
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'subtotal'    => $subtotal,
            ]);
            $total += $subtotal;
        }

        $invoice->update(['total' => $total]);

        return redirect()->route('invoices.show', $invoice)
                         ->with('success', 'Invoice created successfully!');
    }

    // Show single invoice
    public function show(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        $invoice->load('client', 'items');
        return view('invoices.show', compact('invoice'));
    }

    // Show edit form
    public function edit(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        $clients = Client::where('user_id', auth()->id())->get();
        $invoice->load('items');
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    // Update invoice
    public function update(Request $request, Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);

        $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'status'     => 'required|in:draft,sent,paid,overdue',
            'notes'      => 'nullable|string',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        // Delete old items and recreate
        $invoice->items()->delete();

        $total = 0;
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'subtotal'    => $subtotal,
            ]);
            $total += $subtotal;
        }

        $invoice->update([
            'client_id'  => $request->client_id,
            'issue_date' => $request->issue_date,
            'due_date'   => $request->due_date,
            'status'     => $request->status,
            'notes'      => $request->notes,
            'total'      => $total,
        ]);

        return redirect()->route('invoices.show', $invoice)
                         ->with('success', 'Invoice updated successfully!');
    }

    // Delete invoice
    public function destroy(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice deleted successfully!');
    }
}