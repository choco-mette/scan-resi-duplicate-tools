<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
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
        $this->import();
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
            $this->importStatus = 'Gagal mengimpor data. Pastikan format kolom benar.';
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
        $receipts = Receipt::with('expedition')->latest()->paginate(15);
        $expeditions = Expedition::all();

        return view('livewire.import-export', compact('receipts', 'expeditions'));
    }
}
