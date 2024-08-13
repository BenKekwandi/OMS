<?php

namespace Database\Seeders;

use App\Models\ShipmentAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShipmentAccount::factory(30)->create();
    }
}
