<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CryptoPortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'currency',
        'amount',
        'average_price',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function cryptoCurrency(): HasOne
    {
        return $this->hasOne(CryptoCurrency::class, 'symbol', 'currency');
    }
}
