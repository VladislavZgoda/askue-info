<?php

namespace Database\Seeders;

use App\Models\Meter;
use App\Models\SimCard;
use App\Models\Uspd;
use Illuminate\Database\Seeder;

class SimCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SimCard::factory()
            ->count(3)
            ->create();

        $uspds = Uspd::all();

        foreach ($uspds as $uspd) {
            SimCard::factory()
                ->count(rand(1, 2))
                ->for($uspd)
                ->create();
        }

        $metersByObject = Meter::whereNotNull('installation_object_id')
            ->get()
            ->groupBy('installation_object_id');

        foreach ($metersByObject as $objectId => $meters) {
            $simCards = SimCard::factory()
                ->count(2)
                ->create();

            foreach ($simCards as $simCard) {
                $simCard
                    ->meters()
                    ->attach($meters->pluck('id')->toArray());
            }
        }
    }
}
