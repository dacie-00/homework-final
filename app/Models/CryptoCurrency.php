<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'rank',
        'symbol',
        'price',
    ];

    public function icon(): ?string
    {
        return 'storage/app/public/cryptocurrency-' . $this->symbol . '.png';
    }
}
