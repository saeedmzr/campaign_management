<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rate extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function Currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function sourceCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'source_currency_id');
    }
}
