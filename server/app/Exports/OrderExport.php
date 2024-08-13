<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Orders::all();
    }
}
