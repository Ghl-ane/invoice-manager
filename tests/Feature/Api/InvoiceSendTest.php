<?php

use App\Jobs\SendInvoiceJob;
use App\Mail\InvoiceMail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

// Helper: create a user with a client and a draft invoice
function makeInvoiceWithClient(): array
{
    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'draft',
    ]);

    return [$user, $invoice];
}

test('send route dispatches SendInvoiceJob', function () {
    Queue::fake();

    [$user, $invoice] = makeInvoiceWithClient();

    $this->actingAs($user)
        ->post(route('invoices.send', $invoice))
        ->assertRedirect(route('invoices.show', $invoice))
        ->assertSessionHas('success');

    Queue::assertPushed(SendInvoiceJob::class, fn ($job) => $job->invoice->id === $invoice->id);
});

test('send route is blocked for other users', function () {
    Queue::fake();

    [, $invoice] = makeInvoiceWithClient();
    $other = User::factory()->create();

    $this->actingAs($other)
        ->post(route('invoices.send', $invoice))
        ->assertForbidden();

    Queue::assertNothingPushed();
});

test('unauthenticated request to send is redirected to login', function () {
    [, $invoice] = makeInvoiceWithClient();

    $this->post(route('invoices.send', $invoice))
        ->assertRedirect(route('login'));
});

test('SendInvoiceJob sends mail to client email', function () {
    Mail::fake();

    [$user, $invoice] = makeInvoiceWithClient();
    $invoice->loadMissing('client', 'items', 'user');

    $job = new SendInvoiceJob($invoice);
    $job->handle();

    Mail::assertSent(InvoiceMail::class, fn ($mail) =>
        $mail->hasTo($invoice->client->email)
    );
});

test('SendInvoiceJob advances draft status to sent', function () {
    Mail::fake();

    [$user, $invoice] = makeInvoiceWithClient();

    expect($invoice->status)->toBe('draft');

    (new SendInvoiceJob($invoice))->handle();

    expect($invoice->fresh()->status)->toBe('sent');
});

test('SendInvoiceJob does not regress paid status', function () {
    Mail::fake();

    $user    = User::factory()->create();
    $client  = Client::factory()->create(['user_id' => $user->id]);
    $invoice = Invoice::factory()->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'paid',
    ]);

    (new SendInvoiceJob($invoice))->handle();

    expect($invoice->fresh()->status)->toBe('paid');
});
