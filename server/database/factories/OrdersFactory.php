<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Models;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Orders;
use App\Models\Brands;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = null;
        while (!$model) {
            $brand = Brands::inRandomOrder()->first();

            if ($brand) {
                $model = Models::where('brand_id', $brand->id)->inRandomOrder()->first();
            }
        }

        return [
            'brand_id' => $brand->id,
            'reference_number' => $model->reference,
            'deadline' => fake()->dateTimeBetween('+3 days', '+1 year'),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'other_features' => fake()->text(20)
        ];
    }
}
