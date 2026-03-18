<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['model', 'serial_number', 'lan_ip'])]
class Uspd extends Model
{
    use HasFactory;

    public function installationObject(): BelongsTo
    {
        return $this->belongsTo(InstallationObject::class);
    }

    public function simCards(): HasMany
    {
        return $this->hasMany(SimCard::class);
    }
}
