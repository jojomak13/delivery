<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentences(3, true),
            'logo' => '',
            'work_time' => [
                'from' => $this->faker->time(),
                'to' => $this->faker->time()
            ],
            'type_id' => Type::inRandomOrder()->first()->id,
            'seller_id' => Seller::factory()
        ];
    }
}
