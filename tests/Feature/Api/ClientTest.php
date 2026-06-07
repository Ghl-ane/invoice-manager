<?php

use App\Models\Client;
use App\Models\User;

test('unauthenticated request is rejected', function () {
    $this->getJson('/api/v1/clients')->assertUnauthorized();
});

test('user sees only their own clients', function () {
    $user  = User::factory()->create();
    $other = User::factory()->create();

    Client::factory()->count(3)->create(['user_id' => $user->id]);
    Client::factory()->count(2)->create(['user_id' => $other->id]);

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/clients')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('user can create a client', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/clients', [
            'name'    => 'Acme Corp',
            'email'   => 'acme@example.com',
            'phone'   => '+1234567890',
            'address' => '123 Main St',
        ])->assertCreated()
          ->assertJsonPath('data.name', 'Acme Corp');
});

test('duplicate email per user is rejected', function () {
    $user = User::factory()->create();
    Client::factory()->create(['user_id' => $user->id, 'email' => 'dup@example.com']);

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/clients', [
            'name'  => 'Another Client',
            'email' => 'dup@example.com',
        ])->assertUnprocessable()
          ->assertJsonValidationErrors(['email']);
});

test('same email is allowed for different users', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    Client::factory()->create(['user_id' => $userA->id, 'email' => 'shared@example.com']);

    $this->actingAs($userB, 'sanctum')
        ->postJson('/api/v1/clients', [
            'name'  => 'B Client',
            'email' => 'shared@example.com',
        ])->assertCreated();
});

test('user can update their client', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum')
        ->putJson("/api/v1/clients/{$client->id}", [
            'name'  => 'Updated Name',
            'email' => $client->email,
        ])->assertOk()
          ->assertJsonPath('data.name', 'Updated Name');
});

test('user cannot update another users client', function () {
    $owner  = User::factory()->create();
    $other  = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($other, 'sanctum')
        ->putJson("/api/v1/clients/{$client->id}", ['name' => 'Hack', 'email' => 'h@x.com'])
        ->assertForbidden();
});

test('user can delete their client', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/v1/clients/{$client->id}")
        ->assertOk();

    $this->assertDatabaseMissing('clients', ['id' => $client->id]);
});
