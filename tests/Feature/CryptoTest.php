<?php

use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\CryptoPortfolioItem;
use App\Models\User;
use App\Services\CryptoCurrencyService;
use Mockery\MockInterface;

it('purchases cryptocurrency', function () {
    $user = User::factory()->create();
    $this->mock(CryptoCurrencyService::class, function (MockInterface $mock) {
        $mock
            ->shouldReceive('search')
            ->andReturn(Collect([
                new CryptoCurrency('FOO', 40),
            ]));
        $mock
            ->shouldReceive('getTop')
            ->andReturn(collect([]));
    });
    $account = Account::factory()->create([
        'user_id' => $user->id,
        'iban' => 'ibanFoo',
        'type' => 'investment',
        'amount' => 100000,
        'currency' => 'USD',
    ]);

    $response = $this->actingAs($user)->post(
        route('crypto-transaction.store'),
        [
            'account' => $account->iban,
            'type' => 'buy',
            'amount' => 3,
            'currency' => 'FOO',
        ]
    );
    $response->assertRedirect(route('crypto.index'));
    $this->followRedirects($response)->assertStatus(200);

    $this->assertDatabaseHas('accounts',
        [
            'iban' => 'ibanFoo',
            'amount' => 88000
        ]);
    $this->assertDatabaseHas('crypto_portfolio_items',
        [
            'account_id' => $account->id,
            'amount' => 3,
            'currency' => 'FOO'
        ]);
});

it('sells cryptocurrency', function () {
    $user = User::factory()->create();
    $this->mock(CryptoCurrencyService::class, function (MockInterface $mock) {
        $mock
            ->shouldReceive('search')
            ->andReturn(Collect([
                new CryptoCurrency('FOO', 40),
            ]));
        $mock
            ->shouldReceive('getTop')
            ->andReturn(collect([]));
    });
    $account = Account::factory()->create([
        'user_id' => $user->id,
        'iban' => 'ibanFoo',
        'type' => 'investment',
        'amount' => 100000,
        'currency' => 'USD',
    ]);

    CryptoPortfolioItem::factory()->create([
        'account_id' => $account->id,
        'amount' => 7.5,
        'currency' => 'FOO',
    ]);

    $response = $this->actingAs($user)->post(
        route('crypto-transaction.store'),
        [
            'account' => $account->iban,
            'type' => 'sell',
            'amount' => 3,
            'currency' => 'FOO',
        ]
    );
    $response->assertRedirect(route('crypto.index'));
    $this->followRedirects($response)->assertStatus(200);

    $this->assertDatabaseHas('accounts',
        [
            'iban' => 'ibanFoo',
            'amount' => 112000
        ]);
    $this->assertDatabaseHas('crypto_portfolio_items',
        [
            'account_id' => $account->id,
            'amount' => 4.5,
            'currency' => 'FOO'
        ]);
});

