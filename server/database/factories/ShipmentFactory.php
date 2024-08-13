<?php

namespace Database\Factories;

use App\Models\OfficeAddress;
use App\Models\ShipmentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $pickUpTime = fake()->dateTimeThisYear();
        return [
            'shipment_account_id' => ShipmentAccount::inRandomOrder()->first()->id,
            'shipping_type' => fake()->randomElement(['outgoing', 'incoming']),
            'automatic_shipping' => fake()->boolean(),
            'ship_to_title' => fake()->company(),
            'ship_from_title' => fake()->company(),
            'ship_to_id' => OfficeAddress::inRandomOrder()->first()->id,
            'ship_from_id' => OfficeAddress::inRandomOrder()->first()->id,
            'box_weight' => fake()->randomFloat(2, 0, 100),
            'box_width' => fake()->randomFloat(2, 0, 100),
            'box_height' => fake()->randomFloat(2, 0, 100),
            'box_depth' => fake()->randomFloat(2, 0, 100),
            'pick_up_time' => $pickUpTime,
            'deadline' => (clone $pickUpTime)->modify('+10 days'),
        ];
    }
}
