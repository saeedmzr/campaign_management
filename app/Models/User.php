<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    protected $guarded = ["id"];

    public function vote(): HasOne
    {
        return $this->hasOne(Vote::class);
    }
}
