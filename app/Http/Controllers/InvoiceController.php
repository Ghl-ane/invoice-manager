<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Jobs\SendInvoiceJob;
use App\Models\Client;
use App\Models\Invoice;
use App\Services\CurrencyService;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService,
        private CurrencyService $currencyService,
    ) {}

    public function index(Request $request): View
    {
        $invoices = Invoice::where('user_id', auth()->id())
            ->with('client')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($q) => $q
                    ->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                );
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('issue_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('issue_date', '<=', $request->to))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $clients    = Client::where('user_id', auth()->id())->get();
        $currencies = $this->currencyService->all();

        return view('invoices.create', compact('clients', 'currencies'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = $this->invoiceService->create(auth()->user(), $request->validated());

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice): View
    {
        $this->authorize('view', $invoice);

        return view('invoices.show', [
            'invoice'    => $invoice->load('client', 'items'),
            'currencies' => $this->currencyService->all(),
        ]);
    }

    public function edit(Invoice $invoice): View
    {
        $this->authorize('update', $invoice);

        $clients    = Client::where('user_id', auth()->id())->get();
        $currencies = $this->currencyService->all();

        return view('invoices.edit', [
            'invoice'    => $invoice->load('items'),
            'clients'    => $clients,
            'currencies' => $currencies,
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->authorize('update', $invoice);

        $this->invoiceService->update($invoice, $request->validated());

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->authorize('delete', $invoice);

        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    public function send(Invoice $invoice): RedirectResponse
    {
        $this->authorize('update', $invoice);

        SendInvoiceJob::dispatch($invoice);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice queued for delivery to {$invoice->client->email}.");
    }

    // Generates a 7-day signed share link and flashes it back to the show page.
    public function shareLink(Invoice $invoice): RedirectResponse
    {
        $this->authorize('view', $invoice);

        $url = URL::signedRoute('invoices.shared', ['invoice' => $invoice], now()->addDays(7));

        return redirect()->route('invoices.show', $invoice)
            ->with('share_url', $url);
    }

    // Public view — no auth required, but the signed middleware validates the URL.
    public function shared(Invoice $invoice): View
    {
        return view('invoices.shared', [
            'invoice' => $invoice->load('client', 'items'),
        ]);
    }

    public function downloadPdf(Invoice $invoice): Response
    {
        $this->authorize('view', $invoice);

        $invoice->load('client', 'items');
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))->setPaper('A4', 'portrait');

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}
