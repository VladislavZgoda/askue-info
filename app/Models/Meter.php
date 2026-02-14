<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meter extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = ['model', 'serial_number'];

    public function simCards(): BelongsToMany
    {
        return $this->belongsToMany(SimCard::class)
                    ->using(MeterSimCard::class);
    }
}
