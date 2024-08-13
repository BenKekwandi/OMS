<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice_company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice_company>
 */
class Invoice_companyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => fake()->company(),
            'country' => fake()->country(),
            'location' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'contact_name' => fake()->name(),
        ];
    }
}
