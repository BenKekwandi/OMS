<?php

namespace App\Exports;

use App\Models\Offers;
use Maatwebsite\Excel\Concerns\FromCollection;

class OfferExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Offers::all();
    }
}
