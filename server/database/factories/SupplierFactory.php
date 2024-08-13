<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;
use App\Models\Country;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'country_id' => Country::inRandomOrder()->first()->id,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'primary_name' => $name,
            'opening_time' => fake()->time('H:i'),
            'closing_time' => fake()->time('H:i'),
            'invoice_delivery_rules' => fake()->text(),
            'tax' => fake()->unique()->randomNumber(),
            'is_credit' => fake()->boolean(),
            'user_id' => 2,
        ];
    }
}
