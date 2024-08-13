<?php

namespace Database\Seeders;

use App\Models\Invoice_company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice_company::factory(10)->create();
    }
}
