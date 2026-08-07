<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScanLog;

class History extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter;
    
    public function mount()
    {
        $this->dateFilter = \Carbon\Carbon::today()->format('Y-m-d');
    }
    
    // Customizing pagination theme for Tailwind is usually done in AppServiceProvider,
    // but for Livewire 3 it uses Tailwind by default.

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ScanLog::with(['user', 'receipt.expedition'])
                        ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where('scanned_tracking_number', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        $logs = $query->paginate(15);

        return view('livewire.history', compact('logs'));
    }
}
