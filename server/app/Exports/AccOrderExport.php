<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Log;

class AccOrderExport implements FromCollection, ShouldAutoSize, WithEvents
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }
    public function collection()
    {
        $orders = Orders::with(
            'supplier',
            'customer',
            'brand',
            'offer',
            'proposal',
            'invoice',
        )->whereIn('id', $this->ids)->get();

        $data = [];
        $customerInvoice = null;
        $supplierInvoice = null;
        foreach ($orders as $order) {
            $invoices = $order->invoice;
            foreach ($invoices as $invoice) {
                if ($invoice->is_customer) {
                    $customerInvoice = $invoice->is_paid ? 'Customer Invoice (Paid)' : 'Customer Invoice (Unpaid)';
                } else {
                    $supplierInvoice = $invoice->is_paid ? 'Supplier Invoice (Paid)' : 'Supplier Invoice (Unpaid)';
                }
            }
            $rowData = [
                $order->id,
                $order->confirmed_at,
                $order->status,
                $order->brand->name,
                $order->reference_number,
                $order->offer->net_price,
                $order->supplier->name,
                $supplierInvoice ? $supplierInvoice : 'No Invoice',
                $order->customer->name,
                $customerInvoice ? $customerInvoice : 'No Invoice',
            ];

            $data[] = $rowData;
        }

        return collect($data);

    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();

                // Set background color based on invoice payment status
                for ($row = 2; $row <= $highestRow; $row++) {
                    $supplierInvoiceStatus = $event->sheet->getCell('G' . $row)->getValue(); // Column G for supplier invoice status
                    $customerInvoiceStatus = $event->sheet->getCell('I' . $row)->getValue(); // Column I for customer invoice status
    
                    $supplierBackgroundColor = strpos($supplierInvoiceStatus, 'Paid') !== false ? '#00FF00' : '#FF0000'; // Green for paid, red for unpaid
                    $customerBackgroundColor = strpos($customerInvoiceStatus, 'Paid') !== false ? '#00FF00' : '#FF0000'; // Green for paid, red for unpaid
    
                    // Apply styling to supplier invoice column
                    $event->sheet->getStyle('G' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => [
                                'rgb' => $supplierBackgroundColor,
                            ],
                        ],
                    ]);

                    // Apply styling to customer invoice column
                    $event->sheet->getStyle('I' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => [
                                'rgb' => $customerBackgroundColor,
                            ],
                        ],
                    ]);
                }
            },
        ];
    }
}
