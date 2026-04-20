<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        return view('dashboard', [
            'totalClients'   => Client::where('user_id', $userId)->count(),
            'totalInvoices'  => Invoice::where('user_id', $userId)->count(),
            'totalRevenue'   => Invoice::where('user_id', $userId)->where('status', 'paid')->sum('total'),
            'pendingAmount'  => Invoice::where('user_id', $userId)->whereIn('status', ['sent', 'draft'])->sum('total'),
            'recentInvoices' => Invoice::where('user_id', $userId)->latest()->take(5)->with('client')->get(),
        ]);
    }
}