<?php

namespace Database\Seeders;

use App\Models\Brands;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brands::factory(40)->create();

    }
}
