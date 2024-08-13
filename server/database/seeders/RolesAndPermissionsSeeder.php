<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'logistic']);
        Role::create(['name' => 'accounting']);
        Role::create(['name' => 'pm']);
        Role::create(['name' => 'sm']);

        Permission::create(['name' => 'create logistic user']);
        Permission::create(['name' => 'edit logistic user']);
        Permission::create(['name' => 'delete logistic user']);

        Permission::create(['name' => 'create accounting user']);
        Permission::create(['name' => 'edit accounting user']);
        Permission::create(['name' => 'delete accounting user']);

        Permission::create(['name' => 'create pm user']);
        Permission::create(['name' => 'edit pm user']);
        Permission::create(['name' => 'delete pm user']);

        Permission::create(['name' => 'create sm user']);
        Permission::create(['name' => 'edit sm user']);
        Permission::create(['name' => 'delete sm user']);

        $permissions = Permission::all();

        $admin = Role::findByName('admin');
        $admin->syncPermissions($permissions);

      

    }
}
