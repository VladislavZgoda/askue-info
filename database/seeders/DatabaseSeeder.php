<?php

namespace Database\Seeders;

use App\Models\Meter;
use App\Models\User;
use App\Models\Uspd;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Вызов SimCardSeeder должен быть после InstallationObjectSeeder.
        $this->call([
            InstallationObjectSeeder::class,
            SimCardSeeder::class,
        ]);

        Meter::factory()
            ->count(2)
            ->withoutInstallationObject()
            ->create();

        Uspd::factory()
            ->count(2)
            ->withoutInstallationObject()
            ->create();
    }
}
