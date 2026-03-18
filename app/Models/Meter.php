<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['model', 'serial_number'])]
class Meter extends Model
{
    use HasFactory;

    public function installationObject(): BelongsTo
    {
        return $this->belongsTo(InstallationObject::class);
    }

    public function simCards(): BelongsToMany
    {
        return $this->belongsToMany(SimCard::class)
            ->using(MeterSimCard::class);
    }
}
