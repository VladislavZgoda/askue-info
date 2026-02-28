<?php

namespace Database\Seeders;

use App\Models\InstallationObject;
use Illuminate\Database\Seeder;

class InstallationObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InstallationObject::factory()
            ->count(3)
            ->hasMeters(2)
            ->hasUspds(2)
            ->create();
    }
}
