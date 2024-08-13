<?php

namespace Database\Seeders;

use App\Models\Models;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Models::factory(100)->create();
    }
}
