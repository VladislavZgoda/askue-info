<?php

namespace App\Observers;

use App\Models\SimCard;

class SimCardObserver
{
    public function saving(SimCard $simCard)
    {
        if ($simCard->meters()->exists() && ! is_null($simCard->uspd_id)) {
            throw new \Exception('SimCard не может одновременно принадлежать Meter и Uspd');
        }
    }
}
