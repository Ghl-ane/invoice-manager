<?php

use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Flush currency cache between tests to prevent rate bleed
    Cache::forget('currency_rates');
});

// --- CurrencyService unit tests (static rates, no API key) ---

test('converts USD to EUR correctly', function () {
    $service = app(CurrencyService::class);
    $result  = $service->convert(100, 'USD', 'EUR');

    expect($result)->toBeFloat()->toBeLessThan(100);
});

test('converting to same currency returns same amount', function () {
    $service = app(CurrencyService::class);
    expect($service->convert(250.50, 'USD', 'USD'))->toBe(250.5);
});

test('conversion is reversible within rounding', function () {
    $service   = app(CurrencyService::class);
    $converted = $service->convert(100, 'USD', 'EUR');
    $backToUsd = $service->convert($converted, 'EUR', 'USD');

    expect(abs($backToUsd - 100))->toBeLessThan(0.02);
});

test('unsupported currency throws exception', function () {
    $service = app(CurrencyService::class);
    $service->convert(100, 'USD', 'XYZ');
})->throws(InvalidArgumentException::class);

test('sum in currency converts mixed currencies', function () {
    $service = app(CurrencyService::class);
    $rows    = [
        ['amount' => 100, 'currency' => 'USD'],
        ['amount' => 100, 'currency' => 'USD'],
    ];
    expect($service->sumInCurrency($rows, 'USD'))->toBe(200.0);
});

// --- Live rate fetching (Http::fake) ---

test('service uses live rates from API when key is configured', function () {
    Http::fake([
        'api.freecurrencyapi.com/*' => Http::response([
            'data' => [
                'USD' => 1.0,
                'EUR' => 0.95,
                'GBP' => 0.82,
                'MAD' => 10.5,
                'DZD' => 135.0,
                'SAR' => 3.75,
                'AED' => 3.67,
                'TND' => 3.10,
                'MRU' => 39.5,
                'CAD' => 1.36,
                'AUD' => 1.54,
            ],
        ], 200),
    ]);

    config(['services.currency_api.key' => 'fake-test-key']);

    $service   = new CurrencyService();
    $converted = $service->convert(100, 'USD', 'EUR');

    // With live rate EUR = 0.95, 100 USD → 95.00 EUR
    expect($converted)->toBe(95.0);
});

test('service caches live rates and does not re-fetch', function () {
    Http::fake([
        'api.freecurrencyapi.com/*' => Http::response([
            'data' => ['USD' => 1.0, 'EUR' => 0.95, 'GBP' => 0.82, 'MAD' => 10.5,
                       'DZD' => 135.0, 'SAR' => 3.75, 'AED' => 3.67, 'TND' => 3.10,
                       'MRU' => 39.5, 'CAD' => 1.36, 'AUD' => 1.54],
        ], 200),
    ]);

    config(['services.currency_api.key' => 'fake-test-key']);

    new CurrencyService(); // first instantiation — hits API
    new CurrencyService(); // second — should hit cache

    // Only one real HTTP call should have been made
    Http::assertSentCount(1);

    expect(Cache::has('currency_rates'))->toBeTrue();
});

test('service falls back to static rates when API fails', function () {
    Http::fake([
        'api.freecurrencyapi.com/*' => Http::response([], 500),
    ]);

    config(['services.currency_api.key' => 'fake-test-key']);

    $service = new CurrencyService();

    // Static fallback rate for EUR is 0.92 in config/currencies.php
    $converted = $service->convert(100, 'USD', 'EUR');

    expect($converted)->toBe(92.0);
    expect(Cache::has('currency_rates'))->toBeFalse(); // failure must not be cached
});

test('service falls back to static rates when API key is absent', function () {
    Http::fake(); // Should never be called

    config(['services.currency_api.key' => null]);

    $service = new CurrencyService();

    Http::assertNothingSent();

    $converted = $service->convert(100, 'USD', 'EUR');
    expect($converted)->toBeFloat()->toBeLessThan(100);
});

// --- API endpoints ---

test('authenticated user can list currencies', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/currencies')
        ->assertOk()
        ->assertJsonStructure(['data' => [['code', 'name', 'symbol', 'rate_to_usd']]]);

    expect($response->json('data'))->toHaveCount(11);
});

test('authenticated user can convert currency', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/currencies/convert?amount=100&from=USD&to=EUR')
        ->assertOk()
        ->assertJsonStructure(['from', 'to', 'amount', 'converted', 'rate', 'symbol'])
        ->assertJsonPath('from', 'USD')
        ->assertJsonPath('to', 'EUR');
});

test('conversion with unsupported currency returns 422', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/currencies/convert?amount=100&from=USD&to=XYZ')
        ->assertStatus(422);
});

test('conversion requires amount', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/currencies/convert?from=USD&to=EUR')
        ->assertUnprocessable();
});
