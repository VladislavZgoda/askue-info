<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'address'])]
class InstallationObject extends Model
{
    use HasFactory;

    public function meters(): HasMany
    {
        return $this->hasMany(Meter::class);
    }

    public function uspds(): HasMany
    {
        return $this->hasMany(Uspd::class);
    }
}
