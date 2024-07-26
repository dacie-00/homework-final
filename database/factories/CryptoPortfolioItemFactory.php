<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CryptoPortfolioItem>
 */
class CryptoPortfolioItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::query()->where('type', 'investment')->get()->random()->id,
            'amount' => fake()->numberBetween(1, 10),
            'currency' => fake()->randomElement(['BTC', 'ETH', 'LTC', 'XRP', 'BCH', 'USDT']),
        ];
    }
}
