<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Supplier;
use App\Models\Models;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Offers;
use App\Models\Brands;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offers>
 */
class OffersFactory extends Factory
{
    protected $model = Offers::class;
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
        $rrpPrice = fake()->randomFloat(3, 10, 1000);
        $discount = fake()->randomFloat(20, 0, 60);
        $supplier = Supplier::inRandomOrder()->first();
        $country = $supplier->country_id;
        $vat = Country::find($country)->vat;

        $netPrice = $rrpPrice - (($rrpPrice * $vat) + ($rrpPrice * $discount * 0.01));

        $availability = fake()->numberBetween(1, 3);
        if ($availability === 1) {
            $orderDays = 0;
            $warehouseId = null;
            $serialNumber = null;
        } elseif ($availability === 2) {
            $orderDays = fake()->randomNumber();
            $warehouseId = null;
            $serialNumber = null;
        } else {
            $orderDays = 0;
            $warehouseId = Warehouse::inRandomOrder()->first()->id;
            $serialNumber = fake()->numerify('###########');
        }
        return [
            'brand_id' => $brand->id,
            'reference_number' => $model->reference,
            'other_features' => fake()->text(20),
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'discount' => $discount,
            'net_price' => $netPrice,
            'rrp_price' => $rrpPrice,
            'availability' => fake()->randomNumber(1, 3),
            'order_days' => $orderDays,
            'warehouse_id' => $warehouseId,
            'serial_number' => $serialNumber,
        ];
    }
}
