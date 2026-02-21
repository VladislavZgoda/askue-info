<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meter>
 */
class MeterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model'         => $this->faker->randomElement(['CE303', 'CE308', 'Меркурий 236', 'Меркурий 234']),
            'serial_number' => $this->faker->unique()->numerify('########'),
            'installation_object_id'  => InstallationObjectFactory::class,
        ];
    }

    public function withoutInstallationObject()
    {
        return $this->state(fn(array $attributes) => [
            'installation_object_id' => null,
        ]);
    }
}
