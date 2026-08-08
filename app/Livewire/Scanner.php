<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Receipt;
use App\Models\ScanLog;
use Illuminate\Support\Facades\Auth;

class Scanner extends Component
{
    public $scannedTrackingNumber = '';
    public $scanStatus = null; // 'success', 'duplicate', 'unknown'
    public $message = '';
    public $scanTime = '';

    public function processScan($trackingNumber)
    {
        if (empty($trackingNumber)) return;

        $this->scannedTrackingNumber = $trackingNumber;
        $receipt = Receipt::where('tracking_number', $trackingNumber)->first();

        // 1. Unknown
        if (!$receipt) {
            $this->scanStatus = 'unknown';
            $this->message = "Resi {$trackingNumber} tidak terdaftar!";
            $this->scanTime = now()->format('d/m/Y H:i:s');
            $this->logScan('unknown');
            $this->dispatch('scanProcessed', status: 'unknown');
            return;
        }

        $productInfo = $receipt->product_name ? " ({$receipt->product_name})" : "";

        // 2. Duplicate
        if ($receipt->status === 'scanned') {
            $this->scanStatus = 'duplicate';
            $this->message = "DUPLIKAT! Resi {$trackingNumber}{$productInfo} sudah di-scan.";
            $this->scanTime = now()->format('d/m/Y H:i:s');
            $this->logScan('duplicate', $receipt->id);
            $this->dispatch('scanProcessed', status: 'duplicate');
            return;
        }

        // 3. Success
        $receipt->update([
            'status' => 'scanned',
            'scanned_at' => now(),
        ]);
        
        $this->scanStatus = 'success';
        $this->message = "OK! Resi {$trackingNumber}{$productInfo} valid.";
        $this->scanTime = now()->format('d/m/Y H:i:s');
        $this->logScan('success', $receipt->id);
        $this->dispatch('scanProcessed', status: 'success');
    }

    private function logScan($status, $receiptId = null)
    {
        ScanLog::create([
            'user_id' => Auth::id() ?? 2, // fallback to operator
            'receipt_id' => $receiptId,
            'scanned_tracking_number' => $this->scannedTrackingNumber,
            'status' => $status,
        ]);
    }

    public function render()
    {
        return view('livewire.scanner');
    }
}
