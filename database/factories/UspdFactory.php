<?php

namespace Database\Factories;

use App\Models\InstallationObject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Uspd>
 */
class UspdFactory extends Factory
{
    private $allowedModels = [
        'RTR8A.LRsGE-2-1-RUFG',
        'RTR8A.LGE-2-2-RUF',
        'RTR58A.LG-1-1',
        'RTR58A.LG-2-1',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model'         => $this->faker->randomElement($this->allowedModels),
            'serial_number' => $this->faker->unique()->numberBetween(4000000, 4999999),
            'lan_ip'        => $this->faker->localIpv4(),
            'installation_object_id'  => InstallationObject::factory(),
        ];
    }

    public function withoutInstallationObject()
    {
        return $this->state(fn(array $attributes) => [
            'installation_object_id' => null,
        ]);
    }
}
