<?php

namespace Database\Factories;

use App\Models\MoneyTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MoneyTransfer>
 */
class MoneyTransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount_sent' => fake()->numberBetween(1000, 10000),
            'currency_sent' => fake()->currencyCode(),
            'amount_received' => fake()->numberBetween(1000, 10000),
            'currency_received' => fake()->currencyCode(),
            'note' => fake()->text(),
        ];
    }
}
