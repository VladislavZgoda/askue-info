<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table(name: 'meter_sim_card', timestamps: false)]
class MeterSimCard extends Pivot
{
    protected static function booted()
    {
        static::creating(function ($pivot) {
            $simCard = SimCard::find($pivot->sim_card_id);
            $newMeter = Meter::find($pivot->meter_id);

            if ($simCard && ! is_null($simCard->uspd_id)) {
                throw new \Exception('Нельзя привязать SimCard к Meter, так как она уже принадлежит Uspd');
            }

            $existingMeters = $simCard->meters;
            $allMeters = $existingMeters->push($newMeter);

            $installationObjectIds = $allMeters->pluck('installation_object_id')->unique();

            if ($installationObjectIds->count() > 1) {
                throw new \Exception('SimCard может быть привязана только к Meter, принадлежащим одному InstallationObject');
            }

            if ($installationObjectIds->contains(null)) {
                throw new \Exception('Все Meter должны иметь InstallationObject');
            }
        });
    }
}
