<?php

namespace Database\Seeders;

use App\Models\OfficeAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfficeAddress::factory(20)->create();
    }
}
