<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    protected $role;

    public function __construct($roleName)
    {
        $this->role = $roleName;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::role($this->role)
            ->select(
                'id',
                'name',
                'surname',
                'country',
                'phone',
                'email',
                'active',
            )->get();
    }
}
