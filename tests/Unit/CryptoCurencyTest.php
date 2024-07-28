<?php

use App\Models\CryptoCurrencyOld;

it('correctly assigns a symbol to cryptocurrency', function () {
    $currency = new CryptoCurrency('FOO', 3.33);
    expect($currency->symbol())->toEqual('FOO');
});

it('correctly assigns a price to cryptocurrency', function () {
    $currency = new CryptoCurrencyOld('FOO', 3.33);
    expect($currency->price())->toEqual(3.33);
});
