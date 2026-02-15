<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallationObject extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = ['name', 'address', 'type'];

    public function meters(): HasMany
    {
        return $this->hasMany(Meter::class);
    }

    public function uspds(): HasMany
    {
        return $this->hasMany(Uspd::class);
    }
}
