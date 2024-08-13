<?php

namespace Database\Seeders;

use App\Models\ShipmentService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShipmentService::factory(30)->create();
    }
}
