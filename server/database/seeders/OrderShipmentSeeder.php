<?php

namespace Database\Seeders;

use App\Models\OrderShipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderShipment::factory(20)->create();

    }
}
