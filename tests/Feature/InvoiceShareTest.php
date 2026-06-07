<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\URL;

test('owner can generate a share link', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create(['user_id' => $user->id, 'client_id' => $client->id]);

    $this->actingAs($user)
        ->get(route('invoices.share-link', $invoice))
        ->assertRedirect(route('invoices.show', $invoice))
        ->assertSessionHas('share_url');
});

test('other user cannot generate a share link', function () {
    $owner   = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $owner->id]);
    $invoice = Invoice::factory()->create(['user_id' => $owner->id, 'client_id' => $client->id]);
    $other   = User::factory()->create();

    $this->actingAs($other)
        ->get(route('invoices.share-link', $invoice))
        ->assertForbidden();
});

test('valid signed URL shows the public invoice view', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create(['user_id' => $user->id, 'client_id' => $client->id]);

    $url = URL::signedRoute('invoices.shared', ['invoice' => $invoice], now()->addDays(7));

    $this->get($url)
        ->assertOk()
        ->assertSee($invoice->invoice_number)
        ->assertSee($client->name);
});

test('public invoice view requires no authentication', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create(['user_id' => $user->id, 'client_id' => $client->id]);

    $url = URL::signedRoute('invoices.shared', ['invoice' => $invoice], now()->addDays(7));

    // Deliberately unauthenticated request
    $this->get($url)->assertOk();
});

test('tampered signed URL is rejected', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create(['user_id' => $user->id, 'client_id' => $client->id]);

    $url = route('invoices.shared', ['invoice' => $invoice]); // no signature

    $this->get($url)->assertForbidden();
});

test('expired signed URL is rejected', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create(['user_id' => $user->id, 'client_id' => $client->id]);

    // Travel 8 days into the future so the 7-day link is expired
    $url = URL::signedRoute('invoices.shared', ['invoice' => $invoice], now()->addDays(7));

    $this->travel(8)->days();

    $this->get($url)->assertForbidden();
});
