<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Label>
 */
class LabelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shipment_id' => Shipment::inRandomOrder()->first()->id,
            'kind' => 1,
            'status' => 1,
            'file'=>fake()->text(),
            'amount' => fake()->randomFloat(2,200,5000),
            'tracking_number' => fake()->uuid(),
            'expected_collection_at' => fake()->date(),
            'expected_delivery_at' => fake()->date(),
            'response' => 'success',
            'postmen_id' => fake()->uuid(),
        ];
    }
}
