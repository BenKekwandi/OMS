<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Models;
use App\Models\Brands;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Models>
 */
class ModelsFactory extends Factory
{

     protected $model = Models::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => fake()->numerify('######'),
            'brand_id' => Brands::inRandomOrder()->first()->id,
            'image' => fake()->imageUrl(640, 480, 'business', true, 'Faker'),
        ];
    }
}
