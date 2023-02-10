<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'location' => [
                'lat' => '30.29580370294291',
                'long' => '31.74261544636799',
            ],
            'delivery_cost' => $this->faker->numberBetween(10, 100),
            'delivery_period' => $this->faker->numberBetween(5, 120),
            'delivery_distance' => $this->faker->numberBetween(1, 30),
        ];
    }
}
