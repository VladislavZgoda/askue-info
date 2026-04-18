<?php

namespace Database\Factories;

use App\Models\SimCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SimCard>
 */
class SimCardFactory extends Factory
{
    private $allowedOperators = ['МТС', 'Билайн', 'МегаФон'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numerify('89#########'),
            'ip' => $this->faker->optional()->ipv4(),
            'operator' => $this->faker->randomElement($this->allowedOperators),
        ];
    }
}
