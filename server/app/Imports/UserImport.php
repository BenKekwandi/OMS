<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    protected $role;

    public function __construct($roleName)
    {
        $this->role = $roleName;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $name = $row[0];
            $surname = $row[1];
            $country = $row[2];
            $phone = $row[3];
            $email = $row[4];

            $user = User::where('name', $name)->where('surname', $surname)->first();

            if (! $user) {
                User::create([
                    'name' => $name,
                    'surname' => $surname,
                    'country' => $country,
                    'phone' => $phone,
                    'email' => $email,
                    'active' => 1,
                ]);
            } else {
                $user->update([
                    'name' => $name,
                    'surname' => $surname,
                    'country' => $country,
                    'phone' => $phone,
                    'email' => $email,
                ]);
            }

        }

        return response()->json([
            'status' => 'success',
            'message' => 'File Imported successfully.',
        ], 201);
    }
}
