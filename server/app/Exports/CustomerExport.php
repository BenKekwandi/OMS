<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromCollection
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
        return Customer::where('user_id', $this->user)->get();
    }
}
