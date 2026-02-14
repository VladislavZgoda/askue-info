<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MeterSimCard extends Pivot
{
  protected $table = 'meter_sim_card';

  public $timestamps = false;

  protected static function booted()
  {
    static::creating(function ($pivot)
    {
      $simCard = SimCard::find($pivot->sim_card_id);

      if ($simCard && !is_null($simCard->uspd_id))
      {
        throw new \Exception('Нельзя привязать SimCard к Meter, так как она уже принадлежит Uspd');
      }
    });
  }
}
