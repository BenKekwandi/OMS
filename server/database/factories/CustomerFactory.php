<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Country;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'contact_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'country_id' => Country::inRandomOrder()->first()->id,
            'phone' => fake()->phoneNumber(),
            'shipping_address' => fake()->address(),
            'billing_address' => fake()->address(),
            'is_credit' => fake()->boolean(),
            'user_id' => 3,
        ];
    }
}
