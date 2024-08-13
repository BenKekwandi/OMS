<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsersCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Nael',
                'surname' => 'Alyousefi',
                'phone' => fake()->phoneNumber(),
                'country' => 'Yemen',
                'email' => 'nael.alyousefi@gmail.com',
                'password' => Hash::make('147258369'),
                'role' => 'admin',
            ],
            [
                'name' => fake()->name(),
                'surname' => fake()->lastname(),
                'phone' => fake()->phoneNumber(),
                'country' => fake()->country(),
                'email' => 'pm@gmail.com',
                'password' => Hash::make('147258369'),
                'role' => 'pm',
            ],
            [
                'name' => fake()->name(),
                'surname' => fake()->lastname(),
                'phone' => fake()->phoneNumber(),
                'country' => fake()->country(),
                'email' => 'sm@gmail.com',
                'password' => Hash::make('147258369'),
                'role' => 'sm',
            ],
            [
                'name' => fake()->name(),
                'surname' => fake()->lastname(),
                'phone' => fake()->phoneNumber(),
                'country' => fake()->country(),
                'email' => 'acc@gmail.com',
                'password' => Hash::make('147258369'),
                'role' => 'accounting',
            ],
            [
                'name' => fake()->name(),
                'surname' => fake()->lastname(),
                'phone' => fake()->phoneNumber(),
                'country' => fake()->country(),
                'email' => 'log@gmail.com',
                'password' => Hash::make('147258369'),
                'role' => 'logistic',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'surname' => $userData['surname'],
                'phone' => $userData['phone'],
                'country' => $userData['country'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            $user->assignRole($userData['role']);
        }
    }
}
