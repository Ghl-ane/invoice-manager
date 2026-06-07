<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyService $currency) {}

    public function index(): JsonResponse
    {
        $list = collect($this->currency->all())->map(fn ($data, $code) => [
            'code'         => $code,
            'name'         => $data['name'],
            'symbol'       => $data['symbol'],
            'rate_to_usd'  => $data['rate'],
        ])->values();

        return response()->json(['data' => $list]);
    }

    public function convert(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'from'   => 'required|string|size:3',
            'to'     => 'required|string|size:3',
        ]);

        if (! $this->currency->isSupported($data['from'])) {
            return response()->json(['message' => "Unsupported currency: {$data['from']}"], 422);
        }

        if (! $this->currency->isSupported($data['to'])) {
            return response()->json(['message' => "Unsupported currency: {$data['to']}"], 422);
        }

        $converted = $this->currency->convert((float) $data['amount'], $data['from'], $data['to']);

        return response()->json([
            'from'      => strtoupper($data['from']),
            'to'        => strtoupper($data['to']),
            'amount'    => (float) $data['amount'],
            'converted' => $converted,
            'rate'      => $this->currency->rate($data['from'], $data['to']),
            'symbol'    => $this->currency->symbol($data['to']),
        ]);
    }
}
