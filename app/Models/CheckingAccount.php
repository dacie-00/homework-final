<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CheckingAccount extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'user_id',
        'iban',
        'currency',
        'amount',
    ];

    public function user(): belongsTo {
        return $this->belongsTo(User::class);
    }

    public function moneyTransfer(): BelongsToMany
    {
        return $this->belongsToMany(MoneyTransfer::class);
    }
}
