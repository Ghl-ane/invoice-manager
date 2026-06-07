<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class MarkOverdueInvoices extends Command
{
    protected $signature   = 'invoices:mark-overdue';
    protected $description = 'Mark sent invoices whose due date has passed as overdue';

    public function handle(): int
    {
        $updated = Invoice::where('status', 'sent')
            ->whereDate('due_date', '<', today())
            ->update(['status' => 'overdue']);

        $this->info("Marked {$updated} invoice(s) as overdue.");

        return Command::SUCCESS;
    }
}
