<?php

namespace Database\Seeders;

use App\Models\Expenses_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseTypes = ['Gas', 'Repairs', 'Storage', 'Shipping', 'Maintenance', 'Insurance'];

        foreach ($expenseTypes as $expense) {
            Expenses_type::create([
                'name' => $expense,
            ]);
        }
    }
}
