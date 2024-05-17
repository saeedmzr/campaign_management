<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function allRates(): HasManyThrough
    {
        return $this->hasManyThrough(Rate::class, Currency::class, 'id', 'currency_id')->orWhere('source_currency_id', $this->id);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
