<?php

namespace Database\Factories;

use App\Models\InstallationObject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstallationObject>
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
        ];
    }
}
