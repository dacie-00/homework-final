<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
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
            'user_id' => User::query()->get()->random()->id,
            'iban' => fake()->iban(),
            'type' => 'checking',
            'name' => fake()->colorName(),
            'currency' => Arr::random(Currency::CURRENCY_SYMBOLS),
            'amount' => fake()->numberBetween(100000, 1000000),
        ];
    }

    public function forUser(string $userId): self
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }
}
