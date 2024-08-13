<?php

namespace Database\Seeders;

use App\Models\LabelInvoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LabelInvoice::factory(5)->create();
    }
}
