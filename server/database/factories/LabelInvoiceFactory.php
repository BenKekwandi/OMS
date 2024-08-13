<?php

namespace Database\Factories;

use App\Models\Label;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Label>
 */
class LabelInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label_id' => Label::inRandomOrder()->first()->id,
            'kind' => fake()->randomNumber(2,true),
            'serial_number' => fake()->optional()->numerify('###########'),
            'copies' => fake()->randomNumber(),
            'date' => fake()->date(),
        ];
    }
}
