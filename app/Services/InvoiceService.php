<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function create(User $user, array $data): Invoice
    {
        $invoice = Invoice::create([
            'user_id'        => $user->id,
            'client_id'      => $data['client_id'],
            'invoice_number' => $this->nextInvoiceNumber($user),
            'issue_date'     => $data['issue_date'],
            'due_date'       => $data['due_date'],
            'currency'       => $data['currency'] ?? 'USD',
            'notes'          => $data['notes'] ?? null,
            'status'         => 'draft',
            'total'          => 0,
        ]);

        $invoice->update(['total' => $this->saveItems($invoice, $data['items'])]);

        return $invoice->load('client', 'items');
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->items()->delete();

        $invoice->update([
            'client_id'  => $data['client_id'],
            'issue_date' => $data['issue_date'],
            'due_date'   => $data['due_date'],
            'status'     => $data['status'],
            'currency'   => $data['currency'] ?? $invoice->currency,
            'notes'      => $data['notes'] ?? null,
            'total'      => $this->saveItems($invoice, $data['items']),
        ]);

        return $invoice->load('client', 'items');
    }

    private function nextInvoiceNumber(User $user): string
    {
        return DB::transaction(function () use ($user) {
            $last = Invoice::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $next = $last ? (int) str_replace('INV-', '', $last->invoice_number) + 1 : 1;

            return 'INV-' . str_pad($next, 3, '0', STR_PAD_LEFT);
        });
    }

    private function saveItems(Invoice $invoice, array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'subtotal'    => $subtotal,
            ]);
            $total += $subtotal;
        }

        return $total;
    }
}
