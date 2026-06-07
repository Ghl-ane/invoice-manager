<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Services\CurrencyService;

class DashboardController extends Controller
{
    public function __construct(private CurrencyService $currency) {}

    public function index()
    {
        $userId = auth()->id();

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

        return view('dashboard', [
            'totalClients'   => Client::where('user_id', $userId)->count(),
            'totalInvoices'  => Invoice::where('user_id', $userId)->count(),
            'totalRevenue'   => $this->currency->sumInCurrency($paidRows),
            'pendingAmount'  => $this->currency->sumInCurrency($pendingRows),
            'recentInvoices' => Invoice::where('user_id', $userId)->latest()->take(5)->with('client')->get(),
            'revenueChart'   => $this->buildRevenueChart($userId),
        ]);
    }

    private function buildRevenueChart(int $userId): array
    {
        $months = collect(range(5, 0))->map(function (int $monthsAgo) use ($userId) {
            $period = now()->subMonths($monthsAgo);

            $rows = Invoice::where('user_id', $userId)
                ->where('status', 'paid')
                ->whereYear('issue_date', $period->year)
                ->whereMonth('issue_date', $period->month)
                ->get(['total', 'currency'])
                ->map(fn ($i) => ['amount' => $i->total, 'currency' => $i->currency])
                ->all();

            return [
                'label'   => $period->format('M Y'),
                'revenue' => $this->currency->sumInCurrency($rows),
            ];
        });

        return [
            'labels' => $months->pluck('label')->values()->all(),
            'data'   => $months->pluck('revenue')->values()->all(),
        ];
    }
}
