<?php

namespace App\Imports;

use App\Models\Receipt;
use App\Models\Expedition;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ReceiptsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $expeditionsCache = [];

    public function model(array $row)
    {
        // Pengecekan resi agar tidak kosong
        if (empty($row['no_resi'])) {
            return null;
        }

        // Hindari duplikat di master data, tapi gabungkan nama produk
        $existing = Receipt::where('tracking_number', $row['no_resi'])->first();
        $productName = $row['nama_produk'] ?? null;
        
        if ($existing) {
            if ($productName && !str_contains($existing->product_name ?? '', $productName)) {
                $existing->product_name = $existing->product_name ? $existing->product_name . ', ' . $productName : $productName;
                $existing->save();
            }
            return null;
        }

        $expeditionId = null;
        if (!empty($row['opsi_pengiriman'])) {
            $expName = trim($row['opsi_pengiriman']);
            if (!isset($this->expeditionsCache[$expName])) {
                $code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $expName), 0, 5));
                
                // Ensure code is unique
                while (Expedition::where('code', $code)->where('name', '!=', $expName)->exists()) {
                    $code = strtoupper(substr(uniqid(), -5));
                }

                $exp = Expedition::firstOrCreate(
                    ['name' => $expName],
                    ['code' => $code]
                );
                $this->expeditionsCache[$expName] = $exp->id;
            }
            $expeditionId = $this->expeditionsCache[$expName];
        }

        $deadlineAt = null;
        foreach ($row as $key => $val) {
            if (str_contains(strtolower((string)$key), 'harus_dikirimkan_sebelum')) {
                // Parse date string (e.g. "2026-08-06 20:28")
                if (!empty($val)) {
                    try {
                        if (is_numeric($val)) {
                            $deadlineAt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d H:i:s');
                        } else {
                            $deadlineAt = \Carbon\Carbon::parse($val)->format('Y-m-d H:i:s');
                        }
                    } catch (\Exception $e) {
                        $deadlineAt = null;
                    }
                }
                break;
            }
        }

        return new Receipt([
            'tracking_number' => $row['no_resi'],
            'order_id'        => $row['booking_sn'] ?? $row['no_pesanan'] ?? null,
            'product_name'    => $row['nama_produk'] ?? null,
            'deadline_at'     => $deadlineAt,
            'quantity'        => isset($row['jumlah']) ? (int)$row['jumlah'] : null,
            'expedition_id'   => $expeditionId,
            'status'          => 'unscanned',
        ]);
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
