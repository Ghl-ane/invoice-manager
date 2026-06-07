<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;

function invoicePayload(Client $client): array
{
    return [
        'client_id'  => $client->id,
        'issue_date' => '2026-01-01',
        'due_date'   => '2026-01-31',
        'currency'   => 'USD',
        'items'      => [
            ['description' => 'Design work', 'quantity' => 2, 'unit_price' => 500],
            ['description' => 'Hosting',     'quantity' => 1, 'unit_price' => 100],
        ],
    ];
}

test('user can list their invoices', function () {
    $user = User::factory()->create();
    Invoice::factory()->count(3)->create(['user_id' => $user->id]);
    Invoice::factory()->count(2)->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/invoices')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('user can create an invoice with items', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/invoices', invoicePayload($client))
        ->assertCreated()
        ->assertJsonPath('data.currency', 'USD')
        ->assertJsonPath('data.total', 1100)
        ->assertJsonStructure(['data' => ['invoice_number', 'status', 'items']]);

    $this->assertDatabaseHas('invoices', ['invoice_number' => $response->json('data.invoice_number')]);
});

test('invoice requires at least one item', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/invoices', array_merge(invoicePayload($client), ['items' => []]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['items']);
});

test('due date must not be before issue date', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/invoices', array_merge(invoicePayload($client), [
            'issue_date' => '2026-02-01',
            'due_date'   => '2026-01-01',
        ]))->assertUnprocessable()
           ->assertJsonValidationErrors(['due_date']);
});

test('user can view their invoice', function () {
    $user    = User::factory()->create();
    $invoice = Invoice::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/invoices/{$invoice->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $invoice->id);
});

test('user cannot view another users invoice', function () {
    $user    = User::factory()->create();
    $invoice = Invoice::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/invoices/{$invoice->id}")
        ->assertForbidden();
});

test('user can update invoice status', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->draft()->create(['user_id' => $user->id, 'client_id' => $client->id]);

    $this->actingAs($user, 'sanctum')
        ->putJson("/api/v1/invoices/{$invoice->id}", array_merge(invoicePayload($client), ['status' => 'sent']))
        ->assertOk()
        ->assertJsonPath('data.status', 'sent');
});

test('invoice numbers increment per user', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $first  = $this->actingAs($user, 'sanctum')->postJson('/api/v1/invoices', invoicePayload($client));
    $second = $this->actingAs($user, 'sanctum')->postJson('/api/v1/invoices', invoicePayload($client));

    expect($first->json('data.invoice_number'))->toBe('INV-001');
    expect($second->json('data.invoice_number'))->toBe('INV-002');
});

test('user can delete their invoice', function () {
    $user    = User::factory()->create();
    $invoice = Invoice::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/v1/invoices/{$invoice->id}")
        ->assertOk();

    $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
});
