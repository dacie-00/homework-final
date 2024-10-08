<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\CryptoPortfolioItem;
use App\Models\CryptoTransaction;
use App\Models\MoneyTransfer;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Account::factory(20)->create();

        User::factory()->create([
            'id' => 'testUser',
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => 'testtest',
        ]);
        Account::factory(2)->create(['user_id' => 'testUser']);
        Account::factory(1)->create(['user_id' => 'testUser', 'type' => Account::TYPE_INVESTMENT]);

        MoneyTransfer::factory(100)->create();

        CryptoTransaction::factory(100)->create();
        CryptoPortfolioItem::factory(100)->create();


        $accounts = Account::all();
        foreach (MoneyTransfer::all() as $transfer) {
            $pickedAccounts = $accounts->random(2)->pluck('id')->toArray();
            $transfer->accounts()->attach(
                $pickedAccounts[0],
                ['type' => 'send']
            );
            $transfer->accounts()->attach(
                $pickedAccounts[1],
                ['type' => 'receive']
            );
        }
    }
}
