<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MissingReceiptsCheckImport implements ToCollection, WithHeadingRow
{
    public $missingOrders = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $orderId = $row['booking_sn'] ?? $row['no_pesanan'] ?? null;
            $trackingNumber = $row['no_resi'] ?? null;
            
            if (!empty($orderId) && empty($trackingNumber)) {
                $this->missingOrders[] = $orderId;
            }
        }
    }
}
