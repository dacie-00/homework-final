<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CryptoTransaction>
 */
class CryptoTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::query()->get()->random()->id,
            'type' => fake()->randomElement(['buy', 'sell']),
            'amount' => fake()->numberBetween(1, 10),
            'currency' => fake()->randomElement(['BTC', 'ETH', 'LTC', 'XRP', 'BCH', 'USDT']),
            'price' => fake()->numberBetween(1000, 10000),
        ];
    }
}
