<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'user_id',
        'iban',
        'type',
        'currency',
        'amount',
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moneyTransfers(): BelongsToMany
    {
        return $this->belongsToMany(MoneyTransfer::class);
    }

    public function cryptoTransactions(): HasMany
    {
        return $this->hasMany(CryptoTransaction::class);
    }

    public function cryptoPortfolioItems(): HasMany
    {
        return $this->hasMany(CryptoPortfolioItem::class);
    }
}
