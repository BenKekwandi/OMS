<?php

namespace App\Imports;

use App\Models\Brands;
use App\Models\Offers;
use App\Models\Supplier;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class OfferImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $supplier = Supplier::where('name', 'LIKE', $row['supplier'])->first();
        if (!$supplier) {
            throw new \Exception("Supplier with name '{$row['supplier']}' not found.");
        }

        $brand = Brands::where('name', 'LIKE', $row['brand'])->first();
        if (!$brand) {
            throw new \Exception("Brand with name '{$row['brand']}' not found.");
        }

        $warehouse = Warehouse::where('country', 'LIKE', $row['warehouse'])
            // ->where('location', 'LIKE', $row['location'])
            ->first();

        // if (!$warehouse) {
        //     throw new \Exception("Warehouse with country '{$row['warehouse']}' and location '{$row[location]}' not found.");
        // }

        $availability = match ($row['availability']) {
            'In shop' => 1,
            'To order' => 2,
            'In stock' => 3,
            default => 'Unavailable',
        };

        return new Offers([
            'supplier_id' => $supplier->id,
            'brand_id' => $brand->id,
            'reference_number' => $row['reference_number'],
            'other_features' => $row['other_features'],
            'discount' => $row['discount'],
            'rrp_price' => $row['rrp_price'],
            'net_price' => $row['net_price'],
            'availability' => $availability,
            'warehouse_id' => $warehouse->id,
            'order_days' => $row['order_days'],
            'serial_number' => $row['serial_number'],
        ]);
    }

}
