<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersCreateSeeder::class,
            CountrySeeder::class,
            BrandSeeder::class,
            ModelSeeder::class,
            CustomersSeeder::class,
            SuppliersSeeder::class,
            InvoiceCompanySeeder::class,
            ExpensesTypeSeeder::class,
            WarehouseSeeder::class,
            OrderSeeder::class,
            OfferSeeder::class,
            OfficeAddressSeeder::class,
            ShipmentServiceSeeder::class,
            ShipmentAccountSeeder::class,
            ShipmentSeeder::class,
            LabelSeeder::class,
            LabelInvoiceSeeder::class,
        ]);

    }
}
