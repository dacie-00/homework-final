<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MoneyTransfer extends Model
{
    use HasFactory;
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $fillable = [
        "amount_sent",
        "currency_sent",
        "amount_received",
        "currency_received",
    ];

    public function checkingAccounts(): BelongsToMany
    {
        return $this->belongsToMany(CheckingAccount::class);
    }
}
