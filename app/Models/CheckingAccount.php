<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckingAccount extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $fillable = [
        "name",
        "user_id",
        "currency",
        "amount",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
