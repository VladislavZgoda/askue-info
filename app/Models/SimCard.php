<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SimCard extends Model
{
    public function meters():BelongsToMany
    {
        return $this->belongsToMany(Meter::class);
    }
}
