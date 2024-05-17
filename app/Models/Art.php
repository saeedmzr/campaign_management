<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Art extends Model
{
    use HasFactory;

    protected $table = "arts";
    protected $guarded = ['id'];
    protected $casts = ["submission" => "json"];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
