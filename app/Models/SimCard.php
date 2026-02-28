<?php

namespace App\Models;

use App\Observers\SimCardObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([SimCardObserver::class])]
class SimCard extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = ['number', 'ip', 'operator'];

    public function meters(): BelongsToMany
    {
        return $this->belongsToMany(Meter::class)
            ->using(MeterSimCard::class);
    }

    public function uspd(): BelongsTo
    {
        return $this->belongsTo(Uspd::class);
    }
}
