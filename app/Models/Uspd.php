<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uspd extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = ['model', 'serial_number', 'lan_ip'];

    public function simCards(): HasMany
    {
        return $this->hasMany(SimCard::class);
    }
}
