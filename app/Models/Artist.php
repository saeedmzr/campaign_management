<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $guarded = ["id"];

    public function arts()
    {
        return $this->hasMany(Art::class);
    }
}
