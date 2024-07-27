<?php

use App\Models\Currency;

it('correctly assigns a symbol', function () {
    $currency = new Currency('FOO', 3.33);
    expect($currency->symbol())->toEqual('FOO');
});

it('correctly assigns an exchange rate', function () {
    $currency = new Currency('FOO', 3.33);
    expect($currency->exchangeRate())->toEqual(3.33);
});

it('has defined valid currencies', function () {
    expect(Currency::CURRENCY_SYMBOLS)->toBeArray();
});
