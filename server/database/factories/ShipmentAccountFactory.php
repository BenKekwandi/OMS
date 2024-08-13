<?php

namespace Database\Factories;

use App\Models\ShipmentService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShipmentAccount>
 */
class ShipmentAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['incoming', 'outgoing'];

        return [
            'shipment_service_id' => ShipmentService::inRandomOrder()->first()->id,
            'title' => fake()->randomElement($types),
            'address' => fake()->address(),
            'postmen_id' => fake()->optional()->uuid(),
        ];
    }
}
