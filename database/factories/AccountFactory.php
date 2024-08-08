<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'user_id' => User::factory()->create()->id,
            'iban' => fake()->iban(),
            'type' => Account::TYPE_CHECKING,
            'name' => fake()->colorName(),
            'currency' => Arr::random(Currency::CURRENCY_SYMBOLS),
            'amount' => fake()->numberBetween(100000, 1000000),
        ];
    }
}
