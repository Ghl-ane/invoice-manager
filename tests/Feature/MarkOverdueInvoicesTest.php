<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Carbon;

test('marks sent invoices past due date as overdue', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $overdue = Invoice::factory()->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'sent',
        'due_date'  => Carbon::yesterday(),
    ]);

    $this->artisan('invoices:mark-overdue')->assertSuccessful();

    expect($overdue->fresh()->status)->toBe('overdue');
});

test('ignores sent invoices not yet past due', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $notDue = Invoice::factory()->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'sent',
        'due_date'  => Carbon::tomorrow(),
    ]);

    $this->artisan('invoices:mark-overdue')->assertSuccessful();

    expect($notDue->fresh()->status)->toBe('sent');
});

test('does not touch draft or paid invoices', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $draft = Invoice::factory()->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'draft',
        'due_date'  => Carbon::yesterday(),
    ]);

    $paid = Invoice::factory()->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'paid',
        'due_date'  => Carbon::yesterday(),
    ]);

    $this->artisan('invoices:mark-overdue')->assertSuccessful();

    expect($draft->fresh()->status)->toBe('draft');
    expect($paid->fresh()->status)->toBe('paid');
});

test('outputs count of updated invoices', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    Invoice::factory()->count(3)->create([
        'user_id'   => $user->id,
        'client_id' => $client->id,
        'status'    => 'sent',
        'due_date'  => Carbon::yesterday(),
    ]);

    $this->artisan('invoices:mark-overdue')
        ->expectsOutputToContain('3')
        ->assertSuccessful();
});
