<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meter extends Model
{
    public function simCards(): BelongsToMany
    {
        return $this->belongsToMany(SimCard::class);
    }
}
