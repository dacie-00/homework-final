<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoPortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'currency',
        'amount',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
