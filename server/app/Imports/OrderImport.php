<?php

namespace App\Imports;

use App\Models\Models;
use App\Models\Brands;
use App\Models\Customer;
use App\Models\Orders;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        Log::info('Row data: ', $row);
        $rules = [
            'customer' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'reference_number' => 'required|string|max:255',
            'other_features' => 'nullable|string|max:255',
            'deadline' => 'required'
        ];

        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $customer = Customer::whereRaw('LOWER(name) = ?', [strtolower($row['customer'])])->first();

        $brand = Brands::whereRaw('LOWER(name) = ?', [strtolower($row['brand'])])->first();

        if (!$customer) {
            throw new \Exception("Customer '{$row['customer']}' not found.");
        }

        if (!$brand) {
            throw new \Exception("Brand '{$row['brand']}' not found.");
        }

        $model = Models::where('reference', $row['reference_number'])
            ->where('brand_id', $brand->id)
            ->first();

        if (!$model) {
            throw new \Exception("Reference number '{$row['reference_number']}' not found in models.");
        }

        try {
            $parsedDeadline = Carbon::createFromTimestamp(($row['deadline'] - 25569) * 86400);

            if (!$parsedDeadline) {
                throw new \Exception("Invalid deadline format. Please use the d/m/Y format.");
            }

            $formattedDeadline = $parsedDeadline->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Error parsing deadline date: " . $e->getMessage());
        }

        return new Orders([
            'customer_id' => $customer->id,
            'brand_id' => $brand->id,
            'reference_number' => $row['reference_number'],
            'other_features' => $row['other_features'],
            'name_for_warranty' => $row['customer'],
            'deadline' => $formattedDeadline,
            'status' => 1,
        ]);
    }
}
