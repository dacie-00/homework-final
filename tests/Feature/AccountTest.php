<?php

use App\Models\CheckingAccount;
use App\Models\User;

it('creates a new money transfer account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(
        route('account.store'),
        ['name' => 'testAccount', 'currency' => 'EUR']
    );

    $this->assertDatabaseHas('checking_accounts', ['name' => 'testAccount', 'currency' => 'EUR']);
    $this->followRedirects($response)->assertStatus(200);
});

it('transfers money between two accounts', function () {
    $user = User::factory()->create();
    CheckingAccount::factory()->create([
        'user_id' => $user->id,
        'amount' => 1000,
        'currency' => 'EUR',
        'iban' => 'sender',
    ]);
    CheckingAccount::factory()->create([
        'user_id' => $user->id,
        'amount' => 0,
        'currency' => 'EUR',
        'iban' => 'receiver',
    ]);

    $response = $this->actingAs($user)->post(
        route('money-transfer.store'),
        ['account' => 'sender', 'iban' => 'receiver', 'name' => $user->name, 'amount' => 1, 'note' => null]
    );
    $response->assertRedirect(route('account.index'));
    $this->followRedirects($response)->assertStatus(200);

    $this->assertDatabaseHas('checking_accounts', ['iban' => 'sender', 'amount' => 900]);
    $this->assertDatabaseHas('checking_accounts', ['iban' => 'receiver', 'amount' => 100]);
});
