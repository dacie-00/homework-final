<?php

use App\Models\Account;
use App\Models\User;

it('creates a new money transfer account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(
        route('account.store'),
        [
            'name' => 'testAccount',
            'type' => 'checking',
            'currency' => 'EUR'
        ]
    );

    $this->assertDatabaseHas('accounts', ['name' => 'testAccount', 'currency' => 'EUR']);
    $this->followRedirects($response)->assertStatus(200);
});

it('transfers money between two accounts', function () {
    $user = User::factory()->create();
    Account::factory()->create([
        'user_id' => $user->id,
        'iban' => 'sender',
        'type' => 'checking',
        'amount' => 1000,
        'currency' => 'EUR',
    ]);
    Account::factory()->create([
        'user_id' => $user->id,
        'iban' => 'receiver',
        'type' => 'checking',
        'amount' => 0,
        'currency' => 'EUR',
    ]);

    $response = $this->actingAs($user)->post(
        route('money-transfer.store'),
        [
            'account' => 'sender',
            'iban' => 'receiver',
            'name' => $user->name,
            'amount' => 1,
            'note' => null
        ]
    );
    $response->assertRedirect(route('account.index'));
    $this->followRedirects($response)->assertStatus(200);

    $this->assertDatabaseHas('accounts', ['iban' => 'sender', 'amount' => 900]);
    $this->assertDatabaseHas('accounts', ['iban' => 'receiver', 'amount' => 100]);
});

it("does not transfer money to a different user's investment account", function () {
    $senderUser = User::factory()->create();
    $receiverUser = User::factory()->create();
    Account::factory()->create([
        'user_id' => $senderUser->id,
        'iban' => 'sender',
        'type' => 'checking',
        'amount' => 1000,
        'currency' => 'EUR',
    ]);
    Account::factory()->create([
        'user_id' => $receiverUser->id,
        'iban' => 'receiver',
        'type' => 'investment',
        'amount' => 0,
        'currency' => 'EUR',
    ]);

    $response = $this->actingAs($senderUser)->post(
        route('money-transfer.store'),
        [
            'account' => 'sender',
            'iban' => 'receiver',
            'name' => $receiverUser->name,
            'amount' => 1,
            'note' => null
        ]
    );
    $response->assertSessionHasErrors('iban');
    $this->assertDatabaseHas('accounts', ['iban' => 'sender', 'amount' => 1000]);
    $this->assertDatabaseHas('accounts', ['iban' => 'receiver', 'amount' => 0]);
});

it("does not transfer money from investment account to different user's account", function () {
    $senderUser = User::factory()->create();
    $receiverUser = User::factory()->create();
    Account::factory()->create([
        'user_id' => $senderUser->id,
        'iban' => 'sender',
        'type' => 'investment',
        'amount' => 1000,
        'currency' => 'EUR',
    ]);
    Account::factory()->create([
        'user_id' => $receiverUser->id,
        'iban' => 'receiver',
        'type' => 'checking',
        'amount' => 0,
        'currency' => 'EUR',
    ]);

    $response = $this->actingAs($senderUser)->post(
        route('money-transfer.store'),
        [
            'account' => 'sender',
            'iban' => 'receiver',
            'name' => $receiverUser->name,
            'amount' => 1,
            'note' => null
        ]
    );
    $response->assertSessionHasErrors('account');
    $this->assertDatabaseHas('accounts', ['iban' => 'sender', 'amount' => 1000]);
    $this->assertDatabaseHas('accounts', ['iban' => 'receiver', 'amount' => 0]);
});
