<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InstallationObject>
 */
class InstallationObjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->numerify('ТП-##'),
            'address' => $this->faker->streetAddress(),
            'type' => $this->faker
                ->randomElement(['Учёт внутри ТП', 'Щит учёта']),
        ];
    }
}
