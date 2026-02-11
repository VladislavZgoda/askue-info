<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uspd extends Model
{
    public function simCards(): HasMany
    {
        return $this->hasMany(SimCard::class);
    }
}
