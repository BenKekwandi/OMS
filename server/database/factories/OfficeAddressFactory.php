<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OfficeAddress>
 */
class OfficeAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_name' => fake()->name(),
            'company' => fake()->company(),
            'street_1' => fake()->streetAddress(),
            'street_2' => fake()->optional()->secondaryAddress(),
            'street_3' => fake()->optional()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->optional()->state(),
            'post_code' => fake()->postcode(),
            'country' => fake()->country(),
            'tax' => fake()->optional()->randomNumber(5),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
