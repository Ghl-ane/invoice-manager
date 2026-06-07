<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class CurrencyService
{
    private array $currencies;
    private string $base;

    public function __construct()
    {
        $this->base       = config('currencies.base', 'USD');
        $this->currencies = $this->loadRates();
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    public function all(): array
    {
        return $this->currencies;
    }

    public function supported(): array
    {
        return array_keys($this->currencies);
    }

    public function isSupported(string $code): bool
    {
        return isset($this->currencies[strtoupper($code)]);
    }

    public function symbol(string $code): string
    {
        return $this->currencies[strtoupper($code)]['symbol'] ?? '$';
    }

    public function name(string $code): string
    {
        return $this->currencies[strtoupper($code)]['name'] ?? $code;
    }

    /**
     * Whether live rates from the API are currently active (vs. static fallback).
     */
    public function usingLiveRates(): bool
    {
        return Cache::has('currency_rates');
    }

    /**
     * Force a refresh of cached rates. Useful for a scheduled command.
     */
    public function refreshRates(): bool
    {
        Cache::forget('currency_rates');
        $rates = $this->fetchFromApi();

        if ($rates) {
            Cache::put('currency_rates', $rates, config('services.currency_api.ttl', 3600));
            $this->currencies = $this->mergeRates($rates);
            return true;
        }

        return false;
    }

    /**
     * Convert an amount from one currency to another.
     * All conversions route through USD as the intermediary.
     */
    public function convert(float $amount, string $from, string $to): float
    {
        $from = strtoupper($from);
        $to   = strtoupper($to);

        if (! $this->isSupported($from)) {
            throw new InvalidArgumentException("Unsupported currency: {$from}");
        }
        if (! $this->isSupported($to)) {
            throw new InvalidArgumentException("Unsupported currency: {$to}");
        }

        if ($from === $to) {
            return round($amount, 2);
        }

        $inUsd = $amount / $this->currencies[$from]['rate'];

        return round($inUsd * $this->currencies[$to]['rate'], 2);
    }

    /**
     * Get the exchange rate from one currency to another.
     */
    public function rate(string $from, string $to): float
    {
        return $this->convert(1.0, $from, $to);
    }

    /**
     * Sum an array of mixed-currency amounts, converting each to a target currency.
     * Used by dashboard to show a unified total across invoices.
     *
     * @param  array<array{amount: float|int, currency: string}>  $rows
     */
    public function sumInCurrency(array $rows, string $targetCurrency = 'USD'): float
    {
        $total = 0.0;

        foreach ($rows as ['amount' => $amount, 'currency' => $currency]) {
            $total += $this->convert((float) $amount, $currency, $targetCurrency);
        }

        return round($total, 2);
    }

    // -------------------------------------------------------------------------
    // Rate loading
    // -------------------------------------------------------------------------

    private function loadRates(): array
    {
        $apiKey = config('services.currency_api.key');
        $static = config('currencies.supported');

        // No API key configured — use static fallback rates
        if (! $apiKey) {
            return $static;
        }

        // Try cached rates first (avoids an HTTP call on every request)
        $cached = Cache::get('currency_rates');

        if ($cached) {
            return $this->mergeRates($cached);
        }

        // Cache miss — fetch from FreeCurrencyAPI
        $live = $this->fetchFromApi();

        if ($live) {
            Cache::put('currency_rates', $live, config('services.currency_api.ttl', 3600));
            return $this->mergeRates($live);
        }

        // API failed — fall back to static rates silently
        return $static;
    }

    /**
     * Call FreeCurrencyAPI and return the raw rates array, or null on failure.
     *
     * @return array<string, float>|null  e.g. ['EUR' => 0.921, 'GBP' => 0.792, ...]
     */
    private function fetchFromApi(): ?array
    {
        $apiKey  = config('services.currency_api.key');
        $baseUrl = config('services.currency_api.base_url');
        $codes   = implode(',', array_keys(config('currencies.supported')));

        try {
            $response = Http::timeout(5)->get("{$baseUrl}/latest", [
                'apikey'        => $apiKey,
                'base_currency' => $this->base,
                'currencies'    => $codes,
            ]);

            if ($response->successful() && $data = $response->json('data')) {
                return $data;
            }

            Log::warning('FreeCurrencyAPI returned non-200', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::warning('FreeCurrencyAPI request failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Merge live rates (float, relative to USD) into the static currency definitions.
     * Preserves name and symbol from config; only replaces the rate.
     *
     * @param  array<string, float>  $liveRates
     */
    private function mergeRates(array $liveRates): array
    {
        $currencies = config('currencies.supported');

        foreach ($currencies as $code => &$data) {
            if (isset($liveRates[$code])) {
                $data['rate'] = (float) $liveRates[$code];
            }
        }

        return $currencies;
    }
}
