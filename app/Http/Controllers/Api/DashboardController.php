<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private CurrencyService $currency) {}

    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $paidRows = Invoice::where('user_id', $userId)
            ->where('status', 'paid')
            ->get(['total', 'currency'])
            ->map(fn ($i) => ['amount' => $i->total, 'currency' => $i->currency])
            ->all();

        $pendingRows = Invoice::where('user_id', $userId)
            ->whereIn('status', ['sent', 'draft'])
            ->get(['total', 'currency'])
            ->map(fn ($i) => ['amount' => $i->total, 'currency' => $i->currency])
            ->all();

        return response()->json([
            'total_clients'          => $request->user()->clients()->count(),
            'total_invoices'         => Invoice::where('user_id', $userId)->count(),
            'total_revenue_usd'      => $this->currency->sumInCurrency($paidRows),
            'pending_amount_usd'     => $this->currency->sumInCurrency($pendingRows),
            'revenue_note'           => 'Amounts converted to USD using indicative rates.',
            'recent_invoices'        => Invoice::where('user_id', $userId)
                ->with('client')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($inv) => [
                    'id'             => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'client_name'    => $inv->client->name,
                    'status'         => $inv->status,
                    'total'          => $inv->total,
                    'currency'       => $inv->currency,
                    'symbol'         => $inv->symbol,
                    'due_date'       => $inv->due_date,
                ]),
        ]);
    }
}
