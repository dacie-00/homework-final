<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoTransaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'currency',
        'price',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
