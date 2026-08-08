<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReceiptsImport;
use App\Models\Receipt;
use App\Models\Expedition;
use Illuminate\Support\Facades\Log;

class ImportExport extends Component
{
    use WithFileUploads, WithPagination;

    public $file;
    public $importStatus = '';

    // Manual Add Form
    public $showModal = false;
    public $manual_tracking_number = '';
    public $manual_order_id = '';
    public $manual_expedition_id = '';

    public function updatedFile()
    {
        $this->resetPage();
        
        try {
            $checker = new \App\Imports\MissingReceiptsCheckImport;
            Excel::import($checker, $this->file);
            
            $missing = array_unique($checker->missingOrders);
            
            if (!empty($missing)) {
                $this->dispatch('show-missing-resi-alert', missingOrders: array_values($missing));
                return;
            }
            
            $this->import();
        } catch (\Exception $e) {
            Log::error('Preview error: ' . $e->getMessage());
            $this->importStatus = 'Gagal memvalidasi file: ' . $e->getMessage();
        }
    }

    #[On('confirm-import')]
    public function confirmImport()
    {
        $this->import();
    }

    #[On('cancel-import')]
    public function cancelImport()
    {
        $this->file = null;
        $this->importStatus = 'Proses import dibatalkan.';
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:10240',
        ]);

        try {
            Excel::import(new ReceiptsImport, $this->file);
            $this->importStatus = 'Sukses mengimpor data resi!';
            $this->file = null;
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            $this->importStatus = 'Gagal: ' . $e->getMessage();
        }
    }

    public function saveManual()
    {
        $this->validate([
            'manual_tracking_number' => 'required|unique:receipts,tracking_number',
            'manual_expedition_id' => 'nullable|exists:expeditions,id',
        ]);

        Receipt::create([
            'tracking_number' => $this->manual_tracking_number,
            'order_id' => $this->manual_order_id,
            'expedition_id' => $this->manual_expedition_id ?: null,
            'status' => 'unscanned',
        ]);

        $this->importStatus = 'Resi ' . $this->manual_tracking_number . ' berhasil ditambahkan manual.';
        
        // Reset form
        $this->manual_tracking_number = '';
        $this->manual_order_id = '';
        $this->manual_expedition_id = '';
        $this->showModal = false;
    }

    public function render()
    {
        $today = \Carbon\Carbon::today();
        $receipts = Receipt::with('expedition')->whereDate('created_at', $today)->latest()->paginate(15);
        $expeditions = Expedition::all();

        return view('livewire.import-export', compact('receipts', 'expeditions'));
    }
}
