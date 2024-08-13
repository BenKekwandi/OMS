<?php

namespace Database\Factories;

use App\Models\Orders;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Orders::inRandomOrder()->first()->id,
            'shipment_id' => Shipment::inRandomOrder()->first()->id,
        ];
    }
}
