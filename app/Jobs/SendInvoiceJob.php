<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(public Invoice $invoice) {}

    public function handle(): void
    {
        $this->invoice->loadMissing('client', 'items', 'user');

        Mail::to($this->invoice->client->email)
            ->send(new InvoiceMail($this->invoice));

        // Only advance the status forward — never regress paid/overdue back to sent
        if ($this->invoice->status === 'draft') {
            $this->invoice->update(['status' => 'sent']);
        }
    }
}
