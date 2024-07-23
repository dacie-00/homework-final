<?php

namespace Database\Seeders;

use App\Models\CheckingAccount;
use App\Models\MoneyTransfer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\MoneyTransferFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        CheckingAccount::factory(20)->create();

        User::factory()->create([
            'id' => 'testUser',
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => 'testtest'
        ]);
        CheckingAccount::factory(2)->forUser('testUser')->create();
        MoneyTransfer::factory(100)->create();

        $accounts = CheckingAccount::all();
        foreach(MoneyTransfer::all() as $transfer) {
            $pickedAccounts = $accounts->random(2)->pluck('id')->toArray();
            $transfer->checkingAccounts()->attach(
                $pickedAccounts[0],
                ['type' => 'send']
            );
            $transfer->checkingAccounts()->attach(
                $pickedAccounts[1],
                ['type' => 'receive']
            );
        }
    }
}
