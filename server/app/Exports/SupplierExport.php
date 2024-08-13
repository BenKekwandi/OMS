<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;

class SupplierExport implements FromCollection
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Supplier::where('user_id', $this->user)->get();
    }
}
